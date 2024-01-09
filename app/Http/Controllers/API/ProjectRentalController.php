<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\ProjectRental;
use App\Models\Project;
use App\Models\Rental;
use App\Models\RentalBundle;

class ProjectRentalController extends Controller
{
    use RespondTrait;

    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
        ]);

        $project_rentals = ProjectRental::where('project_id', $request->project_id)->get()->map(function($project_rental){

            if($project_rental->bundle_id == NULL){
                $price = $project_rental->price;
                if($project_rental->rental->serial){
                    $title = $project_rental->rental->title.' ('.$project_rental->rental->serial.')';
                }else{
                    $title = $project_rental->rental->title;
                }
            }else{
                $price = "-";
                $bundle_title = RentalBundle::find($project_rental->bundle_id)->title;
                $title = $project_rental->rental->title.' (<b>'.$bundle_title.'</b>)';
            }
            
            return [
                $project_rental->id,
                $title,
                $project_rental->quantity,
                $price,
            '<a href="#" onclick="onEditRental('.$project_rental->id.')" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i></a>
             <a href="#" onclick="onDeleteRental('.$project_rental->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [3];}
        return $this->respondSuccessDatatable($project_rentals, 1, $project_rentals->count(), $hidden_collumns);
    }

    public function detail(Request $request)
    {
        $project_rental = ProjectRental::find($request->id);

        if(!$project_rental){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($project_rental);
    }

    public function create(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'rental_id' => 'required',
            'quantity' => 'required'
        ]);

        $project = Project::find($request->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $rental = Rental::find($request->rental_id);

        if($project->core_status == 2){ // If Project is in progress
            if($rental->remaining_quantity < $request->quantity){
                return $this->respondFail('Quantity cannot be higher than remaining item quantity!');
            }
        }

        $create = ProjectRental::create([
            'project_id' => $request->project_id,
            'rental_id' => $request->rental_id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        if($create){
            if($project->core_status == 2){ // If Project is in progress
                $rental->used_quantity += $request->quantity;
                $rental->save();
            }
            return $this->respondSuccess($create->id, 'Project Rental added successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $project_rental = ProjectRental::find($request->id);
        $project = Project::find($project_rental->project_id);
        if(!$project_rental){
            return $this->respondFail('An error occured!', 500);
        }

        $rental = Rental::find($project_rental->rental_id);
        
        $current_quantity = $project_rental->quantity;
        
        if($project->core_status == 2){ // If Project is in progress
            // rental quantity + project rental quantity - new quantity
            if (($rental->remaining_quantity + $current_quantity) < $request->quantity) {
                return $this->respondFail('Quantity cannot be higher than remaining item quantity!');
            }
        }

        $edit = $project_rental->update([
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        if($edit){
            if($project->core_status == 2){ // If Project is in progress
                $rental->used_quantity = $rental->used_quantity - $current_quantity + $request->quantity;
                $rental->save();
            }

            return $this->respondSuccess($project_rental, 'Project Rental Item saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($project_rental, 'Project Rental Item found.');
    }

    public function delete(Request $request)
    {
        $project_rental = ProjectRental::find($request->id);
        $project = Project::find($project_rental->project_id);
        if(!$project_rental){
            return $this->respondFail('An error occured!', 500);
        }

        $rental = Rental::find($project_rental->rental_id);

        if($project->core_status == 2){ // If Project is in progress
            $rental->used_quantity -= $project_rental->quantity;
            $rental->save();
        }

        $delete = $project_rental->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Project Rental deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
