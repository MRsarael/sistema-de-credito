<?php

namespace App\Business\PersonalCreditOffer;

use Illuminate\Support\Collection;

interface CreditSystemInterface
{
    public function consult(): Collection;
    public function setIdPerson(int $idPerson): void;
    public function getIdPerson(): int;
}
