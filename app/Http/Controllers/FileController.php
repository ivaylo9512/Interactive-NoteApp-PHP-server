<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use App\User;
use Illuminate\Validation\Validator;
use App\Services\FileService as FileService;
use App\Http\Resources\FileResource as FileResource;


class FileController extends Controller
{
    private $fileService;

    function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(Request $request)
    {
        $response = $this->fileService->upload($request, $request->user()->id);

        if($response instanceof Validator){
            return response()->json(['message'=>$response->errors()],400);
        }

        return $response;
    }

    public function setProfilePicture(Request $request)
    {
        $user = User::findOrFail($request->user()->id);

        $response = $this->fileService->setProfilePicture($request, $user);

        if($response instanceof Validator){
            return response()->json(['message'=>$response->errors()],400);
        }
        
        return response()->json(['url'=> $response], 200);
    }

    public function changeAlbum(Request $request, $imageId, $album)
    {
        return $this->fileService->changeAlbum($request, $imageId, $album);
    }

    public function findAlbumImages(Request $request, $album)
    {
        
        $images = $this->fileService->findAlbumImages($request->user()->id, $album);
        
        return $images;
    }

    public function findUserImages(Request $request)
    {
        $images = $this->fileService->findUserImages($request->user()->id);
        
        return FileResource::collection($images);
    }

    public function exchangePhotos(Request $request, $oldPhoto, $newPhoto)
    {

        return $this->fileService->exchangePhotos($request->user()->id, $oldPhoto, $newPhoto);
    }

    public function updateAlbumPhotos(Request $request){

        $this->fileService->updateAlbumPhotos($request->photos);
    }
}
