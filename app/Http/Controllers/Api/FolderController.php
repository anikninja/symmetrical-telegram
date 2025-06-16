<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Interfaces\FolderRepositoryInterface;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Helpers\ApiResponse;

class FolderController extends Controller
{
    protected FolderRepositoryInterface $folderRepository;

    public function __construct(FolderRepositoryInterface $folderRepository)
    {
        $this->folderRepository = $folderRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $folders = $this->folderRepository->getAllUserFolders($request);

        return ApiResponse::response(true, 'Folders retrieved successfully.', $folders);
    }

    public function store(StoreFolderRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $folder = $this->folderRepository->createFolder($validatedData, $request->user()->id);

        return ApiResponse::response(true, 'Folder created successfully.', $folder, null, 201);
    }


    public function show(Folder $folder): JsonResponse
    {
        if (Auth::id() !== $folder->user_id) {
            return ApiResponse::response(false, 'You are not authorized to view this folder.', null, 'Authorization Failed', 403);
        }

        $retrievedFolder = $this->folderRepository->getFolderById($folder);

        return ApiResponse::response(true, 'Folder retrieved successfully.', $retrievedFolder);
    }

    public function update(UpdateFolderRequest $request, Folder $folder): JsonResponse
    {
        $validatedData = $request->validated();
        $updatedFolder = $this->folderRepository->updateFolder($folder, $validatedData, $request->user()->id);

        return ApiResponse::response(true, 'Folder updated successfully.', $updatedFolder);
    }

    public function destroy(Request $request, Folder $folder): JsonResponse
    {
        if (Auth::id() !== $folder->user_id) {
            return ApiResponse::response(false, 'You are not authorized to delete this folder.', null, 'Authorization Failed', 403);
        }

        $this->folderRepository->deleteFolder($folder);

        return ApiResponse::response(true, 'Folder moved to trash successfully.', null, null, 200);
    }
}
