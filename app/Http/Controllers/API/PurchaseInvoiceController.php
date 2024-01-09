<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;
use App\Models\PurchaseInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\SystemFile;
class PurchaseInvoiceController extends Controller
{
    use RespondTrait;

    public function list_datatable(Request $request)
    {

        $purchase_invoices = PurchaseInvoice::filterByType($request)->map(function($purchase_invoice){
                if($purchase_invoice->file){
                    $invoice = '<a class="btn btn-info btn-sm" href="'.env('MIX_APP_URL')."/pfile/".$purchase_invoice->file->uuid.'" target="_blank"><i class="bi bi-file-earmark-arrow-down-fill"></i></a>';
                }else{
                    $invoice = '';
                }
                $is_paid = ($purchase_invoice->is_paid == 1) ? 'checked' : ''; 
                $is_accountant = ($purchase_invoice->is_accountant == 1) ? 'checked' : ''; 

                //Avoit division by zero
                if($purchase_invoice->total == 0){
                    $percentage_used = 100;
                    $percentage_assisned = 100;
                }else{
                    $percentage_used = $purchase_invoice->used_total / ($purchase_invoice->total / 100);
                    $percentage_assisned = $purchase_invoice->assigned_total / ($purchase_invoice->total / 100);
                }

                return [
                    '<div class="d-flex flex-column">
                        <span class="fw-bolder mb-25">'.$purchase_invoice->title.'</span>
                        <span class="font-small-3 text-muted">'.$purchase_invoice->client->name.'</span>
                    </div>',
                    '<div class="d-flex flex-column">
                        <span class="fw-bolder mb-25">'.Carbon::parse($purchase_invoice->date)->format('Y-m-d').'</span>
                        <span class="font-small-3 text-muted">'.$purchase_invoice->serie.'</span>
                    </div>',
                    '<div class="progress-wrapper">
                        <div class="progress progress-bar-success" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" data-bs-original-title="'.__('locale.Assigned').'">
                            <div class="progress-bar" style="width: '.$percentage_assisned.'%"></div>
                        </div>
                         <span class="font-small-3 text-muted">'.Helpers::human_format($purchase_invoice->assigned_total).' / '.Helpers::human_format($purchase_invoice->total).'</span>
                        <div class="progress progress-bar-info" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" data-bs-original-title="'.__('locale.Inserted').'">
                            <div class="progress-bar" style="width: '.$percentage_used.'%"></div>
                        </div>
                        <span class="font-small-3 text-muted">'.Helpers::human_format($purchase_invoice->used_total).' / '.Helpers::human_format($purchase_invoice->total).'</span>
                    </div>',
                    '<div class="d-flex flex-column">
                        <span class="fw-bolder mb-25">'.Helpers::human_format($purchase_invoice->total).'</span>
                        <span class="font-small-3 text-muted">'.__('locale.With VAT').': '.Helpers::human_format($purchase_invoice->total_with_vat).'</span>
                    </div>',
                    '<div class="form-check form-switch form-check-success">
                        <input role="button" type="checkbox" class="form-check-input ispaid" id="paidSwitch-'.$purchase_invoice->id.'" data-id="'.$purchase_invoice->id.'" '.$is_paid.'/>
                    </div>',
                    '<div class="form-check form-switch form-check-success">
                        <input role="button" type="checkbox" class="form-check-input isaccountant" id="accountantSwitch-'.$purchase_invoice->id.'" data-id="'.$purchase_invoice->id.'" '.$is_accountant.'/>
                    </div>',
                    $invoice,
                    '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="onEdit('.$purchase_invoice->id.',1)"><i class="bi bi-pencil"></i> '.__('locale.Edit').'</a>
                        <a class="dropdown-item" href="#" onclick="onEdit('.$purchase_invoice->id.',2)"><i class="bi bi-box-seam"></i> '.__('locale.Items').'</a>
                        <a class="dropdown-item" href="#" onclick="onEdit('.$purchase_invoice->id.',3)"><i class="bi bi-arrow-repeat"></i> '.__('locale.Rentals').'</a>
    
                        <a class="dropdown-item" href="#" onclick="onDelete('.$purchase_invoice->id.')"><i class="bi bi-trash"></i> '.__('locale.Delete').'</a>
                    </div>
                </div>'
                ];
        });

        return $this->respondSuccessDatatable($purchase_invoices, 1, $purchase_invoices->count());
    }

    public function detail(Request $request)
    {
        $purchase_invoice = PurchaseInvoice::where('id', $request->id)->first();

        if(!$purchase_invoice){
            return $this->respondFail('An error occured!', 404);
        }

        $purchase_invoice->human_total = Helpers::human_format($purchase_invoice->remaining_total);
        $purchase_invoice->percentage_total = $purchase_invoice->remaining_total / ($purchase_invoice->total / 100);

        return $this->respondSuccess($purchase_invoice);
    }

    public function create(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'title' => 'required',
            'serie' => 'required',
            'date' => 'required',
            'total' => 'required',
            'total_with_vat' => 'required',
            'is_paid' => 'required',
            'is_accountant' => 'required'
        ]);
        
        $create = PurchaseInvoice::create([
            'client_id' => $request->client_id,
            'created_user_id' => $request->user()->id,
            'title' => $request->title,
            'serie' => $request->serie,
            'date' => $request->date,
            'total' => $request->total,
            'total_with_vat' => $request->total_with_vat,
            'is_paid' => $request->is_paid,
            'is_accountant' => $request->is_accountant,
            'document' => $request->document
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Purchase Invoice created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'title' => 'required',
            'serie' => 'required',
            'date' => 'required',
            'total' => 'required',
            'total_with_vat' => 'required',
            'is_paid' => 'required',
            'is_accountant' => 'required'
        ]);

        $client = PurchaseInvoice::find($request->id);

        if(!$client){
            return $this->respondFail('An error occured!', 500);
        }

        if($client->document AND $request->document){// Remove old document
            if($client->document != $request->document){ // If old document 
                $old_file = SystemFile::find($client->document);
                Storage::delete($old_file->path);
                SystemFile::find($client->document)->delete();
            }
        }

        $client->client_id = $request->client_id;
        $client->title = $request->title;
        $client->serie = $request->serie;
        $client->date = $request->date;
        $client->total = $request->total;
        $client->total_with_vat = $request->total_with_vat;
        $client->is_paid = $request->is_paid;
        $client->is_accountant = $request->is_accountant;
        $client->document = $request->document;
        $update = $client->save();




        if($update){
            return $this->respondSuccess($update, 'Purchase Invoice saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $purchase_invoice = PurchaseInvoice::find($request->id);

        if(!$purchase_invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $purchase_invoice->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Purchase Invoice deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function set_is_paid(Request $request)
    {
        $purchase_invoice = PurchaseInvoice::find($request->id);

        if(!$purchase_invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $purchase_invoice->is_paid = $request->is_paid;
        $update = $purchase_invoice->save();

        if($update){
            return $this->respondSuccess($purchase_invoice, 'Purchase Invoice saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function set_is_accountant(Request $request)
    {
        $purchase_invoice = PurchaseInvoice::find($request->id);

        if(!$purchase_invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $purchase_invoice->is_accountant = $request->is_accountant;
        $update = $purchase_invoice->save();

        if($update){
            return $this->respondSuccess($purchase_invoice, 'Purchase Invoice saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
