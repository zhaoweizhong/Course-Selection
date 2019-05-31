<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Selection extends Model
{
    use Searchable;

    protected $fillable = ['student_id', 'course_id'];

    /**
    * 获得对应用户
    *
    * @return User
    */
    public function student() {
        return $this->belongsTo('App\Models\User', 'student_id', 'id');
    }

    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id', 'id');
    }
}
