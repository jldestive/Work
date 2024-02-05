<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_id',
        'status'
    ];
}
