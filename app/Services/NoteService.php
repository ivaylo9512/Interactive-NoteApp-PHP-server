<?php

namespace App\Services;

use App\Note;

class NoteService
{
    public function findAll()
    {
        return Note::all();
    }

    public function findById($id)
    {
        return Note::findOrFail($id);
    }
 
    public function update($noteSpec, $id)
    {
        Note::whereId($id)->update($noteSpec);
    }

    public function delete($id)
    {
        $note = Note::findOrFail($id);
        $note -> delete();
    }
}
