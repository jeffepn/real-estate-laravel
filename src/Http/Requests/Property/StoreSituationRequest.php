<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSituationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:30', Rule::unique('situations')]
        ];
    }

    public function messages()
    {
        return [
            'required' => "Escolha um nome para a situação do imóvel. Ex: Em construção."
        ];
    }
}
