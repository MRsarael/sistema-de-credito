<?php

namespace App\Business\Gosat;

use App\Business\Gosat\ObjectConsultSimulationOffer;
use App\Business\Gosat\ObjectConsultOfferCredit;
use App\Business\Gosat\CreditConsultation;
use App\Business\Gosat\Simulation;
use App\Http\Resources\PersonResource;

class GosatApiFactory
{
    public static function creditConsultation(PersonResource $person): \App\Business\Gosat\GosatApi
    {
        return CreditConsultation::make(ObjectConsultOfferCredit::make($person));
    }

    public static function simulationOffer(int $idPerson, int $idInstitution, string $codModality): \App\Business\Gosat\GosatApi
    {
        return Simulation::make(ObjectConsultSimulationOffer::make($idPerson, $idInstitution, $codModality));
    }
}
