<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
        ];
    }

    public function isAdmin(): bool
    {
        $role = is_string($this->role) ? UserRole::tryFrom($this->role) : $this->role;
        return $role === UserRole::ADMIN;
    }

    public function isActive(): bool
    {
        $status = is_string($this->status) ? UserStatus::tryFrom($this->status) : $this->status;
        return $status === UserStatus::ACTIVE || $this->status === 'active';
    }

    public function isBlocked(): bool
    {
        $status = is_string($this->status) ? UserStatus::tryFrom($this->status) : $this->status;
        return $status === UserStatus::BLOCKED || $this->status === 'blocked';
    }

    public function personalQuestions(): HasMany
    {
        return $this->hasMany(PersonalQuestion::class);
    }

    public function ocrDocuments(): HasMany
    {
        return $this->hasMany(OcrDocument::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedStudyGuides(): BelongsToMany
    {
        return $this->belongsToMany(StudyGuide::class, 'bookmarks')->withTimestamps();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }
}
