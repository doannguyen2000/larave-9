<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouserRequest;
use App\Http\Requests\SearchCourseRequest;
use App\Http\Resources\ModelCollection;
use App\Http\Resources\ModelResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(SearchCourseRequest $request)
    {
        $searchCourse = Course::where('courseName', 'like', '%' . $request->courseName . '%')
            ->where('courseDescription', 'like', '%' . $request->courseDescription . '%')
            ->where('courseNote', 'like', '%' . $request->courseNote . '%')
            ->where('id', 'like', '%' . $request->id . '%');

        if ($searchCourse->count() == 0) {
            return response()->json(["message" => "No course"], 200);
        }

        if (!is_null($request->orderByValue)) {
            $searchCourse = $searchCourse->orderBy($request->orderByValue);
        }

        $searchCourse = $searchCourse->paginate(2);

        $searchCourse = ([
            'courses' => new ModelCollection($searchCourse->items()),
            'pagination' => [
                'total' => $searchCourse->total(),
                'perPage' => $searchCourse->perPage(),
                'currentPage' => $searchCourse->currentPage(),
                'lastPage' => $searchCourse->lastPage(),
            ],
            // 'pageUrl' => $searchCourse->getUrlRange(1, $searchCourse->lastPage()),
        ]);

        return response()->json(['data' => $searchCourse], 200);
    }

    public function store(CouserRequest $request)
    {
        if (is_null(Course::where('courseName', $request->courseName))) {
            $newCousre = Course::insert($request->all());
            if ($newCousre) {
                return response()->json(["message" => "Create success", "data" => new ModelResource(Course::max('id'))], 200);
            }
            return response()->json(["message" => "Error request"], 400);
        }
        return response()->json(["message" => "Email already in use"], 400);
    }

    public function show($id)
    {
        if (is_null(Course::find($id))) {
            return response()->json(["message" => "Course does not exist"], 400);
        }
        return new ModelResource(Course::find($id));
    }

    public function update(Request $request, $id)
    {
        if (is_null(Course::find($id))) {
            return response()->json(["message" => "Course does not exist"], 400);
        }

        if (!is_null(Course::where('courseName', $request->courseName)) || $request->courseName == Course::where('id', $request->id)->value('courseName')) {
            $newCousre = Course::where('id', $id)->update($request->all());

            if ($newCousre) {
                return response()->json(["messsage" => "Update course success", "data" => new ModelResource(Course::find($id))], 200);
            }

            return response()->json(["message" => "Error create"], 400);
        }

        return response()->json(["message" => "Email already in use"], 400);
    }


    public function destroy($id)
    {
        if (is_null(Course::find($id))) {
            return response()->json(["message" => "Course does not exist"], 400);
        }

        Course::where('id', $id)->delete();
        return response()->json(["message" => "Delete course success"], 200);
    }
}
