<?php

namespace App\Business\Gosat;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use stdClass;

class ObjectConsultSimulationOffer implements ObjectConsultSimulationOfferInterface
{
    private Object $person;
    private Object $personalCreditOffer;
    private Object $creditOfferModality;
    private Object $financialInstitution;
    
    public static function make(int $idPerson, int $idInstitution, string $codModality): self
    {
        $self = app(ObjectConsultSimulationOffer::class);
        $self->query($idPerson, $idInstitution, $codModality);
        return $self;
    }

    public function toString(): string
    {
        return \json_encode($this->getBody());
    }

    public function query(int $idPerson, int $idInstitution, string $codModality)
    {
        $sql = "SELECT
                    p.id         AS person_id,
                    p.name       AS person_name,
                    p.cpf        AS person_cpf,
                    p.age        AS person_age,
                    p.email      AS person_email,
                    p.created_at AS person_created_at,

                    pco.id                       AS personal_credit_offer_id,
                    pco.id_person                AS personal_credit_offer_id_person,
                    pco.id_credit_offer_modality AS personal_credit_offer_id_credit_offer_modality,
                    pco.created_at               AS personal_credit_offer_created_at,
                    
                    com.id                       AS credit_offer_modality_id,
                    com.id_financial_institution AS credit_offer_modality_id_financial_institution,
                    com.description              AS credit_offer_modality_description,
                    com.cod                      AS credit_offer_modality_cod,
                    com.created_at               AS credit_offer_modality_created_at,

                    fi.id         AS financial_institution_id,
                    fi.id_gosat   AS financial_institution_id_gosat,
                    fi.name       AS financial_institution_name,
                    fi.created_at AS financial_institution_created_at
                FROM
                    person p
                    LEFT JOIN personal_credit_offer pco  ON pco.id_person = p.id
                    INNER JOIN credit_offer_modality com ON com.id = pco.id_credit_offer_modality 
                    INNER JOIN financial_institution fi  ON fi.id = com.id_financial_institution
                WHERE
                    p.id = {$idPerson}
                    AND fi.id = {$idInstitution}
                    AND com.cod = '{$codModality}'
        ";

        $query = collect(DB::select($sql));

        if($query->isEmpty()) {
            throw new \Exception("Ocorreu algum erro ao consultar registro", 500);
        }

        $data = $query->first();
        $this->setPerson($data);
        $this->setPersonalCreditOffer($data);
        $this->setCreditOfferModality($data);
        $this->setFinancialInstitution($data);
    }

    private function setPerson($data): void
    {
        $person = new stdClass();
        $person->id = Crypt::encryptString($data->person_id);
        $person->name = $data->person_name;
        $person->cpf = $data->person_cpf;
        $person->age = $data->person_age;
        $person->email = $data->person_email;
        $person->created_at = $data->person_created_at;
        $this->person = $person;
    }

    public function getPerson(): object
    {
        return $this->person;
    }
    
    private function setPersonalCreditOffer($data): void
    {
        $personalCreditOffer = new stdClass();
        $personalCreditOffer->id = Crypt::encryptString($data->personal_credit_offer_id);
        $personalCreditOffer->idPerson = $data->personal_credit_offer_id_person;
        $personalCreditOffer->idCreditOfferModality = $data->personal_credit_offer_id_credit_offer_modality;
        $personalCreditOffer->created_at = $data->personal_credit_offer_created_at;
        $this->personalCreditOffer = $personalCreditOffer;
    }

    public function getPersonalCreditOffer(): object
    {
        return $this->personalCreditOffer;
    }
    
    private function setCreditOfferModality($data): void
    {
        $creditOfferModality = new stdClass();
        $creditOfferModality->id = Crypt::encryptString($data->credit_offer_modality_id);
        $creditOfferModality->idFinancialInstitution = $data->credit_offer_modality_id_financial_institution;
        $creditOfferModality->description = $data->credit_offer_modality_description;
        $creditOfferModality->cod = $data->credit_offer_modality_cod;
        $creditOfferModality->created_at = $data->credit_offer_modality_created_at;
        $this->creditOfferModality = $creditOfferModality;
    }

    public function getCreditOfferModality(): object
    {
        return $this->creditOfferModality;
    }
    
    private function setFinancialInstitution($data): void
    {
        $financialInstitution = new stdClass();
        $financialInstitution->id = Crypt::encryptString($data->financial_institution_id);
        $financialInstitution->id_gosat = $data->financial_institution_id_gosat;
        $financialInstitution->name = $data->financial_institution_name;
        $financialInstitution->created_at = $data->financial_institution_created_at;
        $this->financialInstitution = $financialInstitution;
    }

    public function getFinancialInstitution(): object
    {
        return $this->financialInstitution;
    }

    public function getBody(): array
    {
        return [
            'cpf'            => $this->person->cpf,
            'instituicao_id' => $this->financialInstitution->id_gosat,
            'codModalidade'  => $this->creditOfferModality->cod,
        ];
    }
}
