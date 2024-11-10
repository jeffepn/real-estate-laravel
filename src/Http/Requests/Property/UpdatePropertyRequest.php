<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jeffpereira\RealEstate\Enum\BusinessPropertySituationEnum;
use Jeffpereira\RealEstate\Models\Property\Property;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => ['sometimes', 'bail', 'slug', 'min:3', 'max:150', Rule::unique('properties')->ignore($this->property)],
            'code' => ['sometimes', 'bail', 'integer', Rule::unique('properties')->ignore($this->property)],
            'businesses.*.id' => 'sometimes|bail|required|uuid',
            'businesses.*.value' => 'sometimes|bail|nullable|numeric|between:0,99999999.99',
            'businesses.*.old_value' => 'sometimes|bail|nullable|numeric|between:0,99999999.99',
            'businesses.*.status_situation' => ['sometimes', Rule::in(BusinessPropertySituationEnum::all())],
            'situation_id' => 'sometimes|bail|nullable|uuid',
            'sub_type_id' => 'sometimes|bail|required|uuid',
            'min_description' => 'sometimes|bail|nullable|min:10|max:200',
            'total_area' => 'sometimes|bail|nullable|numeric|between:0,99999999.99',
            'building_area' => 'sometimes|bail|nullable|numeric|between:0,99999999.99',
            'min_dormitory' => 'sometimes|bail|nullable|integer|min:0|lte:max_dormitory',
            'max_dormitory' => 'sometimes|bail|nullable|integer|min:0',
            'min_suite' => 'sometimes|bail|nullable|integer|min:0|lte:max_suite',
            'max_suite' => 'sometimes|bail|nullable|integer|min:0',
            'min_bathroom' => 'sometimes|bail|nullable|integer|min:0|lte:max_bathroom',
            'max_bathroom' => 'sometimes|bail|nullable|integer|min:0',
            'min_garage' => 'sometimes|bail|nullable|integer|min:0|lte:max_garage',
            'max_garage' => 'sometimes|bail|nullable|integer|min:0',
            'min_restroom' => 'sometimes|bail|nullable|integer|min:0|lte:max_restroom',
            'max_restroom' => 'sometimes|bail|nullable|integer|min:0',
            'embed' => 'sometimes|bail|nullable|url|max:300',
            'has_plate' => 'sometimes|bail|boolean',
            'address' => 'sometimes|bail|nullable|max:100',
            'number' => 'sometimes|nullable|integer|min:o',
            'complement' => 'max:15',
            'cep' => 'sometimes|bail|nullable|formato_cep',
            'latitude' => 'sometimes|nullable|integer',
            'longitude' => 'sometimes|nullable|integer',
            'neighborhood' => 'bail|required|min:2|max:100',
            'city' => 'bail|required|min:2|max:100',
            'state' => 'bail|min:2|max:100',
            'initials' => 'bail|required|min:2|max:2',
        ];
    }

    public function messages()
    {
        return [
            'lte' => 'O valor mínimo deve ser menor ou igual ao máximo.',
            'min' => 'O campo deve ter no mínimo :min caracteres.',
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'numeric' => 'Formato de número inválido.',
            'integer' => 'Forneça um número inteiro.',
            'between' => 'Faixa de valores disponíveis: 0 - 99999999.99.',
            'slug' => 'Formato de slug inválido. Tente algo parecido com formato-de-slug-correto-99.',
            'url' => 'Formato de url inválido.',
            'code.integer' => 'Formato de código inválido.',
            'code.unique' => 'Este código já está sendo usado por outro imóvel.',
            'business_id.uuid' => 'Escolha um tipo de negócio.',
            'address_id.required' => 'Cadastre um endereço.',
            'address_id.uuid' => 'Cadastre um endereço.',
            'sub_type_id.required' => 'Escolha um tipo de imóvel.',
            'sub_type_id.uuid' => 'Escolha um tipo de imóvel.',
            'slug.unique' => 'Já existe um imóvel com este slug.',
            'min_dormitory.min' => 'Forneça um valor maior ou igual a 0.',
            'max_dormitory.min' => 'Forneça um valor maior ou igual a 0.',
            'min_suite.min' => 'Forneça um valor maior ou igual a 0.',
            'max_suite.min' => 'Forneça um valor maior ou igual a 0.',
            'min_bathroom.min' => 'Forneça um valor maior ou igual a 0.',
            'max_bathroom.min' => 'Forneça um valor maior ou igual a 0.',
            'min_garage.min' => 'Forneça um valor maior ou igual a 0.',
            'max_garage.min' => 'Forneça um valor maior ou igual a 0.',
            'min_restroom.min' => 'Forneça um valor maior ou igual a 0.',
            'max_restroom.min' => 'Forneça um valor maior ou igual a 0.',
            'number.min' => 'Forneça um número maior do que 0.',
            'businesses.*.id' => [
                'required' => 'Escolha um tipo de negócio',
                'uuid' => 'Escolha um tipo de negócio',
            ],
            'businesses.*.value' => [
                'between' => 'Faixa de valores disponíveis: 0 - 99999999.99.',
                'uuid' => 'Escolha um tipo de negócio',
            ],
            'businesses.*.old_value' => [
                'between' => 'Faixa de valores disponíveis: 0 - 99999999.99.',
                'uuid' => 'Escolha um tipo de negócio',
            ],
            // Address
            'neighborhood.required' => 'Forneça um bairro.',
            'city.required' => 'Forneça uma cidade.',
            'initials.required' => 'Forneça um estado.',
        ];
    }

    public function getData(): array
    {
        return $this->except(
            array_merge(
                ['businesses'],
                Property::$columnsAddress,
                Property::$columnsCreateUpdateNeighborhood
            )
        );
    }

    public function prepareForValidation()
    {
        $this->merge([
            'useful_area' => $this->useful_area ? $this->useful_area : null,
            'building_area' => $this->building_area ? $this->building_area : null,
            'total_area' => $this->total_area ? $this->total_area : null,
            'number' => $this->not_number ? null : $this->number,
            'has_plate' => $this->has_plate ? true : false,
            'neighborhood' => Str::upper($this->neighborhood),
            'city' => Str::upper($this->city),
            'state' => $this->state ? Str::upper($this->state) : $this->getStateByInitials($this->initials),
            'initials' => Str::upper($this->initials),
            'country' => $this->country ? Str::upper($this->country) : 'BRASIL',
        ]);
    }

    private function getStateByInitials($initials): string
    {
        $initials = Str::upper($initials);
        $states = [
            ['AC' => 'Acre'],
            ['AL' => 'Alagoas'],
            ['AP' => 'Amapá'],
            ['AM' => 'Amazonas'],
            ['BA' => 'Bahia'],
            ['CE' => 'Ceará'],
            ['DF' => 'Distrito Federal'],
            ['ES' => 'Espírito Santo'],
            ['GO' => 'Goiás'],
            ['MA' => 'Maranhão'],
            ['MT' => 'Mato Grosso'],
            ['MS' => 'Mato Grosso do Sul'],
            ['MG' => 'Minas Gerais'],
            ['PA' => 'Pará'],
            ['PB' => 'Paraíba'],
            ['PR' => 'Paraná'],
            ['PE' => 'Pernambuco'],
            ['PI' => 'Piauí'],
            ['RJ' => 'Rio de Janeiro'],
            ['RN' => 'Rio Grande do Norte'],
            ['RS' => 'Rio Grande do Sul'],
            ['RO' => 'Rondônia'],
            ['RR' => 'Roraima'],
            ['SC' => 'Santa Catarina'],
            ['SP' => 'São Paulo'],
            ['SE' => 'Sergipe'],
            ['TO' => 'Tocantins'],
        ];

        return isset($states[$initials]) ? Str::upper($states[$initials]) : 'UNDEFINED';
    }
}
