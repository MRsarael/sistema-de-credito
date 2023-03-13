<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\Simulation\SimulationRepositoryInterface::class, \App\Repositories\Simulation\SimulationRepository::class);
        $this->app->bind(\App\Repositories\PersonalCredit\PersonalCreditOfferRepositoryInterface::class, \App\Repositories\PersonalCredit\PersonalCreditOfferRepository::class);
        $this->app->bind(\App\Repositories\Person\PersonRepositoryInterface::class, \App\Repositories\Person\PersonRepository::class);
        $this->app->bind(\App\Repositories\FinancialInstitution\FinancialInstitutionRepositoryInterface::class, \App\Repositories\FinancialInstitution\FinancialInstitutionRepository::class);
        $this->app->bind(\App\Repositories\CreditOfferModality\CreditOfferModalityRepositoryInterface::class, \App\Repositories\CreditOfferModality\CreditOfferModalityRepository::class);
        $this->app->bind(\App\Repositories\Log\LogApiRepositoryInterface::class, \App\Repositories\Log\LogApiRepository::class);
    }
}
