<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Course extends Model
{
    use Searchable;

    protected $fillable = ['name', 'cid', 'credit', 'teacher', 'time', 'location', 'creator_id', 'capacity', 'available'];

    /**
    * 获得对应用户
    *
    * @return User
    */
    public function creator() {
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }

    public function selections() {
        return $this->hasMany('App\Models\Selection', 'course_id', 'id');
    }
}
