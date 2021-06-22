<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PropertyRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $edit = $this->method() == 'PUT' || $this->method() == 'PATCH';
        $sometimes = $edit ? 'sometimes|' : '';
        $rulesItems = "{$sometimes}bail|nullable|integer|min:0";
        $rules = [
            "slug" => ["sometimes", "bail", "slug", "min:3", "max:150", Rule::unique('properties')->ignore($this->property)],
            "businesses" => "sometimes|required",
            "businesses.*.id" => "{$sometimes}bail|required|uuid",
            "businesses.*.value" => "{$sometimes}bail|nullable|numeric|between:0,99999999.99",
            "sub_type_id" => "{$sometimes}bail|required|uuid",
            "min_description" => "{$sometimes}bail|required|min:10|max:200",
            "total_area" => "{$sometimes}bail|nullable|numeric|between:1,99999999.99",
            "building_area" => "{$sometimes}bail|nullable|numeric|between:1,99999999.99",
            "min_dormitory" => $rulesItems,
            "max_dormitory" => $rulesItems,
            "min_suite" => $rulesItems,
            "max_suite" => $rulesItems,
            "min_bathroom" => $rulesItems,
            "max_bathroom" => $rulesItems,
            "min_garage" => $rulesItems,
            "max_garage" => $rulesItems,
        ];
        if (!$edit) {
            $rules = array_merge($rules, [
                "address" => "sometimes|bail|nullable|max:100",
                "number" => "sometimes|nullable|integer|min:1",
                "complement" => "max:15",
                "cep" => "sometimes|bail|nullable|formato_cep",
                "latitude" => "sometimes|nullable|integer",
                "longitude" => "sometimes|nullable|integer",
                "neighborhood" => "{$sometimes}bail|required|min:2|max:100",
                "city" => "{$sometimes}bail|required|min:2|max:100",
                "state" => "{$sometimes}bail|min:2|max:100",
                "initials" => "{$sometimes}bail|required|min:2|max:2",
            ]);
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'min' => 'O campo deve ter no mínimo :min caracteres.',
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'numeric' => 'Formato de número inválido.',
            'integer' => 'Forneça um número inteiro.',
            'between' => 'Faixa de valores disponíveis: 1 - 99999999.99.',
            'slug' => 'Formato de slug inválido. Tente algo parecido com formato-de-slug-correto-99.',
            'businesses.*.id.required' => 'Escolha um tipo de negócio.',
            'business_id.uuid' => 'Escolha um tipo de negócio.',
            'address_id.required' => 'Cadastre um endereço.',
            'address_id.uuid' => 'Cadastre um endereço.',
            'sub_type_id.required' => 'Escolha um tipo de imóvel.',
            'sub_type_id.uuid' => 'Escolha um tipo de imóvel.',
            'slug.unique' => 'Já existe um imóvel com este slug.',
            'min_dormitory.min' => 'Forneça um número inteiro.',
            'min_suite.min' => 'Forneça um número inteiro.',
            'min_bathroom.min' => 'Forneça um número inteiro.',
            'min_garage.min' => 'Forneça um número inteiro.',
            'number.min' => 'Forneça um número maior do que 0.',
            'businesses.*.id' => [
                'required' => 'Escolha um tipo de negócio',
                'uuid' => 'Escolha um tipo de negócio',
            ],
            'businesses.*.value' => [
                'between' => 'Faixa de valores disponíveis: 0 - 99999999.99.',
                'uuid' => 'Escolha um tipo de negócio',
            ],
            // Address
            "neighborhood.required" => "Forneça um bairro.",
            "city.required" => "Forneça uma cidade.",
            "initials.required" => "Forneça um estado.",
        ];
    }
    public function getData()
    {
        return $this->except([
            "businesses",
            "address",
            "number",
            "complement",
            "cep",
            "latitude",
            "longitude",
            "neighborhood",
            "city",
            "state",
            "initials",
            "country",
        ]);
    }

    public function getDataAddress()
    {
        return $this->only([
            "address",
            "number",
            "complement",
            "cep",
            "latitude",
            "longitude",
            "neighborhood",
            "city",
            "state",
            "initials",
            "country",
        ]);
    }

    public function prepareForValidation()
    {
        $this->merge([
            'neighborhood' => Str::upper($this->neighborhood),
            'city' => Str::upper($this->city),
            'state' => $this->state ? Str::upper($this->state) : $this->getStateByInitials($this->initials),
            'initials' => Str::upper($this->initials),
            'country' => $this->country ? Str::upper($this->country) : "BRASIL",
        ]);
    }

    private function getStateByInitials($initials)
    {
        $initials = Str::upper($initials);
        $states = [
            ["AC" => "Acre"],
            ["AL" => "Alagoas"],
            ["AP" => "Amapá"],
            ["AM" => "Amazonas"],
            ["BA" => "Bahia"],
            ["CE" => "Ceará"],
            ["DF" => "Distrito Federal"],
            ["ES" => "Espírito Santo"],
            ["GO" => "Goiás"],
            ["MA" => "Maranhão"],
            ["MT" => "Mato Grosso"],
            ["MS" => "Mato Grosso do Sul"],
            ["MG" => "Minas Gerais"],
            ["PA" => "Pará"],
            ["PB" => "Paraíba"],
            ["PR" => "Paraná"],
            ["PE" => "Pernambuco"],
            ["PI" => "Piauí"],
            ["RJ" => "Rio de Janeiro"],
            ["RN" => "Rio Grande do Norte"],
            ["RS" => "Rio Grande do Sul"],
            ["RO" => "Rondônia"],
            ["RR" => "Roraima"],
            ["SC" => "Santa Catarina"],
            ["SP" => "São Paulo"],
            ["SE" => "Sergipe"],
            ["TO" => "Tocantins"],
        ];
        return isset($states[$initials]) ? Str::upper($states[$initials]) : "UNDEFINED";
    }
}
