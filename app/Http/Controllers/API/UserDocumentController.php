<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\SystemFile;
class UserDocumentController extends Controller
{
    use RespondTrait;

    public function list_by_user(Request $request)
    {
        $documents = UserDocument::where('user_id', $request->user_id)->get()->map(function($document){
            return [
                'id' => $document->id,
                'user_id' => $document->user_id,
                'title' => $document->title,
                'file_id' => $document->file_id,
                'file_link' => env('MIX_APP_URL')."/pfile/".$document->file->uuid,
            ];
        });

        if(!$documents){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($documents);
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'title' => 'required',
            'file_id' => 'required',
        ]);
        
        $create = UserDocument::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'file_id' => $request->file_id,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'User document created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $document = UserDocument::find($request->id);

        if(!$document){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $document->delete();

        $old_file = SystemFile::find($document->file_id);
        Storage::delete($old_file->path);
        SystemFile::find($document->file_id)->delete();

        if($delete){
            return $this->respondSuccess($delete, 'User document deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
