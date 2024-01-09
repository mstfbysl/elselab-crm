<?php

namespace App\Http\Controllers\Api;

use Exception;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\SystemFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SystemFileController extends Controller
{
    use RespondTrait;

    public function list_datatable()
    {
        $files = SystemFile::get()->map(function($file){
            $url = Storage::url($file->path);
            return [
                $file->id,
                $file->extension,
                $file->path,
                '<a class="btn btn-primary btn-sm" href="'.$url.'" target="_blank"><i class="bi bi-file-earmark-arrow-down-fill"></i></a>',
            ];
        });

        if(!$files){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($files, 1, $files->count());
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'upload_file' => 'required|mimes:pdf,xlxs,xlx,docx,doc,csv,txt,png,gif,jpg,jpeg,svg|max:10240',
            ]);
    
            $file = $request->file('upload_file');
    
            $hash = Str::uuid();
            $name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
                
            $path = Storage::putFileAs(
                $request->folder,
                $file,
                $hash . '-' . $name
            );
    
            $insert = SystemFile::create([
                'uuid' => $hash,
                'folder' => $request->folder,
                'extension' => $extension,
                'path' => $path,
            ]);
    
            return $this->respondSuccess($insert, 'File uploaded successfully.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'File upload failed.'], 500);
        }
    }
    
}
