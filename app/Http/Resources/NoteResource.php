<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NoteResource as NoteResource;

class NoteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this -> id,
            'name' => $this -> name,
            'note' => $this -> note,
            'date' => $this -> date
        ];
    }
}
