<?php

namespace App\Interfaces;

use App\Models\Note;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookmarkRepositoryInterface
{
    public function getUserBookmarks(User $user): LengthAwarePaginator;
    public function createBookmark(User $user, int $noteId): \App\Models\Bookmark;
    public function deleteBookmark(User $user, Note $note): bool;
    public function findUserBookmarkForNote(User $user, Note $note): ?\App\Models\Bookmark;
}
