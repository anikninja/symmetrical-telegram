<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Library extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'library_id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'library_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'library_id');
    }
}
