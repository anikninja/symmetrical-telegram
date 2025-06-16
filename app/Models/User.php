<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements HasAvatar
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'auth_id',
        'language_id',
        'is_guest',
        'is_admin',
        'device_token',
        'custom_fields',
        'avatar_url', // Ensure this matches the column name in your database
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_guest' => 'boolean',
            'is_admin' => 'boolean',
            'custom_fields' => 'array',
        ];
    }

    /**
     * Get the notes associated with the user.
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the bookmarks associated with the user.
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Get the libraries associated with the user.
     */
    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    /**
     * Get the folders for the user.
     */
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Get the note types for the user.
     */
    public function noteTypes()
    {
        return $this->hasMany(NoteType::class);
    }

    /**
     * Get the language associated with the user.
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Get the avatar URL for Filament.
     *
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }
}
