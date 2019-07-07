<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Note;

class File extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
 
    public function user()
    {
        return $this->belongsTo(Note::class, 'id');
    } 
}
