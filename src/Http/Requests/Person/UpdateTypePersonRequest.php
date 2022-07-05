<?php

namespace Jeffpereira\RealEstate\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTypePersonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'bail',
                'sometimes',
                'required',
                'min:3',
                'max:30',
                Rule::unique('type_people')->ignore($this->type_person),
            ],
        ];
    }

    public function messages()
    {
        return [
            'min' => 'O campo deve ter no mínimo :min caracteres.',
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'name.required' => 'O tipo pessoa precisa de um nome.',
            'name.unique' => 'Este tipo de pessoa já existe.',
        ];
    }
}
