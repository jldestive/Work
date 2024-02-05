<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Work extends Model
{
    use HasFactory;

    public $fillable = [
        'description',
        'status',
        'user_id'
    ];

    /**
     * Get the user that owns the Work
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The users that belong to the Work
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'request_users', 'work_id', 'user_id')->withPivot('id', 'status')->withTimestamps();
    }

    /**
     * The users that belong to the Work
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'work_users', 'work_id', 'user_id')->withPivot('id', 'status')->withTimestamps();
    }
}
