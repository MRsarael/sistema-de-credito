<?php

namespace App\Business\Gosat;

use App\Repositories\Log\LogApiRepositoryInterface;
use App\Repositories\Simulation\SimulationRepositoryInterface;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class Simulation extends GosatApi
{
    private Client $client;
    private ObjectConsultSimulationOfferInterface $objectConsult;
    private SimulationRepositoryInterface $simulationRepository;

    public function __construct(Client $client, LogApiRepositoryInterface $logApiRepository, SimulationRepositoryInterface $simulationRepository)
    {
        $this->client = $client;
        $this->logApiRepository = $logApiRepository;
        $this->simulationRepository = $simulationRepository;
    }

    public static function make(ObjectConsultSimulationOfferInterface $objectConsult): GosatApi
    {
        $self = app(Simulation::class);
        $self->setObjectConsult($objectConsult);
        return $self;
    }

    public function setObjectConsult(ObjectConsultSimulationOfferInterface $objectConsult)
    {
        $this->objectConsult = $objectConsult;
    }

    public function consult(): Collection
    {
        DB::beginTransaction();

        try {
            $method = 'POST';
            $url = $this->baseUrlApi . '/simulacao/oferta';
            $response = $this->client->request($method, $url, ['form_params' => $this->objectConsult->getBody()]);

            if($response->getStatusCode() == 200) {
                $contentjson = $response->getBody()->getContents();
                $contentObject = \json_decode($contentjson);
                $this->log($this->objectConsult->toString(), $contentjson, '200', 'GOSAT', $method, $url);
                $this->registerConsultHistory($contentObject);
            } else {
                throw new \Exception("Erro ao tentar acessar servidor da Gosat", $response->getStatusCode());
            }

            DB::commit();
            return collect($contentObject);
        } catch (\Exception $e) {
            DB::rollback();

            $message = $e->getMessage();
            $code = $e->getCode();
            $this->log($this->objectConsult->toString(), $message, $code, 'GOSAT', $method, $url);
            
            throw new \Exception($message, $code);
        }
    }

    private function registerConsultHistory($contentObject): void
    {
        $personalCreditOffer = $this->objectConsult->getPersonalCreditOffer();
        $personalCreditOffer->id = Crypt::decryptString($personalCreditOffer->id);
        $simulationCreditOffer = $this->simulationRepository->getSimulationCreditOffer($personalCreditOffer->id);

        if($simulationCreditOffer->isNotEmpty()) {
            $this->simulationRepository->deleteByIdPersonalCreditOffer($personalCreditOffer->id);
        }

        $this->simulationRepository->new([
            'id_personal_credit_offer' => $personalCreditOffer->id,
            'min_installments'         => $contentObject->QntParcelaMin,
            'max_installments'         => $contentObject->QntParcelaMax,
            'min_value'                => $contentObject->valorMin,
            'max_value'                => $contentObject->valorMax,
            'month_interest'           => $contentObject->jurosMes
        ]);
    }
}
