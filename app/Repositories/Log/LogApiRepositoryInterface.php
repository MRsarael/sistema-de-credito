<?php

namespace App\Repositories\Log;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

interface LogApiRepositoryInterface extends BaseRepositoryInterface
{
    public function new(array $data): JsonResource;
}
