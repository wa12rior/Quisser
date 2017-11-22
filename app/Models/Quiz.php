<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {
    protected $table = 'quizzes';

    protected $fillable = [
        'title',
        'description',
        'body',
        'randomize_questions',
        'randomize_answers',
        'URL',
        'user_id',
        'updated_at'
    ];

    public function updateRandomize($field, $flag) {
        $this->update([
            $field => $flag
        ]);
        // this->updateTime();
    }

    public function updateTitle($title) {
        $this->update([
            'title' => $title
        ]);
        // this->updateTime();
    }

    public function updateBody($body) {
        $this->update([
            'body' => $body
        ]);
        // this->updateTime();
    }

    public function updateTime() {
        $this->update([
            'updated_at' => 'NOW()'
        ]);
    }
}