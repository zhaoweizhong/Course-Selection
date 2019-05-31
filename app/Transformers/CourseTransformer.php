<?php

namespace App\Transformers;

use App\Models\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    public function transform(Course $course)
    {
        return [
            'id'                => $course->id,
            'name'              => $course->name,
            'cid'               => $course->cid,
            'credit'            => $course->credit,
            'teacher'           => $course->teacher,
            'time'              => $course->time,
            'location'          => $course->location,
            'capacity'          => $course->capacity,
            'available'         => $course->available,
            'creator_id'        => $course->creator_id,
            'created_at'        => $course->created_at->toDateTimeString(),
            'updated_at'        => $course->updated_at->toDateTimeString(),
        ];
    }
}
