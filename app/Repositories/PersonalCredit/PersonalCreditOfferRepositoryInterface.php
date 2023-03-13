<?php

namespace App\Repositories\PersonalCredit;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface PersonalCreditOfferRepositoryInterface extends BaseRepositoryInterface
{
    public function creditOfferPerson($idPerson): Collection;
    public function new(int $idPerson, int $idCreditOfferModality): bool;
}
