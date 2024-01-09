<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\PurchaseInvoiceItem;
use App\Models\UserPayment;
use App\Models\User;
class UserPaymentController extends Controller
{
    use RespondTrait;

    public function list_datatable(Request $request)
    {
        $payments = UserPayment::get()->map(function($payment){

            if($payment->type == 'Cash'){
                $quantity = 1;
                $cost = Helpers::human_format($payment->total);
                $total_cost = $cost;
            }else{
                $quantity = $payment->quantity;
                $cost = Helpers::human_format($payment->total);
                $total_cost = Helpers::human_format($payment->quantity * $payment->item->cost);
            }

            $user_data =  User::find($payment->user_id);

            return [
                $payment->id,
                $payment->type,
                $user_data->name_surname,
                $quantity,
                $cost,
                $total_cost,
                '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="onDelete('.$payment->id.')"><i class="bi bi-trash"></i> '.__('locale.Delete').'</a>
                    </div>
                </div>'
            ];
        });

        return $this->respondSuccessDatatable($payments, 1, $payments->count());
    }

    public function create_cash(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'type' => 'required',
            'total' => 'required'
        ]);

        $create = UserPayment::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'total' => $request->total,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'User Payment created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function create_item(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'type' => 'required',
            'item_id' => 'required',
            'quantity' => 'required'
        ]);

        $item = PurchaseInvoiceItem::find($request->item_id);

        if($item->remaining_quantity < $request->quantity){
            return $this->respondFail('Quantity cannot be higher than remaining item quantity!', 422);
        }

        $create = UserPayment::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'total' => $item->cost,
            'purchase_invoice_id' => $item->purchase_invoice_id,
            'purchase_invoice_item_id' => $request->item_id,
            'quantity' => $request->quantity,
        ]);

        $item->used_quantity +=  $request->quantity;
        $item->save();

        if($create){
            return $this->respondSuccess($create->id, 'User Payment created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $payment = UserPayment::find($request->id);

        if(!$payment){
            return $this->respondFail('An error occured!', 500);
        }

        if($payment->type != 'Cash')
        {
            $item = PurchaseInvoiceItem::find($payment->purchase_invoice_item_id);
            $item->used_quantity -= $payment->quantity;
            $item->save();
        }

        $delete = $payment->delete();

        if($delete){
            return $this->respondSuccess($delete, 'User Payment deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
