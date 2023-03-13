<?php

namespace App\Repositories\FinancialInstitution;

use Illuminate\Support\Collection;

use App\Repositories\BaseRepositoryInterface;

interface FinancialInstitutionRepositoryInterface extends BaseRepositoryInterface
{
    public function getForIdGosatList(array $idList): Collection;
    public function storeFromArray(array $data): bool;
}
