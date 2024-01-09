<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\Rental;

class RentalController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $rentals = Rental::all()->map(function($rental){
            return [
                'id' => $rental->id,
                'price' => $rental->price,
                'text' => $rental->title
            ];
        });

        if(!$rentals){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($rentals);
    }

    public function list_available(Request $request)
    {
        $rentals = Rental::get()->map(function($rental){

            if($rental->remaining_quantity == $rental->used_quantity) return;

            $rental_string = "{$rental->title} ({$rental->remaining_quantity})";

            return [
                'id' => $rental->id,
                'price' => $rental->price,
                'text' => $rental_string
            ];
        });

        if(!$rentals){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($rentals);
    }

    public function list_datatable()
    {
        $rentals = Rental::get()->map(function($rental){
            $remaining_quantity = $rental->total_quantity-$rental->used_quantity;

            
            if($rental->total_quantity != 0){ 

            $percentage = $rental->remaining_quantity / ($rental->total_quantity / 100);
            $progress_bar = '<div class="progress-wrapper">
                    <div class="progress progress-bar-success">
                        <div class="progress-bar" style="width: '.$percentage.'%"></div>
                    </div>
                    <div class>'.__('locale.Remaining quantity').': '.$rental->remaining_quantity.' / '.$rental->total_quantity.'</div>
                </div>';
            }else{
                $progress_bar = '-';
            }

            if($rental->total_quantity == 1){
                $title = $rental->title.' ('.$rental->serial.')';
            }else{
                $title = $rental->title;
            }

            return [
                $rental->id,
                $title,
                $progress_bar,
                Helpers::human_format($rental->price),
                '<div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#" onclick="onEdit('.$rental->id.')"><i class="bi bi-pencil"></i> '.__('locale.Edit').'</a>
                        <a class="dropdown-item" href="#" onclick="onDelete('.$rental->id.')"><i class="bi bi-trash"></i> '.__('locale.Delete').'</a>
                    </div>
                </div>'
            ];
        });

        if(!$rentals){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }
        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [3,4];}
        return $this->respondSuccessDatatable($rentals, 1, $rentals->count(), $hidden_collumns);
    }

    public function detail(Request $request)
    {
        $currency = Rental::find($request->id);

        if(!$currency){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($currency);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        
        $create = Rental::create([
            'title' => $request->title,
            'price' => $request->price
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Rental created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $rental = Rental::find($request->id);

        if(!$rental){
            return $this->respondFail('An error occured!', 500);
        }

        $rental->title = $request->title;
        $rental->price = $request->price;
        $update = $rental->save();

        if($update){
            return $this->respondSuccess($update, 'Rental saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $rental = Rental::find($request->id);

        if(!$rental){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $rental->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Rental deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
