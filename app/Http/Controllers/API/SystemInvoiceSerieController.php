<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\SystemInvoiceSerie;
class SystemInvoiceSerieController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $series = SystemInvoiceSerie::all()->map(function($serie){
            return [
                'id' => $serie->id,
                'text' => $serie->slug
            ];
        });

        if(!$series){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($series);
    }

    public function list_datatable()
    {
        $series = SystemInvoiceSerie::get()->map(function($serie){

            return [
                $serie->id,
                $serie->slug,
                '<a href="#" onclick="onDelete('.$serie->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$series){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($series, 1, $series->count());
    }
    
    public function create(Request $request)
    {
        $request->validate([
            'slug' => 'required',
        ]);
        
        $create = SystemInvoiceSerie::create([
            'slug' => $request->slug,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Invoice Serie created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'short_code' => 'required',
            'description' => 'required',
        ]);

        $serie = SystemInvoiceSerie::find($request->id);

        if(!$serie){
            return $this->respondFail('An error occured!', 500);
        }

        $serie->slug = $request->slug;
        $update = $serie->save();

        if($update){
            return $this->respondSuccess($update, 'Invoice Serie saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $serie = SystemInvoiceSerie::find($request->id);

        if(!$serie){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $serie->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Invoice Serie deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
