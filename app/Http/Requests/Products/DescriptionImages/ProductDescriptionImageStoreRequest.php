<?php

namespace App\Http\Requests\Products\DescriptionImages;

use App\Concerns\ImageValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductDescriptionImageStoreRequest extends FormRequest
{
    use ImageValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => $this->imageRules(),
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Por favor, selecione uma imagem para enviar.',
            'image.image' => 'O arquivo selecionado não é uma imagem válida.',
            'image.max' => 'A imagem deve ter no máximo 2MB.',
        ];
    }
}
