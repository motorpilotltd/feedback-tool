<?php

namespace App\Models;

use App\Traits\WithPerPage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasPermissions,
        HasProfilePhoto,
        HasRoles,
        HasTeams,
        Notifiable,
        TwoFactorAuthenticatable,
        WithPerPage;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'must_change_password', 'provider_user_id', 'provider_token', 'provider_platform',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'two_factor_confirmed_at',
        'azure_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
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
            'banned_at' => 'datetime',
            'must_change_password' => 'boolean',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function ideaSpams(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, 'idea_spam');
    }

    public function commentSpams(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, 'comment_spam');
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, 'votes');
    }

    public function authoredIdeas(): HasMany
    {
        return $this->hasMany(Idea::class, 'author_id')->with(['author', 'ideaStatus']);
    }

    public function addedByIdeas(): HasMany
    {
        return $this->hasMany(Idea::class, 'added_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getAvatar()
    {
        if (! empty($this->profile_photo_path)) {
            return asset('/'.$this->profile_photo_path);
        } else {
            return null;
        }
    }

    public function isSocialiteHasNoPassword()
    {
        if (! isset($this->provider_platform)) {
            return false;
        }

        return $this->provider_platform !== null && empty($this->password);
    }
}
