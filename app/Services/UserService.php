<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Lcobucci\JWT\Parser;

class UserService
{
    public function findAll($state)
    {
        if (!$state) {
            $state = "";
        }
        $users;
        switch ($state) {
            case "active":
                $users = User::where('state', '=', true)->get();
                break;
            case "blocked":
                $users = User::where('state', '=', false)->get();

                break;
            case "all":
                $users = User::all();
                break;
            default:
                throw new InvalidStateException("\"" + state + "\" is not a valid user state. Use \"active\" , \"blocked\" or \"all\".");
        }
        return $users;
    }

    public function findById($id)
    {
        
        return User::findOrFail($id);
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
    
    public function register($userSpec, $role){
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
        $userInput['role'] = $role; 
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