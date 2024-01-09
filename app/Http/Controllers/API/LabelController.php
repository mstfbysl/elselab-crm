<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\SystemLabel;
use Illuminate\Support\Facades\Artisan;

class LabelController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $labels = SystemLabel::all()->map(function($label){
            return [
                'id' => $label->id,
                'text' => $label->title,
                'color' => $label->color
            ];
        });

        if(!$labels){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($labels);
    }

    public function list_datatable()
    {
        $labels = SystemLabel::get()->map(function($label){

            return [
                $label->id,
                $label->title,
                '<i class="bi bi-bookmark-fill" style="color:'.$label->color.'"></i>',
                '<a href="#" onclick="onEdit('.$label->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDelete('.$label->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$labels){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($labels, 1, $labels->count());
    }

    public function detail(Request $request)
    {
        $label = SystemLabel::find($request->id);

        if(!$label){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($label);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'color' => 'required',
        ]);
        
        $create = SystemLabel::create([
            'title' => $request->title,
            'color' => $request->color,
        ]);

        if($create){
            Artisan::call('cache:clear');
            return $this->respondSuccess($create->id, 'Label created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'color' => 'required',
        ]);

        $label = SystemLabel::find($request->id);

        if(!$label){
            return $this->respondFail('An error occured!', 500);
        }

        $label->title = $request->title;
        $label->color = $request->color;
        $update = $label->save();

        if($update){
            Artisan::call('cache:clear');
            return $this->respondSuccess($update, 'Label saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $label = SystemLabel::find($request->id);

        if(!$label){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $label->delete();

        if($delete){
            Artisan::call('cache:clear');
            return $this->respondSuccess($delete, 'Label deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
