<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\File;

class Note extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'note');
    }
}
