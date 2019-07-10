<?php

namespace App\Services;

use App\File;
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

}