<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NoteResource as NoteResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
          'id' => $this -> id,
          'username' => $this -> username,
          'firstname' => $this -> firstname,
          'lastname' => $this -> lastname,
          'age' => $this -> age,
          'role' => $this -> role,
          'notes' => NoteResource::Collection($this -> notes),
          'profile_picture' => $this -> profile_picture,
          'country' => $this -> country,
        ];
    }
}
