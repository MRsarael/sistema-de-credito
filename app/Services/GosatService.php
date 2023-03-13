<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

use App\Repositories\Person\PersonRepositoryInterface;
use App\Business\Gosat\GosatApiFactory;

class GosatService implements GosatServiceInterface
{
    private PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function creditConsultation(int $idPerson): JsonResource
    {
        $person = $this->personRepository->find($idPerson);
        
        if(empty($person->toArray(false))) {
            throw new \Exception("Não encontrado", 401);
        }

        $person['id'] = (int) Crypt::decryptString($person['id']);
        $consult = GosatApiFactory::creditConsultation($person);
        $result = $consult->consult();
        
        return new JsonResource($result->all());
    }

    public function creditOfferConsult(mixed $idPerson): JsonResource
    {
        $creditOffer = $this->personRepository->credit($idPerson);
        dd($creditOffer);
        return new JsonResource([]);
    }
}
