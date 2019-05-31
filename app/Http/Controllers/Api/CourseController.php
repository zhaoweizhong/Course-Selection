<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use App\Transformers\CourseTransformer;
use App\Http\Requests\Api\CourseRequest;

class CourseController extends Controller
{
    public function store(CourseRequest $request)
    {
        $course = Course::create([
            'name'       => $request->name,
            'cid'        => $request->cid,
            'credit'     => $request->credit,
            'teacher'    => \Auth::guard('api')->user()->name,
            'time'       => $request->time,
            'location'   => $request->location,
            'capacity'   => $request->capacity,
            'available'  => $request->capacity,
            'creator_id' => \Auth::guard('api')->user()->id
        ]);

        return $this->response->item($course, new CourseTransformer())->setStatusCode(201);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $attributes = $request->only(['name', 'cid', 'credit', 'teacher', 'time', 'location']);

        if ($request->capacity) {
            if ($request->capacity > $course->capacity) {
                $attributes['capacity'] = $request->capacity;
                $attributes['available'] = $course->available + ($request->capacity - $course->capacity);
            } else {
                if ($request->capacity < ($course->capacity - $course->available)) {
                    return $this->response->errorBadRequest('Requested capacity is less than selected students number.');
                } else {
                    $attributes['capacity'] = $request->capacity;
                    $attributes['available'] = $request->capacity - ($course->capacity - $course->available);
                }
            }
        }

        $course->update($attributes);

        return $this->response->item($course, new CourseTransformer());
    }
    
    public function show(Course $course)
    {
        return $this->response->item($course, new CourseTransformer);
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $courses = Course::search($keyword)->paginate(5);
        return $this->response->paginator($courses, new CourseTransformer());
    }

    public function userIndex(User $user)
    {
        $courses = $user->courses()->paginate(5);
        return $this->response->paginator($courses, new CourseTransformer());
    }

    public function delete(Course $course)
    {
        $selections = $course->selections();
        foreach ($selections as $selection) {
            $selection->delete();
        }
        $course->delete();
        return $this->response->noContent()->setStatusCode(200);
    }
}
