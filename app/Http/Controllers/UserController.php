<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource as UserResource;
class UserController extends Controller
{
    public function findAll()
    {
        $users = User::all();
        return UserResource::collection($users);
    }
    public function findById($id)
    {
        $users = User::findOrFail($id);
        return UserResource::collection($users);
    }
    public function update(Request $request, $id){
        User::whereId($id)->update($request);
    }
    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
    }
}
