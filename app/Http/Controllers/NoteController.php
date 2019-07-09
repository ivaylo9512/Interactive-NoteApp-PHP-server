<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\Http\Resources\NoteResource as NoteResource;
use App\Services\NoteService;
use App\Services\UserService;

class NoteController extends Controller
{
    private $noteService;
    private $userService;

    public function __construct(NoteService $noteService, UserService $userService){
        $this->noteService = $noteService;
        $this->userService = $userService;

    } 
    public function findAll()
    {
        $notes = $this->noteService->findAll();

        return NoteResource::collection($notes);
    }
    
    public function findById(Request $request, $id)
    {
        $note = $this->noteService->findById($request, $id);

        return new NoteResource($note);
    }

    public function update(Request $request, $id)
    {
       return $this->noteService->update($request, $id);
    }

    public function delete(Request $request, $id)
    {
        $this->noteService->delete($request, $id);
    }
    
    public function create(Request $request)
    {
        $loggedUser = $this->userService->findById($request->user()->id);

        $response = $this->noteService->create($request, $loggedUser);

        if($response instanceof Validator){
            return response()->json(['error'=>$response->errors()], 401); 
        }

        return $response;
    }

    public function findByDate(Request $request, $currentAlbum){

        $notes = $this->noteService->findByDate($request, $currentAlbum);

        return NoteResource::collection($notes);
    }
}
