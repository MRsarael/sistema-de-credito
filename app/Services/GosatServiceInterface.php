<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

interface GosatServiceInterface
{
    public function creditConsultation(int $idPerson): JsonResource;
}
