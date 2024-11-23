<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class ImagePropertyUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'image' => ['sometimes', 'required', 'mimes:jpeg,jpg,png', 'max:10240'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Forneça uma imagem.',
            'alt.max' => 'O alt da imagens deve ter menos de :max caracteres.',
            'image.max' => 'Escolha uma imagem com menos de :max kB.',
            'mimes' => 'Formatos de imagens aceitos: jpg, jpeg e png.',
        ];
    }
}
