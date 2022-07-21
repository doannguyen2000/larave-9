<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;               
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index()
    {
        $users  = User::paginate(2);
        foreach ($users as $key) {
            $key['course'] = User::find($key['id'])->userCourse;
        }

        return new UserCollection($users);
        // return response()->json( 500);
    }

    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'avata' => 'required|image',
            'role' => 'required',
        ]);

        if (User::where('email', $request->email)->doesntExist()) {

            $check = User::insert([

                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'avata' => $request->file('avata')->getClientOriginalName(),
                'role' => $request->role

            ]);

            if ($check) {
                return User::find(User::max('id'));
            }

            return '{"errors": {
                "tb": [
                    "Error."
                ]
            }}';
        }

        return '{"errors": {
            "tb": [
                "Email exirst."
            ]
        }}';
    }

    public function store(Request $request)
    {
        
    }

    public function show($user)
    {
        return new UserResource(User::find($user));
    }

    public function edit($user)
    {
        return new UserResource($user);
    }

    public function update(Request $request,User $user)
    {
        $user->update($request->all());
    }

    public function destroy(User $user)
    {
        $user->delete($user);
    }
}