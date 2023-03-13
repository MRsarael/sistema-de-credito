<?php

namespace App\Business\Gosat;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\PersonalCredit\PersonalCreditOfferRepositoryInterface;
use App\Repositories\FinancialInstitution\FinancialInstitutionRepositoryInterface;
use App\Repositories\CreditOfferModality\CreditOfferModalityRepositoryInterface;
use App\Business\PersonalCreditOffer\PersonalCreditOfferFactory;
use Illuminate\Support\Collection;
use App\Business\Gosat\ConsultOfferCreditInterface;
use App\Repositories\Log\LogApiRepositoryInterface;

class CreditConsultation extends GosatApi
{
    private Client $client;
    private Collection $financialInstitution;
    private Collection $creditOfferModality;
    private PersonalCreditOfferRepositoryInterface $personalCreditOfferRepository;
    private FinancialInstitutionRepositoryInterface $financialInstitutionRepository;
    private CreditOfferModalityRepositoryInterface $creditOfferModalityRepository;
    private ConsultOfferCreditInterface $objectConsult;

    public function __construct(
        PersonalCreditOfferRepositoryInterface $personalCreditOfferRepository,
        FinancialInstitutionRepositoryInterface $financialInstitutionRepository,
        CreditOfferModalityRepositoryInterface $creditOfferModalityRepository,
        Client $client,
        LogApiRepositoryInterface $logApiRepository
    ) {
        $this->client = $client;
        $this->personalCreditOfferRepository = $personalCreditOfferRepository;
        $this->financialInstitutionRepository = $financialInstitutionRepository;
        $this->creditOfferModalityRepository = $creditOfferModalityRepository;
        $this->logApiRepository = $logApiRepository;
        $this->financialInstitution = new Collection([]);
        $this->creditOfferModality = new Collection([]);
    }

    public static function make(ConsultOfferCreditInterface $objectConsult): GosatApi
    {
        $self = app(CreditConsultation::class);
        $self->setObjectConsult($objectConsult);
        return $self;
    }

    public function setObjectConsult(ConsultOfferCreditInterface $objectConsult): void
    {
        $this->objectConsult = $objectConsult;
    }

    public function consult(): Collection
    {
        DB::beginTransaction();
        
        $method = 'POST';
        $url = $this->baseUrlApi . '/simulacao/credito';

        try {
            $response = $this->client->request($method, $url, ['form_params' => ['cpf' => $this->objectConsult->getCpf()]]);
            
            if($response->getStatusCode() == 200) {
                $contentjson = $response->getBody()->getContents();
                $contentObject = \json_decode($contentjson);

                $this->log($this->objectConsult->toString(), $contentjson, '200', 'GOSAT', $method, $url);
    
                if(is_array($contentObject->instituicoes) && !empty($contentObject->instituicoes)) {
                    foreach ($contentObject->instituicoes as $institution) {
                        $this->financialInstitution->push([
                            'id_gosat' => $institution->id,
                            'name'     => $institution->nome
                        ]);
    
                        if(is_array($institution->modalidades) && !empty($institution->modalidades)) {
                            foreach ($institution->modalidades as $modality) {
                                $this->creditOfferModality->push([
                                    'id_gosat' => $institution->id,
                                    'cod'                      => $modality->cod,
                                    'description'              => $modality->nome
                                ]);
                            }
                        }
                    }
    
                    $this->verifyFinancialInstitution();
                    $this->verifyCreditOfferModality();
                }

                DB::commit();
            } else {
                throw new \Exception("Erro ao tentar acessar servidor da Gosat", $response->getStatusCode());
            }
        } catch (\Exception $e) {
            DB::rollback();

            $message = $e->getMessage();
            $code = $e->getCode();
            $this->log($this->objectConsult->toString(), $message, $code, 'GOSAT', $method, $url);
            
            throw new \Exception($message, 500);
        }
        
        $person = PersonalCreditOfferFactory::make($this->objectConsult->getId());
        return $person->consult();
    }

