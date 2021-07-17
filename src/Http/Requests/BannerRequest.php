<?php

namespace Jeffpereira\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $edit = $this->method() == 'PUT' || $this->method() == 'PATCH';
        $rules = $edit && $this->has('_method') ? 'sometimes|nullable|' : 'required|';
        return [
            "image" => "bail|{$rules}image|mimes:jpeg,png|max:300",
            'title' => 'max:150',
            'content' => 'max:250',
            'link' => 'nullable|url|max:250',
        ];
    }

    public function messages()
    {
        return [
            'max' => "Limite o campo a no máximo :max caracteres.",
            'url' => "Url inválida.",
            'image' => "A imagem não é válida.",
            'image.required' => 'Forneça uma imagem.',
            'image.max' => 'Escolha uma imagem com menos de :max kB.',
            'mimes' => "Formatos de imagens aceitos: jpg, jpeg e png.",
        ];
    }
}
