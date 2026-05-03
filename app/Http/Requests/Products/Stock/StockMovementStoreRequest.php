<?php

namespace App\Http\Requests\Products\Stock;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StockMovementStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'balance' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string'],
        ];
    }
}
