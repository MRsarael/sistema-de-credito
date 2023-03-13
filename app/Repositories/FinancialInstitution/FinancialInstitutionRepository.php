<?php

namespace App\Repositories\FinancialInstitution;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use App\Models\FinancialInstitution;

class FinancialInstitutionRepository implements FinancialInstitutionRepositoryInterface
{
    private FinancialInstitution $model;
    
    public function __construct(FinancialInstitution $financialInstitution)
    {
        $this->model = $financialInstitution;
    }

    public function formatModel(Model $data): JsonResource
    {
        return new JsonResource([
            'id'         => Crypt::encryptString($data->id),
            'id_gosat'   => $data->id_gosat,
            'name'       => $data->name,
            'created_at' => $data->created_at->format('Y-m-d')
        ]);
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getForIdGosatList(array $idList): Collection
    {
        $response = array();
        $financialInstitutions = $this->model->whereIn('id_gosat', $idList)->get();

        foreach ($financialInstitutions->all() as $value) {
            $response[] = $this->formatModel($value);
        }

        return collect($response);
    }

    public function storeFromArray(array $data): bool
    {
        $dataInsert = array();

        foreach ($data as $value) {
            $dataInsert[] = [
                'id_gosat'   => $value['id_gosat'],
                'name'       => $value['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        return $this->model->insert($dataInsert);
    }
}