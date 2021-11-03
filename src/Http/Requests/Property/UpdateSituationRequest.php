<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSituationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:30', Rule::unique('situations')->ignore($this->situation)]
        ];
    }

    public function messages()
    {
        return [
            'required' => "Escolha um nome para a situação do imóvel. Ex: Em construção.",
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'unique' => "Já existe uma situação com esse nome.",
        ];
    }
}
