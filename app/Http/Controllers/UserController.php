<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    //Display the specified resource.
    public function user(Request $request)
    {
        $userId = Auth::user()->id;
        $user = User::with('profile')->find($userId);
        if(!$user) {
            return response()->json(['message'=>'User not found'],404);
        }
        return new UserResource($user);
    }



    //Display a listing of the resource.
    public function users()
    {
        $users = User::with('profile')->get();
        if(!$users) {
            return response()->json(['message'=>'User not found'],404);
        }
        return new UserCollection($users);
    }




    //Store a newly created resource in storage.
    public function signup(UserProfileRequest $request)
    {

        //Create User Login
        $user = User::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'password'=> bcrypt($request['password']),
        ]);

        //Create User Profile
        $user->profile()->create([
            'phone'=> $request['phone'],
            'city' => $request['city'],
            'country' => $request['country'],
            'address'=> $request['address'],
            'role' => $request['role'],
        ]);

        return response()->json(['message'=>'User Registered Successfully...']);
    }




    //Update the specified resource in storage.
    public function UpdateUser(UserProfileRequest $request)
    {
        $userId = Auth::user()->id;
        $user= User::find($userId);

        //Update User Login
        $user->update([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);

        //Update User Profile
        $user->profile()->update([
            'phone'=> $request['phone'],
            'city' => $request['city'],
            'country' => $request['country'],
            'address'=> $request['address'],
            'role' => $request['role'],
        ]);

        return response()->json(['message'=>'User Updated Successfully...']);


    }




    //Remove the specified resource from storage.
    public function UserDelete(Request $request)
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $user->delete();

        return response()->json(['message'=>'User Deleted Successfully...']);

    }




    //Login.
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password))  {
            return response()->json(['message'=>'User Not Found'] );
        }
        $result['token']= $user->createToken('Mytkn')->plainTextToken;

        return ['result'=> $result, 'message'=>"User Logged In Successfully"] ;

    }


    //Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'User Logged Out Successfully...']);
    }


}
