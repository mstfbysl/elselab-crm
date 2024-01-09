<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\PurchaseInvoiceItem;

use Illuminate\Http\Request;

class ProjectExpenseController extends Controller
{
    use RespondTrait;

    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
        ]);

        $expenses = ProjectExpense::where('project_id', $request->project_id)->get()->map(function($expense){

            if($expense->quantity == 1){
                $title = $expense->item->title.' ('.$expense->item->serial.')';
            }else{
                $title = $expense->item->title;
            }

            return [
                $expense->id,
                $expense->item->invoice->title,
                $title,
                $expense->quantity,
                Helpers::human_format($expense->item->cost),
                Helpers::human_format($expense->quantity * $expense->item->cost),
                '<a href="#" onclick="onDeleteExpense('.$expense->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });
        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [4,5];}
        return $this->respondSuccessDatatable($expenses, 1, $expenses->count(), $hidden_collumns);
    }

    public function create(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required',
        ]);

        $project = Project::find($request->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $item = PurchaseInvoiceItem::find($request->item_id);

        if($item->remaining_quantity < $request->quantity){
            return $this->respondFail('Quantity cannot be higher than remaining item quantity!', 422);
        }

        $create = ProjectExpense::create([
            'project_id' => $request->project_id,
            'purchase_invoice_item_id' => $request->item_id,
            'purchase_invoice_id' => $item->purchase_invoice_id,
            'quantity' => $request->quantity
        ]);

        if($create)
        {  
            $item->used_quantity += $request->quantity;
            $item->save();
    
            return $this->respondSuccess($create->id, 'Project Expense added successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $expense = ProjectExpense::find($request->id);

        if(!$expense){
            return $this->respondFail('An error occured!', 500);
        }

        $project = Project::find($expense->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }
        
        $item = PurchaseInvoiceItem::find($expense->purchase_invoice_item_id);

        $item->used_quantity -= $expense->quantity;
        $item->save();

        $delete = $expense->delete();

        if($delete)
        {
            return $this->respondSuccess($delete, 'Project Expense deleted successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }
}
