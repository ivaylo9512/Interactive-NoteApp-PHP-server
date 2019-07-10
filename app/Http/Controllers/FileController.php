<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Illuminate\Validation\Validator;

class FileController extends Controller
{
    public function upload(Request $request){
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

        return response()->json(['url'=> $name], 200);
    }
}
