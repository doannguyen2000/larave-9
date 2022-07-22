<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UsersCouserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Models\UsersCourse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(IndexUserRequest $request)
    {
        $searchUser = User::where('name', 'like', '%' . $request->name . '%')
            ->where('email', 'like', '%' . $request->email . '%')
            ->where('role', 'like', '%' . $request->role . '%');

        if ($searchUser->count() == 0) {
            return response()->json(["message" => "No users"], 200);
        }

        if (!is_null($request->orderByValue)) {
            $searchUser = $searchUser->orderBy($request->orderByValue);
        }

        return new UserCollection($searchUser->get());
    }

    public function store(UserRequest $request)
    {
        $uploadAvata = $request->file('avatar')->move(public_path('uploads/'), $request->email . '.' . $request->file('avatar')->getClientOriginalExtension());

        if (!$uploadAvata) {
            return response()->json(["message" => "Avatar upload error"], 400);
        }

        $check = User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $request->avatar,
            'avatar' => $request->email . '.' . $request->file('avatar')->getClientOriginalExtension(),
            'role' => $request->role
        ]);

        if ($check) {
            return response()->json([
                "message" => "Successful create user",
                "data" => User::find(User::max('id'))
            ], 200);
        }

        return response()->json(["message" => "Error create user"], 400);
    }

    public function show($user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User does not exist"], 400);
        }
        return new UserResource(User::find($user));
    }

    public function edit($user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User does not exist"], 400);
        }

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, $user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User does not exist"], 400);
        }

        if (base64_decode($request->avatar, true)) {
            return response()->json(["message" => "Invalid avatar"], 400);
        }

        $folderPath = public_path("uploads/");
        $base64Image = explode(";base64,", $request->avatar);
        $explodeImage = explode("image/", $base64Image[0]);
        $imageType = $explodeImage[1];
        $image_base64 = base64_decode($base64Image[1]);
        $file = $folderPath . $request->email . '.' . $imageType;
        file_put_contents($file, $image_base64);

        User::where('id', $user)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'avatar' => $request->email . '.' . $imageType,
                'role' => $request->role,
            ]);

        return response()->json(["message" => "Update successful", "data" => User::find($user)], 200);
    }

    public function destroy($user)
    {
        if (is_null(User::find($user))) {
            return response()->json(["message" => "User does not exist"], 400);
        }

        User::where('id', $user)->delete();
        return response()->json(["message" => "Delete successfully"], 200);
    }

    public function storeUserCourse(UsersCouserRequest $request)
    {
        if (
            UsersCourse::where('userID', $request->userID)
            ->where('courseID', $request->courseID)
            ->count() > 0
        ) {
            return response()->json(["message" => "Signed up for this course"], 400);
        }

        UsersCourse::insert($request->all());
        return response()->json(["message" => "Successfully registered for the course", "data" => new UserResource(User::find($request->userID))], 200);
    }

    public function destroyUserCourse(UsersCouserRequest $request, $user)
    {
        if (
            UsersCourse::where('userID', $user)
            ->where('courseID', $request->courseID)
            ->count() < 1
        ) {
            return response()->json(["message" => "User's course doesnot exirst"], 400);
        }

        UsersCourse::where('userID', $user)->where('courseID', $request->courseID)->delete();
        return response()->json(["message" => "Delete successfully", "data" => new UserResource(User::find('userID'))], 200);
    }
}
