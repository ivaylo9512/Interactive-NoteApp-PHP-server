<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Lcobucci\JWT\Parser;
use App\Exceptions\InvalidStateException;
use Illuminate\Auth\AuthenticationException;

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
                $users = User::where('enabled', '=', true)->get();
                break;
            case "blocked":
                $users = User::where('enabled', '=', false)->get();
                break;
            case "all":
                $users = User::all();
                break;
            default:
                throw new InvalidStateException(''.$state.' is an invalid state.');
        }
        return $users;
    }

    public function findById($request, $id)
    {
        $user = User::findOrFail($id);
        $loggedUser = $request->user();
        
        if($user->enabled == false && ($loggedUser == null || $loggedUser->role != "ROLE_ADMIN")){
            throw new AuthenticationException('Unauthenticated.');
        }

        return $user;
    }

    public function delete($request, $id)
    {
        $user = User::findOrFail($id);
        $loggedUser = $request->user();

        if($loggedUser->id != $user->id && $loggedUser->role != "ROLE_ADMIN" ){
            throw new AuthenticationException('Unauthenticated.');
        }

        $user->delete();
    }

    public function login()
    {
        if(Auth::attempt(['username' => request('username'), 'password' => request('password')])){ 
            $user = Auth::user();
            $user->token = $user->createToken('app')-> accessToken;
            $success =  $user; 
            return $success;
        } 
    }
    
    public function register($userSpec, $role)
    {
        $validator = Validator::make((json_decode($userSpec->user, true)), [ 
            'username' => 'required|unique:users', 
            'password' => 'required', 
            'repeatPassword' => 'required|same:password',
            'firstName' => 'required',
            'lastName' => 'required',
            'age' => 'required'
        ]);

        if ($validator->fails()) {           
            return $validator;
        }

        $userInput = json_decode($userSpec->user, true); 
        $userInput['role'] = $role; 
        $userInput['password'] = bcrypt($userInput['password']); 
        $user = User::create($userInput); 

        $user->token =  $user->createToken('app')-> accessToken; 
        return $user;   
    }
    
    public function logout($request)
    {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens->find($id);
        $token->revoke();
    }

    public function update($request, $id)
    {
        $user = User::findOrFail($id);
        $loggedUser = $request->user();

        if($loggedUser->id != $user->id && $loggedUser->role != 'ROLE_ADMIN'){
            throw new AuthenticationException('Unauthenticated.');
        }

        foreach($request->except(["id"]) AS $key => $value){
            $user->{$key} = $value;
        }

        $user -> save();
        return $user;
    }
}