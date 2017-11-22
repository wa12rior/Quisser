<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDesc extends Model {
    protected $table = 'user_desc';

    protected $fillable = [
        'date_of_birth',
        'description',
        'age',
        'location',
        'full_name',
        'avatar_path',
        'user_id',
    ];
}