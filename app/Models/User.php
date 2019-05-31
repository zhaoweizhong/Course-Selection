<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Searchable;
    use HasRoles;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sid', 'password', 'name', 'email', 'mobile', 'avatar_url', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin'
    ];

    // Rest omitted for brevity

    public function selections() // For Students
    {
        return $this->hasMany('App\Models\Selection', 'student_id', 'id');
    }

    public function courses() // For Teachers
    {
        return $this->hasMany('App\Models\Course', 'creator_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function toSearchableArray()
    {
        return [
            'sid' => $this->sid,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
