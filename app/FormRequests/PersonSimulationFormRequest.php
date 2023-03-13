<?php

namespace App\FormRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailValidation;

class PersonSimulationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idPerson'      => ['required', 'string'],
            'codModality'   => ['required', 'string'],
            'idInstitution' => ['required', 'string']
        ];
    }
}
