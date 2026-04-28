<?php

namespace App\Concerns;

trait ImageValidationRules
{
    /**
     * Get the validation rules for the image field.
     *
     * @return array<string>
     */
    public function imageRules(): array
    {
        return ['required', 'image', 'max:2048'];
    }
}
