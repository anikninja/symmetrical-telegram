<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLibraryRequest;
use App\Http\Requests\UpdateLibraryRequest;
use App\Interfaces\LibraryRepositoryInterface;
use App\Models\Library;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    protected LibraryRepositoryInterface $libraryRepository;

    public function __construct(LibraryRepositoryInterface $libraryRepository)
    {
        $this->libraryRepository = $libraryRepository;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $libraries = $this->libraryRepository->getAllLibrariesByUser($user);

        return ApiResponse::response(true, 'Libraries retrieved successfully.', $libraries);
    }

    public function store(StoreLibraryRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $library = $this->libraryRepository->createLibrary($user, $request->validated());

        return ApiResponse::response(true, 'Library created successfully.', $library, null, 201);
    }

    public function show(Library $library)
    {
        if (Auth::id() !== $library->user_id) {
            return ApiResponse::response(false, 'Unauthorized.', null, null, 403);
        }

        $libraryData = $this->libraryRepository->getLibraryById($library);

        return ApiResponse::response(true, 'Library retrieved successfully.', $libraryData);
    }

    public function update(UpdateLibraryRequest $request, Library $library)
    {
        $this->libraryRepository->updateLibrary($library, $request->validated());
        $updatedLibrary = $this->libraryRepository->getLibraryById($library);
        
        return ApiResponse::response(true, 'Library updated successfully.', $updatedLibrary);
    }

    public function destroy(Library $library)
    {
        if (Auth::id() !== $library->user_id) {
            return ApiResponse::response(false, 'Unauthorized.', null, null, 403);
        }

        $this->libraryRepository->deleteLibrary($library);

        return ApiResponse::response(true, 'Library moved to trash successfully.', null, null, 200);
    }
}
