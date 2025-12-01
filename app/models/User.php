<?php

namespace App\Models;

class User extends Model {
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password'];
    protected $timestamps = true;
}