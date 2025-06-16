<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Note extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'time_duration',
        'transcription',
        'summary',
        'language_id',
        'type_id',
        'library_id',
        'folder_id',
        'user_id',
    ];
    protected $casts = [
        'language_id' => 'integer',
        'type_id' => 'integer',
        'library_id' => 'integer',
        'folder_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function type()
    {
        return $this->belongsTo(NoteType::class, 'type_id');
    }

    public function library()
    {
        return $this->belongsTo(Library::class, 'library_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookmarks()
    {
        return $this->hasOne(Bookmark::class, 'note_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }
}
