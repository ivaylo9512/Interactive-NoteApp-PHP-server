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

    public function findAll($state)
    {
        $users = $this->userService->findAll($state);
        return UserResource::collection($users);
    }
    
    public function findById(Request $request, $id)
    {
        $user = $this->userService->findById($request, $id);

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        return $this->userService->update($request, $id);
    }
    
    public function delete(Request $request, $id)
    {
        $this->userService->delete($request, $id);
    }

    public function login(Request $request)
    { 
        $success = $this->userService->login();
        if($success){
            return $success;
        }
        return response()->json(['error'=>'Bad creditentials.'], 401); 
    }

    public function register(Request $request) 
    { 
        $response = $this->userService->register($request, "ROLE_USER");

        if($response instanceof Validator){
            return response()->json(['error'=>$response->errors()], 401);  
        } 

        return response()->json(['success'=>$response], 200);
    }

    public function registerAdmin(Request $request) 
    { 
        $response = $this->userService->register($request, "ROLE_ADMIN");

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
