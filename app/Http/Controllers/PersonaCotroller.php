<?php

namespace App\Http\Controllers;

use App\Services\PersonaServiceInterface;
use App\Services\PersonaService;

use App\FormRequests\PersonaStoreFormRequest;
use App\FormRequests\PersonaUpdateFormRequest;
use App\FormRequests\PersonSimulationFormRequest;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Utils\Util;

class PersonaCotroller extends Controller
{
    private Request $request;
    private PersonaServiceInterface $service;

    public function __construct(Request $request, PersonaService $service)
    {
        $this->request = $request;
        $this->service = $service;
    }

    /**
     * List all person's
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            $response['response'] = $this->service->persons();
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }

    /**
     * Store a new person
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 201;

        try {
            $validator = Validator::make($this->request->all(), (new PersonaStoreFormRequest)->rules());

            if ($validator->fails()) {
                throw new \Exception(Util::convertMessageErrorFormRequest($validator->errors()->messages()));
            }

            $response['response'] = $this->service->newPerson($this->request->all())->toArray(false);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }

    /**
     * Show a person
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($idPerson)
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            $idPerson = (int) Crypt::decryptString($idPerson);
            $response['response'] = $this->service->showPerson($idPerson)->toArray(false);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }

    /**
     * Update data person
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            $validator = Validator::make($this->request->all(), (new PersonaUpdateFormRequest)->rules());

            if ($validator->fails()) {
                throw new \Exception(Util::convertMessageErrorFormRequest($validator->errors()->messages()));
            }

            $response['response'] = $this->service->updatePerson($this->request->all())->toArray(false);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }

    /**
     * Delete person
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($idPerson)
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            $idPerson = (int) Crypt::decryptString($idPerson);
            $response['response'] = $this->service->deletePerson($idPerson);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }

    /**
     * Busca das ofertas disponíveis
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function offerConsult($idPerson = null)
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            if($idPerson !== null)
                $idPerson = (int) Crypt::decryptString($idPerson);
            
            $response['response'] = $this->service->creditOfferConsult($idPerson)->toArray(false);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }

    public function simulationOffer()
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            $validator = Validator::make($this->request->all(), (new PersonSimulationFormRequest)->rules());

            if ($validator->fails()) {
                throw new \Exception(Util::convertMessageErrorFormRequest($validator->errors()->messages()));
            }
            
            $idPerson = (int) Crypt::decryptString($this->request->get('idPerson'));
            $idInstitution = (int) Crypt::decryptString($this->request->get('idInstitution'));
            $codModality = $this->request->get('codModality');
            $response['response'] = $this->service->simulationOffer($idPerson, $idInstitution, $codModality)->toArray(false);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }
}
