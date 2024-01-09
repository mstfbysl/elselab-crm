<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;
use App\Models\FixAsset;
use App\Models\PurchaseInvoiceItem;

class FixAssetController extends Controller
{
    use RespondTrait;

    public function list_datatable()
    {
        $assets = FixAsset::get()->map(function($asset){
            return [
                $asset->id,
                $asset->item->invoice->title,
                $asset->item->title,
                $asset->item->serial,
                $asset->valid_date,
                Helpers::human_format($asset->item->cost),
                '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="onEdit('.$asset->id.')"><i class="bi bi-pencil"></i> '.__('locale.Edit').'</a>
                        <a class="dropdown-item" href="#" onclick="onDelete('.$asset->id.')"><i class="bi bi-trash"></i> '.__('locale.Delete').'</a>
                    </div>
                </div>'
            ];
        });

        return $this->respondSuccessDatatable($assets, 1, $assets->count());
    }

    public function create(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'valid_date' => 'required',
        ]);

        $check_exist = FixAsset::where('purchase_invoice_item_id', $request->item_id)
                                ->first();

        if($check_exist){
            return $this->respondFail('This item already exist!', 422);
        }

        $purchase_invoice_id = PurchaseInvoiceItem::where('id', $request->item_id)
                                                    ->first()
                                                    ->purchase_invoice_id;

        $create = FixAsset::create([
            'purchase_invoice_item_id' => $request->item_id,
            'purchase_invoice_id' => $purchase_invoice_id,
            'valid_date' => $request->valid_date,
            'comment' => $request->comment
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Fix Asset added successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'valid_date' => 'required',
        ]);

        $fixed_asset = FixAsset::find($request->id);

        if(!$fixed_asset){
            return $this->respondFail('An error occured!', 500);
        }

        $fixed_asset->valid_date = $request->valid_date;
        $fixed_asset->comment = $request->comment;
        $update = $fixed_asset->save();

        if($update){
            return $this->respondSuccess($update, 'service saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }        

    public function detail(Request $request)
    {
        $asset = FixAsset::find($request->id);

        if(!$asset){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($asset);
    }

    public function delete(Request $request)
    {
        $expense = FixAsset::find($request->id);

        if(!$expense){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $expense->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Fix Asset deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
