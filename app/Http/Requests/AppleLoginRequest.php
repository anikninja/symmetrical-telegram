<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppleLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'auth_id'      => 'required|string',
            'name'         => 'nullable|string|max:255',
            'email'        => 'sometimes|nullable|email',
            'device_token' => 'required|string',
        ];
    }
}
