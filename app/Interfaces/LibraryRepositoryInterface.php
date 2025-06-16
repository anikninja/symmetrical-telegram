<?php

namespace App\Interfaces;

use App\Models\Library;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface LibraryRepositoryInterface
{
    public function getAllLibrariesByUser(User $user): Collection;
    public function createLibrary(User $user, array $data): Library;
    public function getLibraryById(Library $library): Library;
    public function updateLibrary(Library $library, array $data): bool;
    public function deleteLibrary(Library $library): bool;
}
