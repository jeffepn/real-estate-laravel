<?php

namespace Jeffpereira\RealEstate\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ["bail", "sometimes", "required", "min:3", "max:150", Rule::unique('projects')->ignore($this->project)],
            "content" => ["bail", "nullable"],
            "person_id" => "bail|sometimes|required|uuid|exists:people,id",
        ];
    }

    public function messages()
    {
        return [
            'min' => 'O campo deve ter no mínimo :min caracteres.',
            'max' => 'Limite o campo a no máximo :max caracteres.',
            "name.required" => 'O projeto precisa de um nome.',
            "name.unique" => 'Já existe um projeto com este nome.',
            "person_id.exists" => 'Forneça um id de responsável válido.',
            "person_id.uuid" => 'Forneça um id de responsável válido.',
            "person_id.required" => 'Forneça um id de responsável válido.',
        ];
    }
}
