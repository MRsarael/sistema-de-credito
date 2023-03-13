<?php

namespace App\Repositories\Simulation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Collection;
use App\Models\Simulation;

class SimulationRepository implements SimulationRepositoryInterface
{
    private Simulation $model;
    
    public function __construct(Simulation $simulation)
    {
        $this->model = $simulation;
    }

    public function formatModel(Model $data): JsonResource
    {
        return new JsonResource([
            'id'                       => Crypt::encryptString($data->id),
            'id_personal_credit_offer' => $data->id_personal_credit_offer,
            'min_installments'         => $data->min_installments,
            'max_installments'         => $data->max_installments,
            'min_value'                => $data->min_value,
            'max_value'                => $data->max_value,
            'month_interest'           => $data->month_interest,
            'created_at'               => $data->created_at->format('Y-m-d')
        ]);
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getSimulationCreditOffer(int $idPersonalCreditOffer): Collection
    {
        $response = new Collection();
        $result = $this->model->where('id_personal_credit_offer', $idPersonalCreditOffer)->get();

        foreach ($result->all() as $key => $simulation)
            $response->push($this->formatModel($simulation));
        
        return $response;
    }

    public function new(array $data): JsonResource
    {
        $simulation = $this->model->create([
            'id_personal_credit_offer' => $data['id_personal_credit_offer'],
            'min_installments'         => $data['min_installments'],
            'max_installments'         => $data['max_installments'],
            'min_value'                => $data['min_value'],
            'max_value'                => $data['max_value'],
            'month_interest'           => $data['month_interest']
        ]);
        
        return new JsonResource($this->formatModel($simulation));
    }

    public function deleteByIdPersonalCreditOffer(int $idPersonalCreditOffer): bool
    {
        return $this->model->where('id_personal_credit_offer', $idPersonalCreditOffer)->delete();
    }
}
