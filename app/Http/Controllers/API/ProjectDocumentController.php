<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\SystemFile;
class ProjectDocumentController extends Controller
{
    use RespondTrait;

    public function list_by_project(Request $request)
    {
        $documents = ProjectDocument::where('project_id', $request->project_id)->get()->map(function($document){
            return [
                'id' => $document->id,
                'project_id' => $document->project_id,
                'title' => $document->title,
                'file_id' => $document->file_id,
                'preview_link' => env('MIX_APP_URL')."/pfile/".$document->file->uuid,
                'download_link' => env('MIX_APP_URL')."/dfile/".$document->file->uuid,
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
            'project_id' => 'required',
            'title' => 'required',
            'file_id' => 'required',
        ]);
        
        $create = ProjectDocument::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'file_id' => $request->file_id,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'project document created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $document = ProjectDocument::find($request->id);

        if(!$document){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $document->delete();

        $old_file = SystemFile::find($document->file_id);
        Storage::delete($old_file->path);
        SystemFile::find($document->file_id)->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Project document deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
