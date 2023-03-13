<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

interface PersonaServiceInterface
{
    public function persons(): \Illuminate\Support\Collection;
    public function newPerson(array $data): JsonResource;
    public function updatePerson(array $data): JsonResource;
    public function showPerson(int $idPerson): JsonResource;
    public function deletePerson(int $idPerson): bool;
}
