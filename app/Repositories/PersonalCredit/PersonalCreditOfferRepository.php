<?php

namespace App\Repositories\PersonalCredit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\PersonalCreditOffer;

class PersonalCreditOfferRepository implements PersonalCreditOfferRepositoryInterface
{
    private PersonalCreditOffer $model;
    
    public function __construct(PersonalCreditOffer $personalCreditOffer)
    {
        $this->model = $personalCreditOffer;
    }

    public function formatModel(Model $data): JsonResource
    {
        return new JsonResource([
            'id'                       => Crypt::encryptString($data->id),
            'id_person'                => $data->id_person,
            'id_credit_offer_modality' => $data->id_credit_offer_modality,
            'created_at'               => $data->created_at->format('Y-m-d')
        ]);
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function creditOfferPerson($idPerson): Collection
    {
        $response = array();

        $creditOffer = $this->model->select(
            '*'
        )
        ->where('id_person', $idPerson)
        ->get();

        foreach ($creditOffer->all() as $value) {
            $response[] = $this->formatModel($value);
        }
        
        return new Collection($response);
    }

    public function new(int $idPerson, int $idCreditOfferModality): bool
    {
        return $this->model->insert([
            'id_person'                => $idPerson,
            'id_credit_offer_modality' => $idCreditOfferModality,
            'created_at'               => Carbon::now(),
            'updated_at'               => Carbon::now()
        ]);
    }
}
