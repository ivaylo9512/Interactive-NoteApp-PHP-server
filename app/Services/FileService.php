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
            $file->rightPosition = "";
            $file->width = "";
            $file->bottomPosition = "";
            $file->rotation = "";
            $file->place = null;
            $file->note = null;
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
        $newPhoto->bottomPosition = $oldPhoto->bottomPosition;
        $newPhoto->rightPosition = $oldPhoto->rightPosition;
        $newPhoto->rotation = $oldPhoto->rotation;
        $newPhoto->place = $oldPhoto->place;
        $newPhoto->note = $oldPhoto->note;

        $oldPhoto->rightPosition = null;
        $oldPhoto->width = null;
        $oldPhoto->bottomPosition = null;
        $oldPhoto->rotation = null;
        $oldPhoto->place = null;
        $oldPhoto->album = 0;
        $oldPhoto->note = null;

        $oldPhoto->save();
        $newPhoto->save();

        return $newPhoto;
    }

    public function updateAlbumPhotos($photos)
    {
        foreach($photos as $photoDetails)
        {
            $photoDetails = (object)$photoDetails;
            $photo = File::findOrFail($photoDetails->id);

            $photo->rightPosition = $photoDetails->rightPosition;
            $photo->width = $photoDetails->width;
            $photo->bottomPosition = $photoDetails->bottomPosition;
            $photo->rotation = $photoDetails->rotation;
            $photo->note = $photoDetails->note;

            $photo->save();
        }
    }
}