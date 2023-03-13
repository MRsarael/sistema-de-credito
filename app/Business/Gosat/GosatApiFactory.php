<?php

namespace App\Business\Gosat;

use App\Business\Gosat\ObjectConsultOfferCredit;
use App\Business\Gosat\CreditConsultation;
use App\Http\Resources\PersonResource;

class GosatApiFactory
{
    public static function creditConsultation(PersonResource $person): \App\Business\Gosat\GosatApi
    {
        return CreditConsultation::make(ObjectConsultOfferCredit::make($person));
    }
}
