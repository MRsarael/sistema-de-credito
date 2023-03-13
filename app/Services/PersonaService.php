<?php

namespace App\Services;

use App\Repositories\Person\PersonRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class PersonaService implements PersonaServiceInterface
{
    private PersonRepositoryInterface $personRepository;

    public function __construct(PersonRepositoryInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function persons(): \Illuminate\Support\Collection
    {
        return $this->personRepository->search([]);
    }

    public function newPerson(array $data): JsonResource
    {
        $personExists = $this->personRepository->search(['cpf' => $data['cpf'], 'email' => $data['email']]);

        if($personExists->isNotEmpty()) {
            throw new \Exception("Esta pessoa já foi cadastrada", 401);
        }

        return $this->personRepository->new($data);
    }

    public function updatePerson(array $data): JsonResource
    {
        $personExists = $this->personRepository->search(['cpf' => $data['cpf'], 'email' => $data['email']]);

        if($personExists->count() > 1) {
            throw new \Exception("Já existe um registro com estas credenciais", 401);
        }

        $idPerson = Crypt::decryptString($data['id']);

        return $this->personRepository->update($idPerson, $data);
    }

    public function showPerson(int $idPerson): JsonResource
    {
        $person = $this->personRepository->find($idPerson);

        if(empty($person->toArray(false))) {
            throw new \Exception("Não encontrado", 401);
        }

        return $person;
    }

    public function deletePerson(int $idPerson): bool
    {
        $person = $this->personRepository->find($idPerson);

        if(empty($person->toArray(false))) {
            throw new \Exception("Não encontrado", 401);
        }

        if(!$this->personRepository->delete($idPerson)) {
            throw new \Exception("Ocorreu algum erro ao tentar remover o registro", 500);
        }

        return true;
    }
}
