<?php

namespace App\Transformers;

use App\Models\Selection;
use League\Fractal\TransformerAbstract;

class SelectionTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['course'];

    public function transform(Selection $selection)
    {
        return [
            'id'                => $selection->id,
            'student_id'        => $selection->student_id,
            'course_id'         => $selection->course_id,
            'created_at'        => $selection->created_at->toDateTimeString(),
            'updated_at'        => $selection->updated_at->toDateTimeString(),
        ];
    }

    public function includeCourse(Selection $selection)
    {
        return $this->item($selection->course, new CourseTransformer());
    }
}
