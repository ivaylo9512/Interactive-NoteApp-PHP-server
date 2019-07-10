<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Note;

class File extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'id';
    public $timestamps = false;
 
    public function note()
    {
        return $this->belongsTo(Note::class, 'note');
    } 
}
