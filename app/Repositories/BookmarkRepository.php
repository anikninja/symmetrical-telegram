<?php

namespace App\Repositories;

use App\Interfaces\BookmarkRepositoryInterface;
use App\Models\Bookmark;
use App\Models\Note;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class BookmarkRepository implements BookmarkRepositoryInterface
{
    public function getUserBookmarks(User $user): LengthAwarePaginator
    {
        return Bookmark::where('user_id', $user->id)
            ->with('note')
            ->latest()
            ->paginate(15);
    }

    public function createBookmark(User $user, int $noteId): Bookmark
    {
        $bookmark = Bookmark::create([
            'user_id' => $user->id,
            'note_id' => $noteId,
        ]);

        $bookmark->load('note');

        return $bookmark;
    }

    public function findUserBookmarkForNote(User $user, Note $note): ?Bookmark
    {
        return Bookmark::where('user_id', $user->id)
            ->where('note_id', $note->id)
            ->first();
    }

    public function deleteBookmark(User $user, Note $note): bool
    {
        $bookmark = $this->findUserBookmarkForNote($user, $note);

        if ($bookmark) {
            return $bookmark->delete();
        }

        return false;
    }
}
