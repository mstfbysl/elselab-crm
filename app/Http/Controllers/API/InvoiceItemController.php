<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\InvoiceItem;
use App\Models\ProjectIncome;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    use RespondTrait;

    public function list_filter(Request $request)
    {
        $items = InvoiceItem::where('is_used', 0)->get()->map(function($item){

            $pi = ProjectIncome::where('invoice_item_id', $item->id)->first();

            if($pi){
                return;
            }

            return [
                'id' => $item->id,
                'text' => "Item: $item->title - Total: $item->total ($item->quantity)"
            ];
        });

        if(!$items){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($items);
    }
}
