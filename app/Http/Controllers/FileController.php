<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\SystemFile;


class FileController extends Controller
{
    public function preview_image($uuid){
        $file = SystemFile::where('uuid', $uuid)->get()->first();
        if($file->folder == 'public'){
            return Storage::response($file->path);
        }else{
            return 'File not public!';
        }
        
    }

    public function preview_file($uuid){

        $user = Auth::user();

        if(!$user){
            return 'Unauthorized access! Please login.';
        }

        $file = SystemFile::where('uuid', $uuid)->get()->first();
        return Storage::response($file->path);
    }

    public function download_file($uuid){

        $user = Auth::user();

        if(!$user){
            return 'Unauthorized access! Please login.';
        }

        $file = SystemFile::where('uuid', $uuid)->get()->first();
        return Storage::download($file->path);
    }
}