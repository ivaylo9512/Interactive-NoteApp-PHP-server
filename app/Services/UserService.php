<?php

namespace App\Services;

use App\User;
use App\Http\Resources\UserResource as UserResource;
use Illuminate\Support\Facades\Auth;
use Validator;
use Lcobucci\JWT\Parser;

class UserService{

    public function findAll()
    {
        $users = User::all();
        return $users;
    }
    public function findById($id)
    {
        
        $user = User::findOrFail($id);

        return new UserResource($user);
    }
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
    public function login(){
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('app')-> accessToken; 
            return $success;
        } 
    }
    
    public function register($userSpec){
        $validator = Validator::make($userSpec->all(), [ 
            'username' => 'required|unique:users', 
            'email' => 'required|unique:users|email', 
            'password' => 'required', 
            'repeat_password' => 'required|same:password', 
        ]);

        if ($validator->fails()) {           
            return $validator;
        }

        $userInput = $userSpec->all(); 

        $userInput['password'] = bcrypt($userInput['password']); 
        $user = User::create($userInput); 

        $success['token'] =  $user->createToken('app')-> accessToken; 
        $success['username'] =  $user->username;
        return $success;   
    }
    public function logout($request){
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens->find($id);
        $token->revoke();
    }
}