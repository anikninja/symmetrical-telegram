<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookmarkRequest;
use App\Interfaces\BookmarkRepositoryInterface;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class BookmarkController extends Controller
{
    protected BookmarkRepositoryInterface $bookmarkRepository;

    public function __construct(BookmarkRepositoryInterface $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $bookmarks = $this->bookmarkRepository->getUserBookmarks($user);
            return ApiResponse::response(true, 'Bookmarks retrieved successfully.', $bookmarks);
        } catch (Exception $e) {
            return ApiResponse::serverError('Failed to retrieve bookmarks: ' . $e->getMessage());
        }
    }

    public function store(StoreBookmarkRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $bookmark = $this->bookmarkRepository->createBookmark($user, $request->note_id);

            return ApiResponse::response(true, 'Note bookmarked successfully.', $bookmark, null, 201);
        } catch (Exception $e) {
            return ApiResponse::serverError('Failed to bookmark note: ' . $e->getMessage());
        }
    }

    public function destroy(Note $note): JsonResponse
    {
        try {
            $user = Auth::user();
            $bookmarkExists = $this->bookmarkRepository->findUserBookmarkForNote($user, $note);

            if (!$bookmarkExists) {
                return ApiResponse::response(false, 'Bookmark not found.', null, null, 404);
            }

            $deleted = $this->bookmarkRepository->deleteBookmark($user, $note);

            if ($deleted) {
                return ApiResponse::response(true, 'Bookmark removed successfully.');
            } else {
                return ApiResponse::response(false, 'Failed to remove bookmark.', null, null, 500);
            }
        } catch (Exception $e) {
            return ApiResponse::serverError('Failed to remove bookmark: ' . $e->getMessage());
        }
    }
}
