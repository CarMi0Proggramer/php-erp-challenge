<?php

namespace App\Http\Requests\Users;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Concerns\RoleValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    use PasswordValidationRules, ProfileValidationRules, RoleValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            ...$this->profileRules($userId),
            'role' => $this->roleRules(),
            'password' => $this->passwordRules(required: false),
        ];
    }

    public function validated($key = null, $default = null)
    {
        $input = parent::validated($key, $default);

        if (empty($input['password'])) {
            unset($input['password']);
        }

        return $input;
    }
}
