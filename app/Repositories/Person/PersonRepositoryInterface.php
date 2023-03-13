<?php

namespace App\Repositories\Person;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

interface PersonRepositoryInterface extends BaseRepositoryInterface
{
    public function search(array $data): Collection;
    public function new(array $data): JsonResource;
    public function update(int $id, array $data): JsonResource;
    public function find(int $id): JsonResource;
    public function delete(int $id): bool;
}
