<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    use RespondTrait;

    public function list_datatable_filter(Request $request)
    {
        // $filters = [];

        // if(isset($request->type)){
        //     $filters[] = ['type', '=', $request->type];
        // }

        $warehouse_items = PurchaseInvoiceItem::with('invoice.client')->whereRaw('quantity - used_quantity != 0')->get()->map(function($item){  
            return [
                $item->id,
                $item->invoice->client->name,
                $item->invoice->title,
                $item->title,
                $item->remaining_quantity,
                Helpers::human_format($item->cost),
                Helpers::human_format($item->total_cost),
            ];
        });

        return $this->respondSuccessDatatable($warehouse_items, 1, $warehouse_items->count());
    }
}
