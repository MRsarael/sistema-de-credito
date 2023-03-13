<?php

namespace App\Repositories\Log;

use App\Models\LogApi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class LogApiRepository implements LogApiRepositoryInterface
{
    private LogApi $model;
    
    public function __construct(LogApi $person)
    {
        $this->model = $person;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function formatModel(Model $log): JsonResource
    {
        return new \App\Http\Resources\PersonResource([
            'id'         => Crypt::encryptString($log->id),
            'body'       => $log->body,
            'response'   => $log->response,
            'code'       => $log->code,
            'client'     => $log->client,
            'created_at' => $log->created_at->format('Y-m-d')
        ]);
    }

    public function new(array $data): JsonResource
    {
        $log = $this->model->create([
            'body'     => $data['body'],
            'response' => $data['response'],
            'code'     => $data['code'],
            'client'   => $data['client']
        ]);
        
        return new JsonResource($this->formatModel($log));
    }
}
