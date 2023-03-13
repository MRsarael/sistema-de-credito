<?php

namespace App\Business\PersonalCreditOffer;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class CreditSystem implements CreditSystemInterface
{
    private int $idPerson = 0;

    public function __construct() {/**/}
    
    public function consult(): Collection
    {
        try {
            $query = $this->query();
            return $this->getCollectionResult($query);
        } catch (\Exception $e) {
            throw new \Exception("Erro ao montar dados do sistema de crédito", 500);
        }
    }

    private function query()
    {
        $sql = "SELECT
                    p.id         AS person_id,
                    p.name       AS person_name,
                    p.cpf        AS person_cpf,
                    p.age        AS person_age,
                    p.email      AS person_email,
                    p.created_at AS person_created_at,
                    p.updated_at AS person_updated_at,
                    p.deleted_at AS person_deleted_at,

                    pco.id                       AS personal_credit_offer_id,
                    pco.id_person                AS personal_credit_offer_id_person,
                    pco.id_credit_offer_modality AS personal_credit_offer_id_credit_offer_modality,
                    pco.created_at               AS personal_credit_offer_created_at,
                    pco.updated_at               AS personal_credit_offer_updated_at,

                    com.id                       AS credit_offer_modality_id,
                    com.id_financial_institution AS credit_offer_modality_id_financial_institution,
                    com.description              AS credit_offer_modality_description,
                    com.cod                      AS credit_offer_modality_cod,
                    com.created_at               AS credit_offer_modality_created_at,
                    com.updated_at               AS credit_offer_modality_updated_at,
                    
                    fi.id         AS financial_institution_id,
                    fi.id_gosat   AS financial_institution_id_gosat,
                    fi.name       AS financial_institution_name,
                    fi.created_at AS financial_institution_created_at,
                    fi.updated_at AS financial_institution_updated_at,

                    s.id                       AS simulation_id,
                    s.id_personal_credit_offer AS simulation_id_personal_credit_offer,
                    s.min_installments         AS simulation_min_installments,
                    s.max_installments         AS simulation_max_installments,
                    s.min_value                AS simulation_min_value,
                    s.max_value                AS simulation_max_value,
                    s.month_interest           AS simulation_month_interest,
                    s.created_at               AS simulation_created_at
                FROM
                    person p
                    LEFT JOIN personal_credit_offer  AS pco ON pco.id_person = p.id
                    INNER JOIN credit_offer_modality AS com ON com.id = pco.id_credit_offer_modality
                    INNER JOIN financial_institution AS fi  ON fi.id = com.id_financial_institution
                    LEFT JOIN simulation             AS s   ON s.id_personal_credit_offer = pco.id
                WHERE
                    1=1
        ";

        if($this->idPerson) {
            $sql = $sql . " AND p.id = {$this->idPerson}";
        }

        return DB::select($sql);
    }

    private function getCollectionResult(array $query): Collection
    {
        $grouping = array();
        $result = array();

        // Agrupando ofertas de crédito por person
        foreach ($query as $key => $value) $grouping[$value->person_id][] = $value;

        if(is_array($grouping) && !empty($grouping)) {
            foreach ($grouping as $key => $offers) {
                // Todas as ofertas disponíveis para o person
                $dataCollection = collect($offers);

                // Montando dados do person
                $personObject = $this->buildPerson($dataCollection->first());

                $personalCreditOffer = $dataCollection->map(function($offer) use ($dataCollection) {
                    $creditOfferObject = new stdClass();
                    $creditOfferObject->id = Crypt::encryptString($offer->personal_credit_offer_id);
                    $creditOfferObject->creditOfferModality = collect([]);

                    // Filtrando as modalidades de crédito para a oferta:
                    $creditOfferModalityCollection = $dataCollection->where('credit_offer_modality_id', $offer->personal_credit_offer_id_credit_offer_modality)->all();
                    
                    foreach ($creditOfferModalityCollection as $key => $value) {
                        $creditOfferModalityObject = new stdClass();
                        $creditOfferModalityObject->id = Crypt::encryptString($value->credit_offer_modality_id);
                        $creditOfferModalityObject->description = $value->credit_offer_modality_description;
                        $creditOfferModalityObject->cod = $value->credit_offer_modality_cod;

                        // Filtrando dados da instituição bancária
                        $financialInstitution = $dataCollection->where('financial_institution_id', $value->credit_offer_modality_id_financial_institution)->first();

                        if($financialInstitution !== null) {
                            $creditOfferModalityObject->idInstitution = Crypt::encryptString($financialInstitution->financial_institution_id);
                            $creditOfferModalityObject->idGosat = $financialInstitution->financial_institution_id_gosat;
                            $creditOfferModalityObject->nameInstitution = $financialInstitution->financial_institution_name;
                        }

                        $creditOfferObject->creditOfferModality->push($creditOfferModalityObject);
                    }

                    // Filtrando as simulações realizadas para a oferta de crédito
                    $creditOfferObject->simulation = $dataCollection->where('simulation_id_personal_credit_offer', $offer->personal_credit_offer_id)
                        ->map(function($simulation) {
                            $simulationObject = new stdClass();
                            $simulationObject->min_installments = $simulation->simulation_min_installments;
                            $simulationObject->max_installments = $simulation->simulation_max_installments;
                            $simulationObject->min_value = $simulation->simulation_min_value;
                            $simulationObject->max_value = $simulation->simulation_max_value;
                            $simulationObject->month_interest = $simulation->simulation_month_interest;
                            return $simulationObject;
                        });
                    
                    return $creditOfferObject;
                });

                $personObject->personalCreditOffer = $personalCreditOffer;
                $result[] = $personObject;
            }
        }

        return new Collection($result);
    }

    public function buildPerson(Object $dataPerson): Object
    {
        $personObject = new stdClass();
        $personObject->id = Crypt::encryptString($dataPerson->person_id);
        $personObject->name = $dataPerson->person_name;
        $personObject->cpf = $dataPerson->person_cpf;
        $personObject->age = $dataPerson->person_age;
        $personObject->email = $dataPerson->person_email;
        $personObject->personalCreditOffer = null;
        return $personObject;
    }

    public function setIdPerson(int $idPerson): void
    {
        $this->idPerson = $idPerson;
    }

    public function getIdPerson(): int
    {
        return $this->idPerson;
    }
}
