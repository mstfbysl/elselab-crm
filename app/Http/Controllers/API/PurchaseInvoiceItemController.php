<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\CompanyExpense;
use App\Models\FixAsset;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\UserPayment;
use App\Models\ProjectExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceItemController extends Controller
{
    use RespondTrait;

    public function list_short_term()
    {
        $items = PurchaseInvoiceItem::where('quantity', '!=', DB::raw('used_quantity'))->where('type', 'For Sale')->get()->map(function($item){

            $item_cost = Helpers::human_format($item->cost);
            $item_string = "{$item->invoice->title} / {$item->title} / {$item_cost} ({$item->remaining_quantity})";

            return [
                'id' => $item->id,
                'text' => $item_string
            ];
        });

        if(!$items){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($items);
    }

    public function list_long_term()
    {
        $items = PurchaseInvoiceItem::where('quantity', '!=', DB::raw('used_quantity'))->where('type', 'For Use')->get()->map(function($item){

            $item_cost = Helpers::human_format($item->cost);
            $item_string = "{$item->invoice->title} / {$item->title} / {$item_cost} ({$item->remaining_quantity})";

            return [
                'id' => $item->id,
                'text' => $item_string
            ];
        });

        if(!$items){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($items);
    }

    public function list_fix_asset()
    {
        $items = PurchaseInvoiceItem::where('type', 'For Use')->get()->map(function($item){

            $fa = FixAsset::where('purchase_invoice_item_id', $item->id)->first();

            if($fa){
                return;
            }

            $ce = CompanyExpense::where('purchase_invoice_item_id', $item->id)->where('quantity', 1)->first();
            $up = UserPayment::where('purchase_invoice_item_id', $item->id)->where('quantity', 1)->where('type', '!=', 'Cash')->first();

            if(!$ce && !$up){
                return;
            }

            $item_cost = Helpers::human_format($item->cost);
            $item_string = "{$item->invoice->title} / {$item->title} / {$item_cost}";

            return [
                'id' => $item->id,
                'text' => $item_string
            ];
        });

        return $this->respondSuccess($items);
    }

    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'purchase_invoice_id' => 'required',
        ]);



        $items = PurchaseInvoiceItem::where('purchase_invoice_id', $request->purchase_invoice_id)->get()->map(function($item){
            $popover_content = " ";
            if($item->used_quantity > 0){
                $projectexpenses = ProjectExpense::where("purchase_invoice_item_id", $item->id)->get();
                foreach ($projectexpenses as $project) {
                    $link = "<a href='/project/edit/".$project->project_id."/3' target='_blank'>Project ID: ".$project->project_id."</a><br/>";
                    $popover_content .= $link;
                }
            }

            $percentage = $item->used_quantity / ($item->quantity / 100);
            return [
                $item->id,
                $item->type,
                $item->serial,
                $item->title,
                $item->quantity,
                '<div class="progress-wrapper">
                    <div class="progress progress-bar-success">
                        <div id="edit-invoice-progressbar" style="width: '.$percentage.'%" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="" aria-describedby="example-caption"></div>
                    </div>
                    <a href="#" data-bs-placement="top" data-bs-toggle="popover" title="'. __('locale.Usage').'" data-bs-content="'.$popover_content.'">'.$item->used_quantity.' / '.$item->quantity.'</a>
                </div>',
                Helpers::human_format($item->cost),
                Helpers::human_format($item->total_cost),
                '<a href="#" onclick="onEditItem('.$item->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDeleteItem('.$item->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        return $this->respondSuccessDatatable($items, 1, $items->count());
    }

    public function detail(Request $request)
    {
        $item = PurchaseInvoiceItem::find($request->id);

        if(!$item){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($item);
    }

    public function create(Request $request)
    {
        $request->validate([
            'purchase_invoice_id' => 'required',
            'type' => 'required',
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

        $create = PurchaseInvoiceItem::create([
            'client_id' => $invoice->client_id,
            'purchase_invoice_id' => $request->purchase_invoice_id,
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

            return $this->respondSuccess($create->id, 'Purchase Invoice Item added successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'quantity' => 'required|min:1',
            'cost' => 'required',
        ]);

        $item = PurchaseInvoiceItem::find($request->id);

        if(!$item){
            return $this->respondFail('An error occured!', 500);
        }

        if($item->used_quantity > $request->quantity){
            return $this->respondFail('Quantity cannot be lower than used quantity!', 422);
        }

        $invoice = PurchaseInvoice::find($item->purchase_invoice_id);

        if(!$invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $total_cost = $request->cost * $request->quantity;
        $new_total = $total_cost + ($invoice->used_total - $item->total_cost);

        if($new_total > $invoice->total){
            return $this->respondFail('The total invoice is exceeded, please modify total or reduce cost.');
        }

        $item->type = $request->type;
        $item->serial = $request->serial;
        $item->title = $request->title;
        $item->quantity = $request->quantity;
        $item->cost = $request->cost;
        $update = $item->save();

        if($update)
        {
            $invoice->used_total = $new_total;
            $invoice->save();

            return $this->respondSuccess($update, 'Purchase Invoice Item saved successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $item = PurchaseInvoiceItem::find($request->id);

        if(!$item){
            return $this->respondFail('An error occured!', 500);
        }

        $item->invoice->used_total -= $item->total_cost;
        $item->invoice->save();

        $delete = $item->delete();

        if($delete)
        {
            return $this->respondSuccess($delete, 'Purchase Invoice Item deleted successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }
}
