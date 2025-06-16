<?php

namespace App\Interfaces;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface FolderRepositoryInterface
{
    public function getAllUserFolders(Request $request): Collection;
    public function createFolder(array $data, int $userId): Folder;
    public function getFolderById(Folder $folder): Folder;
    public function updateFolder(Folder $folder, array $data, int $userId): Folder;
    public function deleteFolder(Folder $folder): bool;
}
