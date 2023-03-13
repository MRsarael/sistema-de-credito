<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function getModel(): \Illuminate\Database\Eloquent\Model;
    public function formatModel(\Illuminate\Database\Eloquent\Model $data): \Illuminate\Http\Resources\Json\JsonResource;
}
