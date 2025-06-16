<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class, 'type_id');
    }
}
