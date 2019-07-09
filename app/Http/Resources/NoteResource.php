<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\NoteResource as NoteResource;
use App\Http\Resources\FileResource as FileResource;

class NoteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this -> id,
            'name' => $this -> name,
            'files' => FileResource::Collection($this -> files),
            'note' => $this -> note,
            'date' => $this -> date
        ];
    }
}
