<?php

namespace App\Repositories\Person;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Collection;

class PersonRepository implements PersonRepositoryInterface
{
    private Person $model;
    
    public function __construct(Person $person)
    {
        $this->model = $person;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function formatModel(Model $person): JsonResource
    {
        return new \App\Http\Resources\PersonResource([
            'id'         => Crypt::encryptString($person->id),
            'name'       => $person->name,
            'cpf'        => $person->cpf,
            'age'        => $person->age,
            'email'      => $person->email,
            'created_at' => $person->created_at->format('Y-m-d')
        ]);
    }

    public function find(int $id): JsonResource
    {
        $person = $this->model->find($id);

        if($person === null) {
            return new JsonResource([]);
        }

        return $this->formatModel($person);
    }

    public function search(array $data): Collection
    {
        $response = array();

        $persons = $this->model->select(
            '*'
        )
        ->when(isset($data['cpf']) && $data['cpf'] !== '', function($query) use ($data) {
            $query->where('cpf', $data['cpf']);
        })
        ->when(isset($data['email']) && $data['email'] !== '', function($query) use ($data) {
            $query->where('email', $data['email']);
        })
        ->get();
        
        if($persons->count()) {
            foreach ($persons->all() as $key => $value) {
                $response[] = $this->formatModel($value);
            }
        }

        return collect($response);
    }

    public function new(array $data): JsonResource
    {
        $person = $this->model->create([
            'name'  => $data['name'],
            'cpf'   => $data['cpf'],
            'age'   => $data['age'],
            'email' => $data['email']
        ]);

        $person = $person->fresh();

        return $this->formatModel($person);
    }

    public function update(int $id, array $data): JsonResource
    {
        $person = $this->model->find($id);

        if($person === null) {
            throw new \Exception("Registro não encontrado", 401);
        }

        $person->name = $data['name'];
        $person->cpf = $data['cpf'];
        $person->age = $data['age'];
        $person->email = $data['email'];
        
        if(!$person->save()) {
            throw new \Exception("Ocorreu algum erro ao tentar editar o registro", 500);
        }
        
        return $this->formatModel($person);
    }

    public function delete(int $id): bool
    {
        $person = $this->model->find($id);
        return $person->delete();
    }

    public function credit($idPerson = null): Collection
    {
        $result = $this->model->select(
            'person.id AS person_id',
            'person.name AS name_person',
            'person.cpf',
            'credit_offer_modality.id AS credit_offer_modality_id',
            'credit_offer_modality.description AS modality',
            'credit_offer_modality.cod AS modality_cod',
            'financial_institution.id_gosat',
            'financial_institution.name AS institution'
        )
        ->leftJoin('personal_credit_offer', '=', 'personal_credit_offer.id_person', 'person.id')
        ->leftJoin('credit_offer_modality', '=', 'credit_offer_modality.id', 'personal_credit_offer.id_credit_offer_modality')
        ->leftJoin('financial_institution', '=', 'financial_institution.id', 'credit_offer_modality.id_financial_institution');

        if($idPerson !== null) {
            $result->where('person.id', $idPerson);
        }

        $data = $result->get();

        dd($data);

        return collect([]);
    }
}
