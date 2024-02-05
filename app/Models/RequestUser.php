<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestUser extends Model
{
    use HasFactory;

    protected  $table = 'request_users';

    protected $fillable = [
        'user_id',
        'work_id',
        'status'
    ];
}
