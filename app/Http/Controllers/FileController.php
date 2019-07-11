<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
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
        $response = $this->fileService->upload($request);

        if($response instanceof Validator){
            return response()->json(['message'=>$response->errors()],400);
        }

        return response()->json(['url'=> $response], 200);
    }

    public function setProfilePicture(Request $request)
    {
        $response = $this->fileService->setProfilePicture($request);

        if($response instanceof Validator){
            return response()->json(['message'=>$response->errors()],400);
        }
        
        return response()->json(['url'=> $response], 200);
    }

    public function changeAlbum(Request $request, $imageId, $album)
    {
        $this->fileService->changeAlbum($request, $imageId, $album);
    }

    public function findAlbumImages(Request $request, $album)
    {
        $images = $this->fileService->findAlbumImages($request, $album);
        
        return FileResource::collection($images);
    }

    public function findUserImages(Request $request)
    {
        $images = $this->fileService->findUserImages($request->user()->id);
        
        return FileResource::collection($images);
        
    }
}
