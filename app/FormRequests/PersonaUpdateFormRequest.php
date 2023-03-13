<?php

namespace App\FormRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailValidation;

class PersonaUpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'    => ['required', 'string'],
            'name'  => ['required', 'string'],
            'cpf'   => ['required', 'string', 'max:11', 'min:11'],
            'age'   => ['required', 'integer'],
            'email' => [new EmailValidation()],
        ];
    }
}
