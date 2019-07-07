<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
