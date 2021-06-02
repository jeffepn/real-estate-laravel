<?php

namespace Jeffpereira\RealEstate\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubTypeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  $this->method() == 'PUT' || $this->method() == 'PATCH'
            ? [
                "type_id" => "sometimes|required|uuid",
                "name" => ['sometimes', 'required', 'max:30', Rule::unique('sub_types')->ignore($this->sub_type)],
            ] : [
                "type_id" => "required|uuid",
                "name" => ['required', 'max:30', Rule::unique('sub_types')->ignore($this->sub_type)],
            ];
    }
    public function messages()
    {
        return [
            'max' => 'Limite o campo a no máximo :max caracteres.',
            'name.required' => "É necessário fornecer um nome para o subtipo.",
            'name.unique' => "Já existe um subtipo com esse nome.",
        ];
    }

    public function prepareForValidation()
    {
        if ($this->name) {
            $this->merge(['name' => Str::upper($this->name)]);
        }
    }
}
