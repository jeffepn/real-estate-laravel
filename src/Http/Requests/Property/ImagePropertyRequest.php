<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class ImagePropertyRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "image" => "required|mimes:jpeg,png|max:10240",
            "property_id" => "required|uuid",
        ];
    }
    public function messages()
    {
        return [
            'image.required' => 'Forneça uma imagem.',
            'max' => 'Escolha uma imagem com menos de :max kB.',
            'mimes' => "Formatos de imagens aceitos: jpg, jpeg e png.",
            'property_id.required' => "Forneça um id de um imóvel.",
            'property_id.uuid' => "Forneça um id de um imóvel.",
        ];
    }
}
