<?php

namespace App\Repositories\CreditOfferModality;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\CreditOfferModality;

class CreditOfferModalityRepository implements CreditOfferModalityRepositoryInterface
{
    private CreditOfferModality $model;
    
    public function __construct(CreditOfferModality $creditOfferModality)
    {
        $this->model = $creditOfferModality;
    }

    public function formatModel(Model $data): JsonResource
    {
        return new JsonResource([
            'id'                       => Crypt::encryptString($data->id),
            'id_financial_institution' => $data->id_financial_institution,
            'description'              => $data->description,
            'cod'                      => $data->cod,
            'created_at'               => $data->created_at->format('Y-m-d')
        ]);
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function search(array $data): Collection
    {
        $response = array();

        $responseCollect = $this->model->select(
            '*'
        )
        ->when(isset($data['id_financial_institution']) && $data['id_financial_institution'] != '', function($query) use ($data){
            $query->where('id_financial_institution', $data['id_financial_institution']);
        })
        ->when(isset($data['cod']) && $data['cod'] != '', function($query) use ($data){
            $query->where('cod', $data['cod']);
        })
        ->get();

        foreach ($responseCollect->all() as $value) {
            $response[] = $this->formatModel($value);
        }

        return collect($response);
    }

    public function storeFromArray(array $data): bool
    {
        $dataInsert = array();

        foreach ($data as $value) {
            $dataInsert[] = [
                'id_financial_institution' => $value['id_financial_institution'],
                'cod'                      => $value['cod'],
                'description'              => $value['description'],
                'created_at'               => Carbon::now(),
                'updated_at'               => Carbon::now()
            ];
        }
        
        return $this->model->insert($dataInsert);
    }
}
