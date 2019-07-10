<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Illuminate\Validation\Validator;
use App\Services\FileService as FileService;


class FileController extends Controller
{
    private $fileService;

    function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(Request $request)
    {
        $name = upload();

        return response()->json(['url'=> $name], 200);
    }
}
