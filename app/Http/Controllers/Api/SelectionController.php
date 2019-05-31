<?php

namespace App\Http\Controllers\Api;

use App\Models\Selection;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Transformers\SelectionTransformer;
use App\Http\Requests\Api\SelectionRequest;

class SelectionController extends Controller
{
    public function store(SelectionRequest $request)
    {
        $selection = Selection::create([
            'student_id' => \Auth::guard('api')->user()->id,
            'course_id'  => $request->course_id,
        ]);

        $course = Course::findOrFail($request->course_id);
        if ($course->available <= 0) {
            return $this->response->errorBadRequest('This course is full.');
        }
        $course->available = $course->available - 1;
        $course->update();

        return $this->response->item($selection, new SelectionTransformer())->setStatusCode(201);
    }

    public function show(Selection $selection)
    {
        return $this->response->item($selection, new SelectionTransformer);
    }

    public function userIndex(User $user)
    {
        $selections = $user->selections()->paginate(5);
        return $this->response->paginator($selections, new SelectionTransformer());
    }

    public function delete(Selection $selection)
    {
        $selection->delete();
        return $this->response->noContent()->setStatusCode(200);
    }
}
