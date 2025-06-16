<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreBookmarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $user = Auth::user();

        return [
            'note_id' => [
                'required',
                'integer',
                'exists:notes,id',
                Rule::unique('bookmarks')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id)
                                 ->whereNull('deleted_at');
                }),
            ],
        ];
    }
}
