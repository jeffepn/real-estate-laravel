<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class ImagePropertyRequest extends FormRequest
{

    public function rules()
    {
        return [
            "alt" => "nullable|max:255",
            "image" => "required|mimes:jpeg,png|max:10240",
            "property_id" => "required|uuid|exists:properties,id",
        ];
    }
    public function messages()
    {
        return [
            'image.required' => 'Forneça uma imagem.',
            'alt.max' => 'O alt da imagens deve ter menos de :max caracteres.',
            'image.max' => 'Escolha uma imagem com menos de :max kB.',
            'mimes' => "Formatos de imagens aceitos: jpg, jpeg e png.",
            'property_id.required' => "Forneça um id de um imóvel.",
            'property_id.uuid' => "Forneça um id de um imóvel válido.",
            'property_id.exists' => "Forneça um id de um imóvel válido.",
        ];
    }
}
