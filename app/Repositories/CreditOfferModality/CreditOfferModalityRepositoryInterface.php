<?php

namespace App\Repositories\CreditOfferModality;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface CreditOfferModalityRepositoryInterface extends BaseRepositoryInterface
{
    public function search(array $data): Collection;
    public function storeFromArray(array $data): bool;
}
