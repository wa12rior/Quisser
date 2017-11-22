<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';

    protected $fillable = [
        'email',
        'name',
        'password',
        'updated_at'
    ];

    public function setPassword($password) {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function setUpdatedTime() {
        $this->update([
            'updated_at' => 'NOW()'
        ]);
    }
}