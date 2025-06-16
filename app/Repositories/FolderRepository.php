<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Folder;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\FolderRepositoryInterface;

class FolderRepository implements FolderRepositoryInterface
{
    public function getAllUserFolders(Request $request): Collection
    {
        return $request->user()->folders()->latest()->get();
    }

    public function createFolder(array $data, int $userId): Folder
    {
        $user = User::find($userId);
        if (!$user) {
            // Or throw an exception, depending on how you want to handle a missing user
            throw new \Exception("User not found");
        }
        $status = $data['status'] ?? null;

        if ($status === StatusEnum::ACTIVE->value) {
            $user->folders()->where('status', StatusEnum::ACTIVE->value)->update(['status' => StatusEnum::DEACTIVE->value]);
        }

        $folder = new Folder($data);
        $folder->user_id = $userId;
        $folder->save();
        return $folder;
    }

    public function getFolderById(Folder $folder): Folder
    {
        return $folder;
    }

    public function updateFolder(Folder $folder, array $data, int $userId): Folder
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception("User not found");
        }
        $newStatus = $data['status'] ?? null;

        if ($newStatus === StatusEnum::ACTIVE->value && $folder->status !== StatusEnum::ACTIVE->value) {
            $user->folders()
                ->where('id', '!=', $folder->id)
                ->where('status', StatusEnum::ACTIVE->value)
                ->update(['status' => StatusEnum::DEACTIVE->value]);
        }

        $folder->update($data);
        return $folder;
    }

    public function deleteFolder(Folder $folder): bool
    {
        return $folder->delete();
    }
}
