<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class ImagePropertyUpdateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orders.*.id' => 'required|uuid',
            'orders.*.order' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'orders.*.id.required' => 'Forneça o id da imagem.',
            'orders.*.id.uuid' => 'Forneça o id da imagem.',
            'orders.*.order.required' => 'Forneça uma ordem para a imagem.',
            'orders.*.order.integer' => 'Forneça um número inteiro.',
        ];
    }
}
