<?php

namespace Jeffpereira\RealEstate\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageProjectRequest extends FormRequest
{
    public const MIMES = 'jpeg,jpg,png';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'bail|required|uuid|exists:projects,id',
            'alt' => 'nullable|max:255',
            'images' => 'required|array',
            'images.*.alt' => 'nullable|max:255',
            'images.*.image' => 'required|image|mimes:' . self::MIMES . '|max:10240',
            'images.*.title' => 'nullable|max:255',
            'images.*.description' => 'nullable|max:255',
            'images.*.author' => 'nullable|max:255',
            'images.*' => 'array',
        ];
    }

    public function messages()
    {
        return [
            'min' => 'O campo deve ter no mínimo :min caracteres.',
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'name.required' => 'O projeto precisa de um nome.',
            'images.required' => 'Forneça pelo menos uma imagem.',
            'images.*.image' => 'Imagem inválida.',
            'images.*.max' => 'Forneça um arquivo de no máximo 1MB.',
            'images.*.mimes' => 'Forneça uma imagem nos seguintes formatos: ' . self::MIMES . '.',
            'project_id.exists' => 'Forneça um id de projeto válido.',
            'project_id.uuid' => 'Forneça um id de projeto válido.',
            'project_id.required' => 'Forneça um id de projeto válido.',
        ];
    }
}
