<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class BusinessRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ['required', 'max:30', Rule::unique('businesses')->ignore($this->business)],
        ];
    }
    public function messages()
    {
        return [
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'name.required' => "É necessário fornecer um nome para o negócio.",
            'name.unique' => "Já existe um negócio com esse nome.",
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['name' => Str::upper($this->name)]);
    }
}
