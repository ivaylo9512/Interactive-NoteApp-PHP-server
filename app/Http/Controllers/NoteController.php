<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\Http\Resources\NoteResource as NoteResource;
use App\Services\NoteService;

class NoteController extends Controller
{
    private $noteService;

    public function __construct(NoteService $noteService){
        $this->noteService = $noteService;

    } 
    public function findAll()
    {
        $notes = $this->noteService->findAll();

        return NoteResource::collection($notes);
    }
    
    public function findById($id)
    {
        $note = $this->noteService->findById($id);

        return new NoteResource($note);
    }

    public function update(Request $request, $id)
    {
        $this->noteService->update($request, $id);
    }

    public function delete($id)
    {
        $this->noteService->delete($id);
    }
}
