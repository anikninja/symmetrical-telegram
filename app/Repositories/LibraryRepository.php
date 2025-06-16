<?php

namespace App\Repositories;

use App\Interfaces\LibraryRepositoryInterface;
use App\Models\Library;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class LibraryRepository implements LibraryRepositoryInterface
{
    public function getAllLibrariesByUser(User $user): Collection
    {
        return $user->libraries()->latest()->get();
    }

    public function createLibrary(User $user, array $data): Library
    {
        return $user->libraries()->create($data);
    }

    public function getLibraryById(Library $library): Library
    {
        return $library->load('notes', 'folders', 'bookmarks');
    }

    public function updateLibrary(Library $library, array $data): bool
    {
        return $library->update($data);
    }

    public function deleteLibrary(Library $library): bool
    {
        return $library->delete();
    }
}
