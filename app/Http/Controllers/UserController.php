<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource as UserResource;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public function findAll()
    {
        $users = User::all();
        return UserResource::collection($users);
    }
    public function findById($id)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    public function update(Request $request, $id){
        User::whereId($id)->update($request);
    }
    
    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function login(){ 
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], 200); 
        } 
        else{ 
            
            return response()->json(['error'=>'Bad creditentials.'], 401); 
        } 
    }
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'username' => 'required|unique:users', 
            'email' => 'required|unique:users|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $userInput = $request->all(); 

        $userInput['password'] = bcrypt($userInput['password']); 
        $user = User::create($userInput); 

        $success['token'] =  $user->createToken('app')-> accessToken; 
        $success['username'] =  $user->username;
        return response()->json(['success'=>$success], 200); 
    }
}
