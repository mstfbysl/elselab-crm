<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\InvoiceItem;

use App\Models\Project;
use App\Models\ProjectIncome;
use App\Models\SystemSetting;
// use App\Models\ProjectService;
// use App\Models\ProjectRental;
// use App\Models\ProjectExpense;

use App\Models\SystemInvoiceSerie;
use App\Models\SystemInvoiceSerieCounter;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class InvoiceController extends Controller
{
    use RespondTrait;

    private function calculate_items($items = [])
    {
        $total = 0;
        $sub_total = 0;
        $tax_total = 0;
        $count = 0;

        if($items == [] || !$items || gettype($items) != 'array'){
            return 0;
        }

        foreach ($items as $i) {
            $cost = str_replace(',', '', $i['item_cost']);
            $quantity = $i['item_quantity'];
            $tax = $i['item_tax'];

            $item_sub_total = round($cost * $quantity, 2);
            $item_tax_total = round($item_sub_total / 100 * $tax, 2);
            $item_total = round($item_sub_total + $item_tax_total, 2);

            $total += $item_total;
            $sub_total += $item_sub_total;
            $tax_total += $item_tax_total;

            $count++;
        }

        return [
            'total' => $total,
            'sub_total' => $sub_total,
            'tax_total' => $tax_total,
        ];
    }

    public function check_invoice_serie(Request $request)
    {
        $check_invoice = Invoice::where('invoice_no', $request->invoice_no)->first();

        return $this->respondSuccess($check_invoice);
    }

    public function new_invoice_no(Request $request)
    {
        $serie = SystemInvoiceSerie::where('id', $request->serie)->first();

        $counter = SystemInvoiceSerieCounter::where('serie_id', $request->serie)->where('year', date('Y', strtotime($request->date)))->orderBy('count', 'desc')->first();


        if(!$counter){
            $count = 1;
        }else{
            $count = $counter->count + 1;
        }

        $count_padded = str_pad($count, 4, '0', STR_PAD_LEFT);
        $slug = substr(date('Y', strtotime($request->date)), 2);
        $invoice_serie = $serie->slug . '-' . $slug . $count_padded;

        return $this->respondSuccess($invoice_serie);
    }

    public function list_all()
    {
        $invoices = Invoice::all()->orderBy('id', 'desc')->map(function($invoice){
            return [
                'id' => $invoice->id,
                'text' => $invoice->invoice_no
            ];
        });

        if(!$invoices){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($invoices);
    }

    public function list_datatable()
    {
        $invoices = Invoice::get()->map(function($invoice){

            $client_html = '<div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar bg-light-success me-50"><div class="avatar-content">'.substr($invoice->client->name, 0, 1).'</div></div></div><div class="d-flex flex-column"><h6 class="user-name text-truncate mb-0">'.$invoice->client->name.'</h6><small class="text-truncate text-muted">'.$invoice->client->email.'</small></div></div>';

            $is_paid = ($invoice->is_paid == 1) ? 'checked' : '';
            $is_accountant = ($invoice->is_accountant == 1) ? 'checked' : '';

            return [
                $invoice->id,
                $invoice->invoice_no,
                $client_html,
                '<div class="d-flex flex-column">
                    <span class="fw-bolder mb-25">'.Helpers::human_format($invoice->total).'</span>
                    <span class="font-small-3 text-muted">'.Helpers::human_format($invoice->sub_total).'</span>
                </div>',
                $invoice->date,
                '<div class="form-check form-switch form-check-success">
                    <input role="button" type="checkbox" class="form-check-input ispaid" id="paidSwitch-'.$invoice->id.'" data-id="'.$invoice->id.'" '.$is_paid.'/>
                </div>',
                '<div class="form-check form-switch form-check-success">
                    <input role="button" type="checkbox" class="form-check-input isaccountant" id="accountantSwitch-'.$invoice->id.'" data-id="'.$invoice->id.'" '.$is_accountant.'/>
                </div>',
                '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/invoice/edit/'.$invoice->id.'"><i class="bi bi-pencil"></i></svg>
                            '.__('locale.Edit').'</a>
                        <a class="dropdown-item" href="/api/invoice/preview/'.$invoice->id.'"><i class="bi bi-eye"></i>
                            '.__('locale.Preview').'</a>
                        <a class="dropdown-item" href="/api/invoice/download/'.$invoice->id.'"><i class="bi bi-cloud-download"></i>
                            '.__('locale.Download').'</a>
                    </div>
                </div>'
            ];
        });

        return $this->respondSuccessDatatable($invoices, 1, $invoices->count());
    }

    public function detail(Request $request)
    {
        $invoice = Invoice::with('items')->where('id', $request->id)->first();

        if(!$invoice){
            return $this->respondFail('An error occured!', 404);
        }

        $invoice->repeater_items = $invoice->items->map(function($item){
            return [
                'item_title' => $item->title,
                'item_description' => $item->description,
                'item_cost' => $item->cost,
                'item_quantity' => $item->quantity,
                'item_tax' => $item->tax,
                'item_id' => $item->id
            ];
        });

        return $this->respondSuccess($invoice);
    }

    public function details_from_project(Request $request)
    {
        $project = Project::with(['expenses' , 'services', 'rentals'])->where('id', $request->id)->first();

        if(!$project){
            return $this->respondFail('An error occured!', 404);
        }

        $repeater_items = collect([]);

        $repeater_services = $project->services->map(function($item){
            return [
                'item_title' => $item->title,//$item->services->title,
                'item_description' => '',
                'item_cost' => $item->price,
                'item_quantity' => $item->quantity,
                'item_tax' => 21,
                'item_id' => $item->service_id
            ];
        });

        $repeater_expenses = $project->expenses->map(function($item){
            return [
                'item_title' => $item->title,
                'item_description' => $item->serial,
                'item_cost' => $item->cost,
                'item_quantity' => $item->quantity,
                'item_tax' => 21
            ];
        });

        $repeater_rentals = $project->rentals->map(function($item){
            return [
                    'item_title' => $item->title,
                    'item_description' => '',
                    'item_cost' => $item->price,
                    'item_quantity' => $item->quantity,
                    'item_tax' => 21
                ];
        });

        $project->repeater = array_merge($repeater_services->toArray(), $repeater_expenses->toArray(), $repeater_rentals->toArray());

        $project->owner_id = 1;

        return $this->respondSuccess($project);
    }


    public function create(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'invoice_no' => 'required',
            'date' => 'required'
        ]);

        if(!$request->items)
        {
            return $this->respondFail('You have to add at least 1 item to invoice.', 422);
        }

        $check_items = $this->calculate_items($request->items);

        if($check_items['total'] <= 0)
        {
            return $this->respondFail('You have to add at least 1 item to invoice.', 422);
        }

        DB::beginTransaction();

        try
        {
            $create_invoice = Invoice::create([
                'client_id' => $request->client_id,
                'created_user_id' => $request->user()->id,
                'invoice_no' => $request->invoice_no,
                'date' => $request->date,
                'type' => $request->type,
                'sub_total' => $check_items['sub_total'],
                'total' => $check_items['total'],
                'tax' => $check_items['tax_total'],
                'note' => $request->note
            ]);
            $project_id = $request->project_id;

            $items_for_create = array_map(function($i) use ($create_invoice, $project_id){

                $title = $i['item_title'];
                $description = $i['item_description'];
                $cost = str_replace(',', '', $i['item_cost']);
                $quantity = $i['item_quantity'];
                $tax = ($i['item_tax']) ? $i['item_tax'] : 0;

                if($i['item_cost'] && $i['item_quantity'] && $i['item_title'])
                {
                    $item_id = InvoiceItem::create([
                        'invoice_id' => $create_invoice->id,
                        'title' => $title,
                        'description' => $description,
                        'cost' => $cost,
                        'tax' => $tax,
                        'quantity' => $quantity,
                    ])->id;

                    if($project_id){
                        ProjectIncome::create([
                            'project_id' => $project_id,
                            'invoice_item_id' => $item_id,
                        ]);
                    }
                }

            }, $request->items);

            $counter = SystemInvoiceSerieCounter::where('serie_id', $request->serie)->where('year', date('Y', strtotime($request->date)))->orderBy('count', 'desc')->first();

            if(!$counter){
                $count = 1;
            }else{
                $count = $counter->count + 1;
            }

            SystemInvoiceSerieCounter::create([
                'serie_id' => $request->serie,
                'year' => date('y'),
                'count' => $count,
                'invoice_id' => $create_invoice->id,
            ]);

            DB::commit();

            return $this->respondSuccess([], 'Invoice created successfully.');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return $this->respondFail($th, 500);
            return $this->respondFail('An error occured!'.$th, 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'invoice_no' => 'required',
            'date' => 'required',
        ]);

        if(!$request->items)
        {
            return $this->respondFail('You have to add at least 1 item to invoice.', 422);
        }

        $check_items = $this->calculate_items($request->items);

        if($check_items['total'] <= 0)
        {
            return $this->respondFail('You have to add at least 1 item to invoice.', 422);
        }

        DB::beginTransaction();

        try
        {
            $invoice = Invoice::with('items')->find($request->id);

            $invoice->client_id = $request->client_id;
            $invoice->date = $request->date;
            $invoice->sub_total = $check_items['sub_total'];
            $invoice->total = $check_items['total'];
            $invoice->tax = $check_items['tax_total'];
            $invoice->note = $request->note;
            $invoice->save();

            $items_old = $invoice->items->map(function($i){ return $i->id; })->toArray();
            $items_new = array_map(function($i){ return (int) $i['item_id']; }, $request->items);
            $items_for_delete  = array_diff($items_old, $items_new);

            $items_for_create = array_map(function($i) use ($invoice){

                if(isset($i['item_id']))
                {
                    $id = $i['item_id'];
                    $title = $i['item_title'];
                    $description = $i['item_description'];
                    $cost = str_replace(',', '', $i['item_cost']);
                    $quantity = $i['item_quantity'];
                    $tax = ($i['item_tax']) ? $i['item_tax'] : 0;

                    if($i['item_cost'] && $i['item_quantity'] && $i['item_title'])
                    {
                        InvoiceItem::where('id', $id)->update([
                            'title' => $title,
                            'description' => $description,
                            'cost' => $cost,
                            'tax' => $tax,
                            'quantity' => $quantity,
                        ]);
                    }
                }
                else
                {
                    $title = $i['item_title'];
                    $description = $i['item_description'];
                    $cost = str_replace(',', '', $i['item_cost']);
                    $quantity = $i['item_quantity'];
                    $tax = ($i['item_tax']) ? $i['item_tax'] : 0;

                    if($i['item_cost'] && $i['item_quantity'] && $i['item_title'])
                    {
                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'title' => $title,
                            'description' => $description,
                            'cost' => $cost,
                            'tax' => $tax,
                            'quantity' => $quantity,
                        ]);
                    }
                }

            }, $request->items);

            InvoiceItem::destroy($items_for_delete);

            DB::commit();

            return $this->respondSuccess([], 'Invoice saved successfully.');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();

            return $this->respondFail('An error occured!', 500);
        }
    }

    public function preview(Request $request)
    {
        $invoice = Invoice::find($request->id);
        $notation_lib = new \App\Libraries\SumToText();

        $invoice_full_no = explode("-", $invoice->invoice_no);

        $systemSettings = SystemSetting::first();

        $data = [
            'invoice_full_no' => $invoice->invoice_no,
            'serie' => $invoice_full_no[0],
            'no' => $invoice_full_no[1],
            'date' => $invoice->date,
            'from_company_name' => $systemSettings->company_name,
            'from_company_address' => $systemSettings->company_address,
            'from_company_code' => $systemSettings->company_code,
            'from_company_vat_no' => $systemSettings->company_vat_number,
            'client_company_name' => $invoice->client->name,
            'client_company_address' => $invoice->client->address,
            'client_company_code' => $invoice->client->code,
            'client_company_vat_code' => $invoice->client->vat_number,
            'note' => $invoice->note,
            'suma_text' => $notation_lib->sum_to_text($invoice->total),
            'sub_total' => $invoice->sub_total,
            'tax_total' => $invoice->tax,
            'total' => $invoice->total,
            'items' => $invoice->items,
            'user_name_surname' => $invoice->user->name_surname,
            'logo' => ($systemSettings->logo_file) ? base64_encode(Storage::get($systemSettings->logo_file->path)) : null
        ];

        if($invoice->type == 'Standart'){
            $pdf = PDF::loadView('pdf.invoice.regular', $data);
        }elseif($invoice->type == 'Preliminary'){
            $pdf = PDF::loadView('pdf.invoice.proforma', $data);
        }elseif($invoice->type == 'Credit'){
            $pdf = PDF::loadView('pdf.invoice.credit', $data);
        }

        return $pdf->stream();
    }

    public function download(Request $request)
    {
        $invoice = Invoice::find($request->id);
        $notation_lib = new \App\Libraries\SumToText();

        $invoice_full_no = explode("-", $invoice->invoice_no);

        $systemSettings = SystemSetting::first();

        $data = [
            'invoice_full_no' => $invoice->invoice_no,
            'serie' => $invoice_full_no[0],
            'no' => $invoice_full_no[1],
            'date' => $invoice->date,
            'from_company_name' => $systemSettings->company_name,
            'from_company_address' => $systemSettings->company_address,
            'from_company_code' => $systemSettings->company_code,
            'from_company_vat_no' => $systemSettings->company_vat_number,
            'client_company_name' => $invoice->client->name,
            'client_company_address' => $invoice->client->address,
            'client_company_code' => $invoice->client->code,
            'client_company_vat_code' => $invoice->client->vat_number,
            'note' => $invoice->note,
            'suma_text' => $notation_lib->sum_to_text($invoice->total),
            'sub_total' => $invoice->sub_total,
            'tax_total' => $invoice->tax,
            'total' => $invoice->total,
            'items' => $invoice->items,
            'user_name_surname' => $invoice->user->name_surname,
            'logo' => ($systemSettings->logo_file) ? base64_encode(Storage::get($systemSettings->logo_file->path)) : null
        ];

        if($invoice->type == 'Standart'){
            $pdf = PDF::loadView('pdf.invoice.regular', $data);
        }elseif($invoice->type == 'Preliminary'){
            $pdf = PDF::loadView('pdf.invoice.proforma', $data);
        }elseif($invoice->type == 'Credit'){
            $pdf = PDF::loadView('pdf.invoice.credit', $data);
        }

        return $pdf->download('SF-'.$invoice->invoice_no.'.pdf');
    }

    public function set_is_paid(Request $request)
    {
        $invoice = Invoice::find($request->id);

        if(!$invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $invoice->is_paid = $request->is_paid;
        $update = $invoice->save();

        if($update){
            return $this->respondSuccess($invoice, 'Invoice saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function set_is_accountant(Request $request)
    {
        $invoice = Invoice::find($request->id);

        if(!$invoice){
            return $this->respondFail('An error occured!', 500);
        }

        $invoice->is_accountant = $request->is_accountant;
        $update = $invoice->save();

        if($update){
            return $this->respondSuccess($invoice, 'Invoice saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
