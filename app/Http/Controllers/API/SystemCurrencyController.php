<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\SystemCurrency;
use Illuminate\Support\Facades\Artisan;

class SystemCurrencyController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $currencies = SystemCurrency::all()->map(function($currency){
            return [
                'id' => $currency->id,
                'text' => $currency->short_code
            ];
        });

        if(!$currencies){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($currencies);
    }

    public function list_datatable()
    {
        $currencies = SystemCurrency::get()->map(function($currency){

            return [
                $currency->id,
                $currency->short_code,
                $currency->description,
                $currency->symbol,
                '<a href="#" onclick="onEdit('.$currency->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDelete('.$currency->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$currencies){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($currencies, 1, $currencies->count());
    }

    public function detail(Request $request)
    {
        $currency = SystemCurrency::find($request->id);

        if(!$currency){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($currency);
    }

    public function create(Request $request)
    {
        $request->validate([
            'short_code' => 'required',
            'description' => 'required',
        ]);
        
        $create = SystemCurrency::create([
            'short_code' => $request->short_code,
            'description' => $request->description,
        ]);

        if($create){
            Artisan::call('cache:clear');
            return $this->respondSuccess($create->id, 'Currency created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'short_code' => 'required',
            'description' => 'required',
        ]);

        $currency = SystemCurrency::find($request->id);

        if(!$currency){
            return $this->respondFail('An error occured!', 500);
        }

        $currency->short_code = $request->short_code;
        $currency->description = $request->description;
        $update = $currency->save();

        if($update){
            Artisan::call('cache:clear');
            return $this->respondSuccess($update, 'Currency saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $currency = SystemCurrency::find($request->id);

        if(!$currency){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $currency->delete();

        if($delete){
            Artisan::call('cache:clear');
            return $this->respondSuccess($delete, 'Currency deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
