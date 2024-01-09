<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\OfferItem;
use Illuminate\Http\Request;

class OfferItemController extends Controller
{
    use RespondTrait;

    public function list_filter(Request $request)
    {
        $items = OfferItem::where('is_used', 0)->get()->map(function($item){
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
