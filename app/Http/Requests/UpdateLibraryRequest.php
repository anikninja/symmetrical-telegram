<?php

namespace App\Http\Requests;

use App\Models\Library;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateLibraryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Library $library */
        $library = $this->route('library');
        return Auth::check() && Auth::id() === $library->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @var Library $library */
        $library = $this->route('library');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('libraries')->where(function ($query) use ($library) {
                    return $query->where('user_id', $library->user_id)->whereNull('deleted_at');;
                })->ignore($library->id),
            ],
        ];
    }
}
