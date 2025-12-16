<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name'    => 'required|string|max:50',
            'last_name'     => 'required|string|max:50',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'sometimes|string|max:20',
            'password'      => 'required|confirmed',
            'role'          => 'required|exists:roles,name',
            'business_name' => 'required_if:role,business_admin|string|max:255|unique:businesses,business_name',
        ];
    }

    protected function prepareForValidation()
    {
        $this->replace($this->snakeCaseKeys($this->all()));
    }

    private function snakeCaseKeys(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = Str::snake($key);
            if (is_array($value)) {
                $value = $this->snakeCaseKeys($value);
            }
            $result[$newKey] = $value;
        }
        return $result;
    }

    protected function passedValidation()
    {
        if ($this->has('password')) {
            $this->merge(['password' => bcrypt($this->password)]);
        }
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        return $this->snakeCaseKeys($validated);
    }
}
