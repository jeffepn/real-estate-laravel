<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TypeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ['required', 'max:30', Rule::unique('types')->ignore($this->type)],
        ];
    }
    public function messages()
    {
        return [
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'name.required' => "É necessário fornecer um nome para o tipo.",
            'name.unique' => "Já existe um tipo com esse nome.",
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['name' => Str::upper($this->name)]);
    }
}
