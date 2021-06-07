<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            return [
                "business_id" => ['sometimes', 'required', 'uuid'],
            ];
        }
        return [
            "business_id" => ['required', 'uuid'],
        ];
    }

    public function messages()
    {
        return [
            'business_id.required' => 'Escolha um tipo de negócio.',
            'business_id.uuid' => 'Escolha um tipo de negócio.',
        ];
    }
}
