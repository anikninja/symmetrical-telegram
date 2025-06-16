<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming any authenticated user can update a note they own.
        // More specific authorization (e.g., checking if the user owns the note)
        // should ideally be handled by a policy or in the controller before update.
        // For now, just checking if the user is authenticated.
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
            'title' => 'sometimes|required|string|max:255',
            'time_duration' => 'sometimes|required|date_format:H:i:s',
            'transcription' => 'nullable|string',
            'summary' => 'nullable|string',
            'language_id' => 'sometimes|nullable|integer|exists:languages,id',
            'type_id' => 'sometimes|required|integer|exists:note_types,id',
            'library_id' => 'sometimes|required|integer|exists:libraries,id',
            'folder_id' => 'sometimes|nullable|integer|exists:folders,id',
            'audio_file' => 'sometimes|nullable|file|mimes:mp3,wav,aac,m4a|max:10240', // Max 10MB, added more common audio mimes
        ];
    }
}
