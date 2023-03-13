<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Utils\Util;

use App\Services\GosatServiceInterface;
use App\Services\GosatService;

class GosatCotroller extends Controller
{
    private Request $request;
    private GosatServiceInterface $service;

    public function __construct(Request $request, GosatService $gosatService)
    {
        $this->request = $request;
        $this->service = $gosatService;
    }

    /**
     * Bank credit offer consultation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function creditOffer($idPerson)
    {
        $response = ['response' => [], 'error' => false, 'message' => 'Success'];
        $code = 200;

        try {
            $idPerson = (int) Crypt::decryptString($idPerson);
            $response['response'] = $this->service->creditConsultation($idPerson)->toArray(false);
        } catch (\Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            $code = Util::getStatusCode($e->getCode());
        }

        return response()->json($response, $code);
    }
}
