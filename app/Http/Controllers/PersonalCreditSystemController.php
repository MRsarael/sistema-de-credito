<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\GosatServiceInterface;
use App\Services\GosatService;
use App\Services\PersonaServiceInterface;
use App\Services\PersonaService;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\Utils\Util;

class PersonalCreditSystemController extends Controller
{
    private Request $request;
    private GosatServiceInterface $gosatService;
    private PersonaServiceInterface $personaService;

    public function __construct(Request $request, GosatService $gosatService, PersonaService $personaService)
    {
        $this->request = $request;
        $this->gosatService = $gosatService;
        $this->personaService = $personaService;
    }

    /**
     * Lista os dados de todas as pessoas cadastradas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $personsData = array();
        $view = 'home';

        try {
            $personsData['data'] = $this->personaService->persons();
        } catch (\Exception $e) {
            $personsData = ['code' => $e->getCode(), 'message' => $e->getMessage()];
            $view = 'message_status';
        }

        return view($view, $personsData);
    }
}
