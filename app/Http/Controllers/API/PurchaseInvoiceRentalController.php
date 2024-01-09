<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\PurchaseInvoiceRental;
use App\Models\PurchaseInvoice;
use App\Models\Rental;
use App\Helpers\Helpers;

class PurchaseInvoiceRentalController extends Controller
{
    use RespondTrait;

    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'purchase_invoice_id' => 'required',
        ]);

        $rentals = PurchaseInvoiceRental::where('purchase_invoice_id', $request->purchase_invoice_id)->get()->map(function($rental){
            return [
                $rental->id,
                $rental->serial,
                $rental->title,
                $rental->quantity,
                Helpers::human_format($rental->cost),
                Helpers::human_format($rental->total_cost),
                '<a href="#" onclick="onEditRental('.$rental->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDeleteRental('.$rental->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a><div class="dropdown">'
            ];
        });

        return $this->respondSuccessDatatable($rentals, 1, $rentals->count());
    }

    public function detail(Request $request)
    {
        $rental = PurchaseInvoiceRental::find($request->id);

        if(!$rental){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($rental);
    }

    public function create(Request $request)
    {
        $request->validate([
            'purchase_invoice_id' => 'required',
            'title' => 'required',
            'quantity' => 'required',
            'cost' => 'required',
        ]);

        $invoice = PurchaseInvoice::find($request->purchase_invoice_id);

        if(!$invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $total_cost = ($request->cost * $request->quantity);

        if($invoice->remaining_total < $total_cost){
            return $this->respondFail('The total invoice is exceeded, please modify total or reduce cost.');
        }

        if(!$request->rental_id){
            $create = Rental::create([
                'title' => $request->title
            ]);

            $rental_id = $create->id;
        }else{
            $rental_id = $request->rental_id;
        }

        $create = PurchaseInvoiceRental::create([
            'purchase_invoice_id' => $request->purchase_invoice_id,
            'rental_id' => $rental_id,
            'type' => $request->type,
            'serial' => $request->serial,
            'title' => $request->title,
            'quantity' => $request->quantity,
            'cost' => $request->cost,
        ]);

        if($create)
        {
            $invoice->used_total += $total_cost;
            $invoice->save();
    
            return $this->respondSuccess($create->id, 'Purchase Invoice Rental added successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'rental_id' => 'required',
            'title' => 'required',
            'quantity' => 'required|min:1',
            'cost' => 'required',
        ]);

        $rental = PurchaseInvoiceRental::find($request->id);

        if($rental->rental->used_quantity > 0){
            return $this->respondFail('This Purchase Invoice Rental is using by project or projects. You cannot delete using rentals.');
        }

        $invoice = PurchaseInvoice::find($rental->purchase_invoice_id);

        if(!$invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $total_cost = $request->cost * $request->quantity;
        $new_total = $total_cost + ($invoice->used_total - $rental->total_cost);

        if($new_total > $invoice->total){
            return $this->respondFail('The total invoice is exceeded, please modify total or reduce cost.');
        }

        $rental->rental_id = $request->rental_id;
        $rental->serial = $request->serial;
        $rental->title = $request->title;
        $rental->quantity = $request->quantity;
        $rental->cost = $request->cost;
        $update = $rental->save();

        if($update)
        {
            $invoice->used_total = $new_total;
            $invoice->save();

            return $this->respondSuccess($update, 'Purchase Invoice Rental saved successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $rental = PurchaseInvoiceRental::find($request->id);

        if(!$rental){
            return $this->respondFail('An error occured!', 500);
        }

        if($rental->rental){
            if($rental->rental->used_quantity > 0){
                return $this->respondFail('This Purchase Invoice Rental is using by project or projects. You cannot delete using rentals.');
            }
        }


        $rental->invoice->used_total -= $rental->total_cost;
        $rental->invoice->save();

        $delete = $rental->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Purchase Invoice Rental deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
