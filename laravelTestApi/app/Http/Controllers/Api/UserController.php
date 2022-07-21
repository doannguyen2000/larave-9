<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UsersCouserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\Course;
use App\Models\User;
use App\Models\UsersCourse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {
        $users  = User::paginate(2);
        return new UserCollection($users);
    }

    public function create()
    {
        
    }

    public function store(UserRequest $request)
    {
        if (User::where('email', $request->email)->doesntExist()) {
            $check = User::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'avata' => $request->avata,
                'avata' => $request->file('avata')->getClientOriginalName(),
                'role' => $request->role
            ]);

            if ($check) {
                return response()->json([
                    "message"=>"Successful create user",
                    "data"=>User::find(User::max('id'))
                ], 200);
            }
            return response()->json(["message" => "Error create user"], 400);
        }

        return response()->json(["message" => "Email already exists"], 400);
    }

    public function show($user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User doesnot exirst"], 400);
        }
        return new UserResource(User::find($user));
    }

    public function edit($user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User doesnot exirst"], 400);
        }
        return new UserResource($user);
    }

    public function update(Request $request, $user)
    {

        if (is_null(User::find($user))) {
            return response()->json(["message" => "User doesnot exirst"], 400);
        }

        if ($request->isMethod('PUT')) {
            $request->validate([
                'name' => 'required|min:2',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'avata' => 'required',
                'role' => 'required',
            ]);
        }

        if ($request->isMethod('PATCH')) {
            $va= [];
            foreach ($request->all() as $key => $value) {
                if ($key == "name") {
                    $va["name"]  = "required|min:2" ;
                }
    
                if ($key == "password") {
                    $va["password"]="required|min:6";
                }
    
                if ($key == "avata") {
                    $va["avata"] = "required";
                }
    
                if ($key == "role") {
                    $va["role"] = "required";
                }
    
                if ($key == "email") {
                    $va["email"] = "required|email";
                }
            }

            $request->validate($va);
        }

        if(User::where('email',$request->email)->count() > 0 && User::find($user)->email != $request->email){
            return response()->json(["message" => "Email already exists"], 400);
        }
        

        User::where('id', $user)
            ->update($request->all());

        return response()->json(["message" => "Successful update","data"=>User::find($user)], 200);
    }

    public function destroy($user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User doesnot exirst"], 400);
        }
        User::where('id', $user)->delete();
        return response()->json(["message" => "Successful delete"], 200);
    }

    public function storeUserCourse(UsersCouserRequest $request)
    {
        if (UsersCourse::where('userID', $request->userID)
            ->where('courseID', $request->courseID)
            ->count() > 0
        ) {
            return response()->json(["message" => "Signed up for this course"], 400);
        }

        if (User::where('id', $request->userID)->count() < 1) {
            return response()->json(["message" => "User doesnot exirst"], 400);
        }

        if (Course::where('id', $request->courseID)->count() < 1) {
            return response()->json(["message" => "Course doesnot exirst"], 400);
        }

        UsersCourse::insert($request->all());
        return response()->json(["message" => "Successfully registered for the course","data"=> new UserResource(User::find($request->userID))], 200);
    }

    public function destroyUserCourse(UsersCouserRequest $request,$user )
    {
        if (UsersCourse::where('userID', $user)
            ->where('courseID', $request->courseID)
            ->count() < 1
        ) {
            return response()->json(["message" => "User's course doesnot exirst"], 400);
        }
        UsersCourse::where('userID', $user)->where('courseID', $request->courseID)->delete();
        return response()->json(["message" => "Successful delete","data"=> new UserResource(User::find('userID'))], 200);
    }
}
