<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    public function password(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value,
            set: fn ($value) => Hash::make($value),
        );
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all of the works for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function works(): HasMany
    {
        return $this->hasMany(Work::class);
    }

    /**
     * The requests that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requests(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'request_users', 'user_id', 'work_id')->withPivot('id', 'status')->withTimestamps();
    }

    /**
     * The works that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userWorks(): BelongsToMany
    {
        return $this->belongsToMany(Work::class, 'work_users', 'user_id', 'work_id')->withPivot('id', 'status')->withTimestamps();
    }
}
