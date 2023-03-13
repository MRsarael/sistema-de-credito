<?php

namespace App\Business\Gosat;

use App\Repositories\Log\LogApiRepositoryInterface;
use Illuminate\Support\Collection;

abstract class GosatApi
{
    protected LogApiRepositoryInterface $logApiRepository;
    protected string $baseUrlApi = 'https://dev.gosat.org/api/v1';

    abstract public function consult(): Collection;

    protected function log(string $body, string $response, string $code, string $client, string $method, string $url): void
    {
        $this->logApiRepository->new([
            'body' => $body, 'response' => $response, 'code' => $code, 'client' => $client, 'method' => $method, 'url' => $url
        ]);
    }
}
