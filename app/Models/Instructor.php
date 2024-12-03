<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Instructor extends  Authenticatable implements JWTSubject{
    use  Notifiable;


    protected $guard = 'instructors';

    protected $table = 'instructors';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'subject',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Assuming your instructor model has a primary key called "id".
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}


