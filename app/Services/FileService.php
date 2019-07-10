<?php

namespace App\Services;

use App\File;
use App\User;
use App\Http\Resources\FileResource as FileResource;
use Validator;
use Carbon;
use Illuminate\Auth\AuthenticationException;

class FileService
{
    public function upload($request)
    {
        $userId = $request->user()->id;

        $upload = $request->file('photo');
        $fileName = $upload->getClientOriginalName();
        $name = $userId.'_'.pathinfo($fileName, PATHINFO_FILENAME);
        $type = pathinfo($fileName, PATHINFO_EXTENSION);
        $size = $upload->getSize();

        $file = new File;
        $file->owner = $userId;
        $file->location = $name;
        $file->type = $type;
        $file->size = $size;

        $file->save();

        $path = $upload->move(public_path('/'), $name);

        return $name;
    }

    public function setProfilePicture($request)
    {
        $userId = $request->user()->id;
        $user = User::findOrFail($id);

        $upload = $request->file('photo');
        $name = $userId.'_profile';
        $path = $upload->move(public_path('/'), $name);

        $user->profilePicture = $name;
        $user->save();
    }
    
    public function changeAlbum($request, $imageId, $album)
    {
        $userId = $request->user()->id;
        
        $file = File::findOrFail($id);

        if($userId != $file->id){
            throw new AuthenticationException('Unauthenticated.');
        }

        if($album == 0){
            $file->left = "";
            $file->width = "";
            $file->top = "";
            $file->place = null;
        }

        $file->alubm = $album;

        $file->save();

        return $file;
    }

    public function findAlbumImages($request, $album)
    {
        $userId = $request->user()->id;
        $images = $userNotes->where('album', $album)->where('owner', $userId)->get();;
 
        return $images;
    }

}