    private function verifyFinancialInstitution(): void
    {
        if($this->financialInstitution->count() > 0) {
            $financialInstitutionValidInsert = $this->financialInstitution->all();

            $idList = $this->financialInstitution->map(function($value) {
                return $value['id_gosat'];
            })->all();

            $financialInstitutions = $this->financialInstitutionRepository->getForIdGosatList($idList);

            if($financialInstitutions->count() > 0) {
                $idExistsList = $financialInstitutions->map(function($value) {
                    return $value['id_gosat'];
                })->all();

                // Filtrando somente os registros que podem ser inseridos:
                $financialInstitutionValidInsert = $this->financialInstitution->whereNotIn('id_gosat', $idExistsList)->all();
            }

            // Inserindo os resultados que sobraram
            $this->financialInstitutionRepository->storeFromArray($financialInstitutionValidInsert);
        }
    }

    private function verifyCreditOfferModality(): void
    {
        if($this->creditOfferModality->count() > 0) {
            $financialInstitution = $this->joinIdFinancialInstitution($this->creditOfferModality);
            
            foreach ($financialInstitution->all() as $key => $value) {
                $creditOfferModalityCollect = $this->creditOfferModalityRepository->search([
                    'id_financial_institution' => $value['id_financial_institution'],
                    'cod'                      => $value['cod']
                ]);

                // Filtrando somente os registros que podem ser inseridos:
                if($creditOfferModalityCollect->count() > 0) {
                    $creditOfferModalityInvalid = $financialInstitution
                        ->where('id_financial_institution', $value['id_financial_institution'])
                        ->where('cod', $value['cod'])
                        ->all();
                    
                    foreach ($creditOfferModalityInvalid as $key => $value)
                        $financialInstitution->forget($key);
                }
            }

            // Inserindo os resultados que sobraram
            $this->creditOfferModalityRepository->storeFromArray($financialInstitution->all());
            $this->verifyPersonalCreditOffer($financialInstitution);
        }
    }

    private function verifyPersonalCreditOffer(Collection $financialInstitution): void
    {
        // $personalCreditOffer = $this->personalCreditOfferRepository->creditOfferPerson($this->objectConsult->getId());

        $financialInstitutionList = $financialInstitution->map(function($value) {
            return [
                'id_financial_institution' => $value['id_financial_institution'],
                'cod' => $value['cod']
            ];
        })->unique()->all();

        foreach ($financialInstitutionList as $key => $value) {
            $creditOfferModalityCollect = $this->creditOfferModalityRepository->search([
                'id_financial_institution' => $value['id_financial_institution'],
                'cod'                      => $value['cod']
            ]);

            if($creditOfferModalityCollect->count() == 0) {
                throw new \Exception("Nenhuma modalidade foi registrada ainda", 404);
            }
            
            $idCreditOfferModality = (int) Crypt::decryptString($creditOfferModalityCollect->first()['id']);
            $idPerson = (int) $this->objectConsult->getId();
            $this->personalCreditOfferRepository->new($idPerson, $idCreditOfferModality);
        }
    }

    private function joinIdFinancialInstitution(Collection $creditOfferModality): Collection
    {
        $creditOfferModalityResult = $creditOfferModality->all();

        $idGosatList = $creditOfferModality->map(function($value) {
            return $value['id_gosat'];
        })->unique()->all();

        $financialInstitutions = $this->financialInstitutionRepository->getForIdGosatList($idGosatList);

        if($financialInstitutions->count() == 0) {
            throw new \Exception("Nenhuma instituição foi registrada ainda", 404);
        }

        foreach ($creditOfferModalityResult as $key => &$value) {
            $institutions = $financialInstitutions->where('id_gosat', $value['id_gosat'])->first();
            $value['id_financial_institution'] = (int) Crypt::decryptString($institutions['id']);
        }

        return collect($creditOfferModalityResult);
    }
}
