<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model {
    protected $table = 'answers';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'body',
        'body_bad',
    ];
}