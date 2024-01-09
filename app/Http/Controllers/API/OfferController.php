<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\Offer;
use App\Models\OfferItem;

use App\Models\Project;

use App\Models\SystemOfferSerie;
use App\Models\SystemOfferSerieCounter;
use App\Models\SystemSetting;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class OfferController extends Controller
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
            if($i['item_title'] != '' && $i['item_cost'] != '' && $i['item_quantity'] != '' && $i['item_tax'] != ''){
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
        }

        return [
            'total' => $total,
            'sub_total' => $sub_total,
            'tax_total' => $tax_total,
        ];
    }

    public function check_offer_serie(Request $request)
    {
        $check_offer = Offer::where('offer_no', $request->offer_no)->first();

        return $this->respondSuccess($check_offer);
    }

    public function new_offer_no(Request $request)
    {
        $serie = SystemOfferSerie::where('id', $request->serie)->first();

        $counter = SystemOfferSerieCounter::where('serie_id', $request->serie)->where('year', date('Y', strtotime($request->date)))->orderBy('count', 'desc')->first();


        if(!$counter){
            $count = 1;
        }else{
            $count = $counter->count + 1;
        }

        $count_padded = str_pad($count, 4, '0', STR_PAD_LEFT); 
        $slug = substr(date('Y', strtotime($request->date)), 2);
        $offer_serie = $serie->slug . '-' . $slug . $count_padded;

        return $this->respondSuccess($offer_serie);
    }

    public function list_all()
    {
        $offers = Offer::all()->orderBy('id', 'desc')->map(function($offer){
            return [
                'id' => $offer->id,
                'text' => $offer->offer_no
            ];
        });

        if(!$offers){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($offers);
    }

    public function list_datatable()
    {
        $offers = Offer::get()->map(function($offer){

            $client_html = '<div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar bg-light-success me-50"><div class="avatar-content">'.substr($offer->client->name, 0, 1).'</div></div></div><div class="d-flex flex-column"><h6 class="user-name text-truncate mb-0">'.$offer->client->name.'</h6><small class="text-truncate text-muted">'.$offer->client->email.'</small></div></div>';
           
            $is_send = ($offer->is_send == 1) ? 'checked' : ''; 

            return [
                $offer->id,
                '<div class="d-flex flex-column">
                    <span class="fw-bolder mb-25">'.$offer->title.'</span>
                    <span class="font-small-3 text-muted">'.$offer->offer_no.'</span>
                </div>',
                $client_html,
                '<div class="d-flex flex-column">
                    <span class="fw-bolder mb-25">'.Helpers::human_format($offer->total).'</span>
                    <span class="font-small-3 text-muted">'.Helpers::human_format($offer->sub_total).'</span>
                </div>',
                $offer->date,
                '<div class="form-check form-switch form-check-success">
                    <input role="button" type="checkbox" class="form-check-input issend" id="sendSwitch-'.$offer->id.'" data-id="'.$offer->id.'" '.$is_send.'/>
                </div>',
                '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/offer/edit/'.$offer->id.'"><i class="bi bi-pencil"></i></svg>
                            '.__('locale.Edit').'</a>
                        <a class="dropdown-item" href="/api/offer/preview/'.$offer->id.'"><i class="bi bi-eye"></i>
                            '.__('locale.Preview').'</a>
                        <a class="dropdown-item" href="/api/offer/download/'.$offer->id.'"><i class="bi bi-cloud-download"></i> 
                            '.__('locale.Download').'</a>
                    </div>
                </div>'
            ];
        });

        return $this->respondSuccessDatatable($offers, 1, $offers->count());
    }

    public function detail(Request $request)
    {
        $offer = Offer::with('items')->where('id', $request->id)->first();

        if(!$offer){
            return $this->respondFail('An error occured!', 404);
        }

        $offer->repeater_items = $offer->items->map(function($item){
            return [
                'item_title' => $item->title,
                'item_description' => $item->description,
                'item_cost' => $item->cost,
                'item_quantity' => $item->quantity,
                'item_tax' => $item->tax,
                'item_id' => $item->id
            ];
        });

        return $this->respondSuccess($offer);
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

        $repeater_rentals = $project->rentals->filter(function($item) {
                    return is_null($item->bundle_id);
                })->map(function($item){
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
            'offer_no' => 'required',
            'date' => 'required'
        ]);

        if(!$request->items)
        {
            return $this->respondFail('Pasiūlymas tuščias', 422);
        }

        $check_items = $this->calculate_items($request->items);
        
        if($check_items['total'] <= 0)
        {
            return $this->respondFail('Pasiūlymas tuščias', 422);
        }

        DB::beginTransaction();

        try 
        {
            $create_offer = Offer::create([
                'title' => $request->title,
                'client_id' => $request->client_id,
                'created_user_id' => $request->user()->id,
                'offer_no' => $request->offer_no,
                'date' => $request->date,
                'sub_total' => $check_items['sub_total'],
                'total' => $check_items['total'],
                'tax' => $check_items['tax_total'],
                'note' => $request->note
            ]);

            array_map(function($i) use ($create_offer){

                $title = $i['item_title'];
                $description = $i['item_description'];
                $cost = str_replace(',', '', $i['item_cost']);
                $quantity = $i['item_quantity'];
                $tax = ($i['item_tax']) ? $i['item_tax'] : 0;
    
                if($i['item_cost'] && $i['item_quantity'] && $i['item_title'])
                {
                    OfferItem::create([
                        'offer_id' => $create_offer->id,
                        'title' => $title,
                        'description' => $description,
                        'cost' => $cost,
                        'tax' => $tax,
                        'quantity' => $quantity,
                    ])->id;
                }

            }, $request->items);

            $counter = SystemOfferSerieCounter::where('serie_id', 1)->where('year', date('Y', strtotime($request->date)))->orderBy('count', 'desc')->first();
    
            if(!$counter){
                $count = 1;
            }else{
                $count = $counter->count + 1;
            }
            
            SystemOfferSerieCounter::create([
                'serie_id' => 1,
                'year' => date('y'),
                'count' => $count,
                'offer_id' => $create_offer->id,
            ]);

            DB::commit();

            return $this->respondSuccess([], 'offer created successfully.');
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
            'offer_no' => 'required',
            'date' => 'required',
        ]);

        if(!$request->items)
        {
            return $this->respondFail('Pasiūlymas tuščias', 422);
        }

        $check_items = $this->calculate_items($request->items);
        
        if($check_items['total'] <= 0)
        {
            return $this->respondFail('Pasiūlymas tuščias', 422);
        }

        DB::beginTransaction();

        try
        {
            $offer = Offer::with('items')->find($request->id);

            $offer->title = $request->title;
            $offer->client_id = $request->client_id;
            $offer->date = $request->date;
            $offer->sub_total = $check_items['sub_total'];
            $offer->total = $check_items['total'];
            $offer->tax = $check_items['tax_total'];
            $offer->note = $request->note;
            $offer->save();

            $items_old = $offer->items->map(function($i){ return $i->id; })->toArray();
            $items_new = array_map(function($i){ return (int) $i['item_id']; }, $request->items);
            $items_for_delete  = array_diff($items_old, $items_new); 

            $items_for_create = array_map(function($i) use ($offer){

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
                        OfferItem::where('id', $id)->update([
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
                        OfferItem::create([
                            'offer_id' => $offer->id,
                            'title' => $title,
                            'description' => $description,
                            'cost' => $cost,
                            'tax' => $tax,
                            'quantity' => $quantity,
                        ]);
                    }
                }

            }, $request->items);

            OfferItem::destroy($items_for_delete);

            DB::commit();

            return $this->respondSuccess([], 'offer saved successfully.');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();

            return $this->respondFail('An error occured!', 500);
        }
    }

    public function preview(Request $request)
    {
        $offer = Offer::find($request->id);

        $offer_full_no = explode("-", $offer->offer_no);

        $systenSettings = SystemSetting::first();

        $data = [
            'title' => $offer->title,
            'offer_full_no' => $offer->offer_no,
            'serie' => $offer_full_no[0],
            'no' => $offer_full_no[1],
            'date' => $offer->date,
            'from_company_name' => $systenSettings->company_name,
            'from_company_address' => $systenSettings->company_address,
            'from_company_code' => $systenSettings->company_code,
            'from_company_vat_no' => $systenSettings->company_vat_number,
            'client_company_name' => $offer->client->name,
            'client_company_address' => $offer->client->address,
            'client_company_code' => $offer->client->code,
            'client_company_vat_code' => $offer->client->vat_number,
            'note' => $offer->note,
            'sub_total' => Helpers::human_format($offer->sub_total),
            'tax_total' => Helpers::human_format($offer->tax),
            'total' => Helpers::human_format($offer->total),
            'items' => $offer->items,
            'user_name_surname' => $offer->user->name_surname,
            'logo' => ($systenSettings->logo_file) ? base64_encode(Storage::get($systenSettings->logo_file->path)) : null
        ];

        $pdf = PDF::loadView('pdf.offer.regular', $data);

        return $pdf->stream();
    }

    public function download(Request $request)
    {
        $offer = Offer::find($request->id);

        $offer_full_no = explode("-", $offer->offer_no);

        $systenSettings = SystemSetting::first();

        $data = [
            'title' => $offer->title,
            'offer_full_no' => $offer->offer_no,
            'serie' => $offer_full_no[0],
            'no' => $offer_full_no[1],
            'date' => $offer->date,
            'from_company_name' => $systenSettings->company_name,
            'from_company_address' => $systenSettings->company_address,
            'from_company_code' => $systenSettings->company_code,
            'from_company_vat_no' => $systenSettings->company_vat_number,
            'client_company_name' => $offer->client->name,
            'client_company_address' => $offer->client->address,
            'client_company_code' => $offer->client->code,
            'client_company_vat_code' => $offer->client->vat_number,
            'note' => $offer->note,
            'sub_total' => Helpers::human_format($offer->sub_total),
            'tax_total' => Helpers::human_format($offer->tax),
            'total' => Helpers::human_format($offer->total),
            'items' => $offer->items,
            'user_name_surname' => $offer->user->name_surname,
            'logo' => ($systenSettings->logo_file) ? base64_encode(Storage::get($systenSettings->logo_file->path)) : null
        ];


        $pdf = PDF::loadView('pdf.offer.regular', $data);

        return $pdf->download('offer-'.$offer->offer_no.'.pdf');
    }
    
    public function set_is_send(Request $request)
    {
        $offer = Offer::find($request->id);

        if(!$offer){
            return $this->respondFail('An error occured!', 500);
        }

        $offer->is_send = $request->is_send;
        $update = $offer->save();

        if($update){
            return $this->respondSuccess($offer, 'offer saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
