<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\CompanyExpense;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

class CompanyExpenseController extends Controller
{
    use RespondTrait;

    public function list_datatable(Request $request)
    {
        $expenses = CompanyExpense::get()->map(function($expense){
            return [
                $expense->id,
                $expense->item->invoice->title,
                $expense->item->title,
                $expense->quantity,
                Helpers::human_format($expense->item->cost),
                Helpers::human_format($expense->quantity * $expense->item->cost),
                '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="onDelete('.$expense->id.')"><i class="bi bi-trash"></i> '.__('locale.Delete').'</a>
                    </div>
                </div>'
            ];
        });

        return $this->respondSuccessDatatable($expenses, 1, $expenses->count());
    }

    public function create(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required',
        ]);

        $item = PurchaseInvoiceItem::find($request->item_id);

        if($item->remaining_quantity < $request->quantity){
            return $this->respondFail('Quantity cannot be higher than remaining item quantity!', 422);
        }

        $total = $request->quantity * $item->cost;

        $create = CompanyExpense::create([
            'purchase_invoice_item_id' => $request->item_id,
            'purchase_invoice_id' => $item->purchase_invoice_id,
            'quantity' => $request->quantity,
            'total' => $total
        ]);
        
        $item->used_quantity +=  $request->quantity;
        $item->save();

        if($create){
            return $this->respondSuccess($create->id, 'Company Expense created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $expense = CompanyExpense::find($request->id);

        if(!$expense){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $expense->delete();

        $item = PurchaseInvoiceItem::find($expense->purchase_invoice_item_id);
        $item->used_quantity -= $expense->quantity;
        $item->save();

        if($delete){
            return $this->respondSuccess($delete, 'Company Expense deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
