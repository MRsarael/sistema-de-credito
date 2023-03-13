<?php

namespace App\Business\Gosat;

interface ObjectConsultSimulationOfferInterface
{
    public function toString(): string;
    public function getBody(): array;
    public function getPerson(): object;
    public function getPersonalCreditOffer(): object;
    public function getCreditOfferModality(): object;
    public function getFinancialInstitution(): object;
}
