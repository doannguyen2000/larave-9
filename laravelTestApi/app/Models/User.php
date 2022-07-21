<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'user';
    protected $fillable = ['id', 'name', 'email', 'password', 'role'];

    public $timestamps = false;

    public function userCourse()
    {
        return $this->belongsToMany(Course::class, 'userscourse', 'userID', 'courseID');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
