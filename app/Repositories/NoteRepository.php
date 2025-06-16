<?php

namespace App\Repositories;

use App\Models\Note;
use App\Interfaces\NoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class NoteRepository implements NoteRepositoryInterface
{
    protected $relations = ['type', 'library', 'user', 'language', 'folder', 'media'];

    public function getAllNotes(): Collection
    {
        return Note::with($this->relations)->where('user_id', Auth::id())->get();
    }

    public function getNoteById(int $id): ?Note
    {
        return Note::with($this->relations)->where('user_id', Auth::id())->find($id);
    }

    public function createNote(array $data): Note
    {
        $data['user_id'] = Auth::id();
        $note = Note::create($data);
        return $note->load($this->relations);
    }

    public function updateNote(Note $note, array $data): Note
    {
        $data['user_id'] = Auth::id();
        $note->update($data);

        return $note->load($this->relations);
    }

    public function deleteNote(Note $note): bool
    {
        return $note->delete();
    }

    public function searchNotes(string $query): Collection
    {
        return Note::with($this->relations)
            ->where('user_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->get();
    }
}
