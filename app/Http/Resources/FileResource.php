<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this -> id,
            'location' => $this -> location,
            'album' => $this -> album,
            'leftPosition' => $this -> leftPosition,
            'topPosition' => $this -> topPosition,
            'width' => $this -> width,
            'rotation' => $this -> rotation,
            'place' => $this -> place
        ];
    }
}
