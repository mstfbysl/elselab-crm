<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\ProjectIncome;
use App\Models\Project;

class ProjectIncomeController extends Controller
{
    use RespondTrait;
    
    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
        ]);

        $expenses = ProjectIncome::where('project_id', $request->project_id)->get()->map(function($item){
            
            return [
                $item->id,
                $item->item->invoice->invoice_no,
                $item->item->id,
                $item->item->title,
                Helpers::human_format($item->item->cost),
                '<a href="#" onclick="onDeleteIncome('.$item->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [4];}
        return $this->respondSuccessDatatable($expenses, 1, $expenses->count(), $hidden_collumns);
    }

    public function create(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'item_id' => 'required',
        ]);

        $check_exist = ProjectIncome::where('project_id', $request->project_id)
                                ->where('invoice_item_id', $request->item_id)
                                ->first();

        if($check_exist){
            return $this->respondFail('This item already exist in the project!', 422);
        }

        $project = Project::find($request->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $create = ProjectIncome::create([
            'project_id' => $request->project_id,
            'invoice_item_id' => $request->item_id,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Project Income added successfully.');
        }else{
            
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $income = ProjectIncome::find($request->id);

        if(!$income){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $income->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Project Income deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
