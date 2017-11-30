<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model {
    protected $table = 'statistics';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'user_correct',
        'user_wrong',
        'correct',
        'wrong',
        'all_answers',
        'updated_at',
    ];
}