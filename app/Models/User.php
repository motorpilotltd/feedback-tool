<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Product;
use App\Traits\WithPerPage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasProfilePhoto,
        HasTeams,
        Notifiable,
        TwoFactorAuthenticatable,
        HasRoles,
        HasPermissions,
        WithPerPage;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider_user_id', 'provider_token', 'provider_platform'
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
        'azure_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function ideaSpams()
    {
        return $this->belongsToMany(Idea::class, 'idea_spam');
    }

    public function commentSpams()
    {
        return $this->belongsToMany(Idea::class, 'comment_spam');
    }

    public function votes()
    {
        return $this->belongsToMany(Idea::class, 'votes');
    }

    public function authoredIdeas() {
        return $this->hasMany(Idea::class, 'author_id')->with(['author', 'ideaStatus']);;
    }

    public function addedByIdeas() {
        return $this->hasMany(Idea::class, 'added_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getAvatar()
    {
        if (!empty($this->profile_photo_path)) {
            return asset('/' . $this->profile_photo_path);
        } else {
            return null;
        }
    }

    public function isSocialiteHasNoPassword()
    {
        if (!isset($this->provider_platform)) {
            return false;
        }
        return $this->provider_platform !== null && empty($this->password);
    }

}
