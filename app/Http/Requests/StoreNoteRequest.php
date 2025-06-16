<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNoteRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'time_duration' => 'required|date_format:H:i:s',
            'transcription' => 'nullable|string',
            'summary' => 'nullable|string',
            'language_id' => 'nullable|integer|exists:languages,id',
            'type_id' => 'required|integer|exists:note_types,id',
            'library_id' => 'required|integer|exists:libraries,id',
            'folder_id' => 'nullable|integer|exists:folders,id',
            'audio_file' => 'nullable|file|mimes:mp3,wav,aac,m4a|max:10240',
        ];
    }
}
