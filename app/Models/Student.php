<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends  Authenticatable implements JWTSubject {

    use  Notifiable;


        protected $guard = 'students';

        protected $table = 'students';

        protected $fillable = [
            'name',
            'email',
            'password',
            'phone',
            'date_of_birth',
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
        return $this->getKey(); // Assuming your student model has a primary key called "id".
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
