<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class, 'language_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'language_id');
    }
}
