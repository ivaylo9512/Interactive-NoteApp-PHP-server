<?php

namespace App\Services;

use App\Note;
use App\Http\Resources\NoteResource as NoteResource;
use Validator;
use Carbon;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\InvalidInputException;


class NoteService
{
    public function findAll()
    {
        return Note::all();
    }

    public function findById($request, $id)
    {
        $note = Note::findOrFail($id);
        $loggedUser = $request->user();

        if($loggedUser->id != $note->owner && $loggedUser->role != 'ROLE_ADMIN'){
            throw new AuthenticationException('Unauthenticated.');
        }
        return $note;
    }
 
    public function update($request, $loggedUser)
    {
        $note = Note::findOrFail($request->id);

        if($loggedUser->id != $note->owner && $loggedUser->role != 'ROLE_ADMIN'){
            throw new AuthenticationException('Unauthenticated.');
        }

        foreach($request-> except(["id"]) AS $key => $value){
            $note->{$key} = $value;
        }
        $note->save();

        return $note;
    }

    public function delete($request, $id)
    {
        $note = Note::findOrFail($id);
        $loggedUser = $request->user();

        if($loggedUser->id != $note->owner && $loggedUser->role != 'ROLE_ADMIN'){
            throw new AuthenticationException('Unauthenticated.');
        }

        $note->delete();
    }
    
    public function create($noteSpec, $loggedUser)
    {
        $note = new Note;

        $messages = array(
            'name.required' => 'Name is required.'
        );

        $validator = Validator::make($noteSpec->all(), [ 
            'name' => 'required', 
        ]);

        if ($validator->fails()) {
            throw new InvalidInputException('Note must have a name');
        }

        $date = Carbon::now()->format('Y-m-d');

        $note->name = $noteSpec->name;
        $note->note = $noteSpec->note;
        $note->owner = $loggedUser['id'];
        $note->date = $date;

        $note->save();

        return $note;
    }

    public function findByDate($request, $currentAlbum)
    {
        $loggedUser = $request->user();
        $userNotes = $loggedUser->notes;

        $date = $request->date;

        $notes = $userNotes->where('date', $date);

        foreach($notes as $note){
            $note->files = $note->files->where('album', $currentAlbum);
        }

        return $notes;
    }
}
