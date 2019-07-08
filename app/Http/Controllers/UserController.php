<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource as UserResource;
use App\Services\UserService as UserService;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function findAll()
    {
        $users = $this->userService->findAll();
        return UserResource::collection($users);
    }
    
    public function findById($id)
    {
        $user = $this->userService->findById($id);

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        User::whereId($id)->update($request);
    }
    
    public function delete($id)
    {
        $this->userService->delete($id);
    }

    public function login(Request $request)
    { 
        $success = $this->userService->login();
        if($success){
            return response()->json(['success' => $success], 200);
        }
        return response()->json(['error'=>'Bad creditentials.'], 401); 
    }

    public function register(Request $request) 
    { 
        $response = $this->userService->register($request);

        if($response instanceof Validator){
            return response()->json(['error'=>$response->errors()], 401);  
        } 

        return response()->json(['success'=>$response], 200);
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request);
    }
}