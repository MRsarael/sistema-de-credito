<?php

namespace App\Business\PersonalCreditOffer;

use App\Business\PersonalCreditOffer\CreditSystemInterface;
use App\Business\PersonalCreditOffer\CreditSystem;

class PersonalCreditOfferFactory
{
    public static function make($idPerson = null): CreditSystemInterface
    {
        $creditSystem = app(CreditSystem::class);
        $creditSystem->setIdPerson($idPerson);
        return $creditSystem;
    }
}
