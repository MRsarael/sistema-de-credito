<?php

namespace App\Repositories\Simulation;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;
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
        return new JsonResource([]);
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}
