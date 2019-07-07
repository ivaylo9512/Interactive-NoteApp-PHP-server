<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\Http\Resources\NoteResource as NoteResource;
class NoteController extends Controller
{
    public function findAll()
    {
        $notes = Note::all();
        return NoteResource::collection($notes);
    }
    
    public function findById($id)
    {
        $note = Note::findOrFail($id);
        return NoteResource::$note;
    }

    public function update(Request $request, $id){
        Note::whereId($id)->update($request);
    }

    public function delete($id){
        $note = Note::findOrFail($id);
        $note -> delete();
    }
}
