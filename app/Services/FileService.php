<?php

namespace App\Services;

use App\File;
use App\User;
use App\Http\Resources\FileResource as FileResource;
use Validator;
use Carbon;
use Illuminate\Auth\AuthenticationException;
use App\Exceptions\InvalidStateException;

class FileService
{
    public function upload($request)
    {
        $upload = $request->file('photo');
        $userId = $request->user()->id;

        $validator = Validator::make($request->all(), [
            'photo'=> 'required|image'
        ]);

        if ($validator->fails())
        {
            return $validator;
        }

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

        return $file;
    }

    public function setProfilePicture($request)
    {
        $validator = Validator::make($request->all(), [
            'photo'=> 'required|image'
        ]);

        if ($validator->fails())
        {
            return $validator;
        }

        $userId = $request->user()->id;
        $user = User::findOrFail($userId);

        $upload = $request->file('photo');
        $name = $userId.'_profile';
        $path = $upload->move(public_path('/'), $name);

        $user->profile_picture = $name;
        $user->save();

        return $name;
    }
    
    public function changeAlbum($request, $imageId, $album)
    {
        if($album > 3 || $album < 0){
            throw new InvalidException('Invalid album.');
        }

        $userId = $request->user()->id;
        
        $file = File::findOrFail($imageId);

        if($userId != $file->owner){
            throw new AuthenticationException('Unauthenticated.');
        }

        if($album == 0){
            $file->left_position = "";
            $file->width = "";
            $file->top_position = "";
            $file->rotation = "";
            $file->place = null;
        }

        $file->album = $album;

        $file->save();

        return $file;
    }

    public function findAlbumImages($userId, $album)
    {
        if($album > 3 || $album < 0){
            throw new InvalidException('Invalid album.');
        }
        
        $images = File::where('album', $album)->where('owner', $userId)->get();
 
        return $images;
    }

    public function findUserImages($userId)
    {
        return File::where('owner', $userId)->get();
    }

    public function exchangePhotos($userId, $oldPhoto, $newPhoto)
    {
        $oldPhoto = File::findOrFail($oldPhoto);
        $newPhoto = File::findOrFail($newPhoto);

        if($oldPhoto->owner != $userId || $newPhoto->owner != $userId)
        {
            throw new AuthenticationException('Unauthenticated.');
        }

        $newPhoto->album = $oldPhoto->album;
        $newPhoto->width = $oldPhoto->width;
        $newPhoto->top_position = $oldPhoto->top_position;
        $newPhoto->left_position = $oldPhoto->left_position;
        $newPhoto->rotation = $oldPhoto->rotation;
        $newPhoto->place = $oldPhoto->place;

        $oldPhoto->left_position = "";
        $oldPhoto->width = "";
        $oldPhoto->top_position = "";
        $oldPhoto->rotation = "";
        $oldPhoto->place = null;
        $oldPhoto->album = 0;

        $oldPhoto->save();
        $newPhoto->save();
    }

    public function updateAlbumPhotos($photos)
    {
        foreach($photos as $photoDetails)
        {
            $photoDetails = (object)$photoDetails;
            $photo = File::findOrFail($photoDetails->id);

            $photo->left_position = $photoDetails->left_position;
            $photo->width = $photoDetails->width;
            $photo->top_position = $photoDetails->top_position;
            $photo->rotation = $photoDetails->rotation;
            $photo->note = $photoDetails->note;

            $photo->save();
        }
    }
}