<?php

namespace Jeffpereira\RealEstate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jeffpereira\RealEstate\Enum\AppSettingsEnum;

class AppSettingsRequest extends FormRequest
{

    const PREFIX_METHOD_RULES = 'rules';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameMethod = self::PREFIX_METHOD_RULES . Str::studly($this->name ?? 'name_method_not_found');
        return method_exists($this, $nameMethod)
            ? $this->$nameMethod()
            : $this->rulesDefault();
    }

    public function messages()
    {
        return [
            'max' => "Limite o campo a no máximo :max caracteres.",
            'name.required' => 'Forneça o nome da configuração.',
            'name.in' => 'O nome da configuração dever ser uma das seguintes: [' . implode(', ', AppSettingsEnum::getAll()) . ']    ',
            'image_watter' => "A imagem não é válida.",
            'image_watter.required' => 'Forneça uma imagem.',
            'image_watter.max' => 'Escolha uma imagem com menos de :max kB.',
            'image_watter.mimes' => "Formatos de imagens aceitos: jpg, jpeg e png.",
            'value.required' => 'É necessário fornecer um valor para a configuração.'
        ];
    }

    private function rulesDefault(): array
    {
        return [
            'name' => ['bail', 'required', Rule::unique('app_settings')->ignore($this->app_setting), 'max:255', Rule::in(AppSettingsEnum::getAll())],
            'value' => 'required'
        ];
    }

    private function rulesWattermarkImageProperty(): array
    {
        return [
            'name' => ['required', Rule::unique('app_settings')->ignore($this->app_setting), 'max:255', Rule::in(AppSettingsEnum::getAll())],
            'image_watter' => 'required|image|mimes:jpeg,png|max:300'
        ];
    }
}
