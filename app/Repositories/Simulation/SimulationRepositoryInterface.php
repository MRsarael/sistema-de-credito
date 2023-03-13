<?php

namespace App\Repositories\Simulation;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface SimulationRepositoryInterface extends BaseRepositoryInterface
{
    public function getSimulationCreditOffer(int $idPersonalCreditOffer): Collection;
    public function new(array $data): JsonResource;
    public function deleteByIdPersonalCreditOffer(int $idPersonalCreditOffer): bool;
}
