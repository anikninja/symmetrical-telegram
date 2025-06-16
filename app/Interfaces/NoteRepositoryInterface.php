<?php

namespace App\Interfaces;

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;

interface NoteRepositoryInterface
{
    public function getAllNotes(): Collection;
    public function getNoteById(int $id): ?Note;
    public function createNote(array $data): Note;
    public function updateNote(Note $note, array $data): Note;
    public function deleteNote(Note $note): bool;
    public function searchNotes(string $query): Collection;
}
