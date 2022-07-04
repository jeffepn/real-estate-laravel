<?php

namespace Jeffpereira\RealEstate\Http\Requests\Person;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|sometimes|required|min:3|max:255',
            'bio' => 'bail|sometimes|nullable|max:500',
            'type_person_id' => 'bail|sometimes|required|uuid|exists:type_people,id',
        ];
    }

    public function messages()
    {
        return [
            'min' => 'O campo deve ter no mínimo :min caracteres.',
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'name.required' => 'A pessoa precisa de um nome.',
            'type_person_id.exists' => 'Forneça um id de tipo de pessoa válido.',
            'type_person_id.uuid' => 'Forneça um id de tipo de pessoa válido.',
            'type_person_id.required' => 'Forneça um id de tipo de pessoa válido.',
        ];
    }
}
