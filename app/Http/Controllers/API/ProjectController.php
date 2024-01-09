<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\Project;
use App\Models\Rental;
use App\Models\ProjectStatus;
use App\Models\ProjectOwner;
use App\Models\ProjectLabel;
use App\Models\ProjectRental;
use App\Models\SystemLabel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class ProjectController extends Controller
{
    use RespondTrait;

    public function list_datatable(Request $request)
    {
        if (isset($request->type)) {
            if ($request->type == 1 || $request->type == 2) {
                $filters = ['1', '2'];
            } elseif ($request->type == 3) {
                $filters = ['3'];
            }else{
                $filters = ['1', '2', '3'];
            }
        }

        $projects = Project::whereIn('core_status', $filters)->get()->map(function ($project) {

        $users = ProjectOwner::where('project_id', $project->id)->get()->map(function($user){
            $user_data =  User::find($user->user_id);
            $name =  $user_data->name_surname;
            $profile_picture = $user_data->getProfilePicturePreviewAttribute();
            $return = '<div data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="" class="avatar pull-up" data-bs-original-title="'.$name.' ('.$user->percentage.'%)">
                <img src="'.$profile_picture.'" alt="Avatar" height="26" width="26">
            </div>';

            return [
                $return
            ];
        });

        if($project->status_id){
            $status = ProjectStatus::where('id', $project->status_id)->first();
            $status = '<span class="badge rounded-pill" style="background-color:'.$status->color.'">'.$status->title.'</span>';
        }else{
            $status = '';
        }
        
        $labels = '';

        if($project->labels){
            foreach ($project->labels as $label) {
                $tada_data = SystemLabel::where('id', $label->label_id)->first();
                $labels .= ' <span class="badge" style="background-color:'.$tada_data->color.'">'.$tada_data->title.'</span>';
            }
        }

        $budget_popover = __('locale.Income').' : '.Helpers::human_format($project->project_income).'<br/>'.
                          __('locale.Expenses').' : '.Helpers::human_format($project->project_expense).'<br/>'.
                          __('locale.Profit').' : <strong>'.Helpers::human_format($project->project_income-$project->project_expense).'</strong><br/>'.
                          __('locale.Expected profit').' : ∼'.Helpers::human_format($project->expected_profit);

        if($project->project_income > 0){
            $budget_btn = 'btn-outline-success';
            $budget_btn_value = Helpers::human_format($project->project_income-$project->project_expense);
        }else{
            $budget_btn = 'btn-outline-info';
            $budget_btn_value = Helpers::human_format($project->expected_profit);;//Helpers::human_format($project->project_income-$project->project_expense);
        }

        $date_now = date("Y-m-d");
        
        if($project->finish_date < $date_now){
            $finish_date = '<span class="badge rounded-pill badge-light-danger">'. $project->finish_date.'</span>';
        }elseif($project->finish_date < date('Y-m-d', strtotime($date_now. ' + 7 days'))){
            $finish_date = '<span class="badge rounded-pill badge-light-warning">'. $project->finish_date.'</span>';
        }else{
            $finish_date = '<span class="badge rounded-pill badge-light-primary">'.$project->finish_date.'</span>';
        }

        if($project->is_finshed){
            $finish_date = '<i class="bi bi-check-circle"></i>';
        }

        return [
            $project->id,
            $project->core_status,
            $project->title,
            $finish_date,
            $users,
            $status,
            $labels,
            '<h6 class="user-name text-truncate mb-0">'.$project->client->name.'</h6>',
            '<button type="button" class="btn btn-icon '.$budget_btn.' waves-effect" data-bs-placement="top" data-bs-toggle="popover" title="Budget" 
            data-bs-content="'.$budget_popover.'">
            '.$budget_btn_value.'
            </button>',
            '<div class="dropdown">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.',1)"><i class="bi bi-pencil"></i></svg>
                        '.__('locale.Project details').'</a>
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.',2)"><i class="bi bi-wrench"></i>
                        '.__('locale.Services').'</a>
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.', 3)"><i class="bi bi-basket"></i> 
                    '.__('locale.Expenses').'</a>
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.', 4)"><i class="bi bi-arrow-repeat"></i>
                    '.__('locale.Rentals').'</a>
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.',5)"><i class="bi bi-cash-stack"></i>
                        '.__('locale.Incomes').'</a>
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.',6)"><i class="bi bi-people"></i>
                        '.__('locale.Owners').'</a>
                    <a class="dropdown-item" href="#" onclick="onEdit('.$project->id.',7)"><i class="bi bi-journal-bookmark"></i>
                        '.__('locale.Documents').'</a>
                    <a class="dropdown-item" href="#" onclick="onDelete('.$project->id.')"><i class="bi bi-trash"></i>
                        '.__('locale.Delete').'</a>
                </div>
            </div>'
            ];
        });

        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [7];}
        return $this->respondSuccessDatatable($projects, 1, $projects->count(), $hidden_collumns);
    }
    public function detail(Request $request)
    {
        $project = Project::where('id', $request->id)->first();

        if(!$project){
            return $this->respondFail('An error occured!', 404);
        }

        $project->human_ownerness = $project->remaining_ownerness;
        $project->percentage_ownerness = $project->remaining_ownerness;

        if($project->labels){
            $labels = $project->labels->map(function($t){
                return $t->label_id;
            });
        }

        return $this->respondSuccess([
            'project' => $project,
            'labels' => $labels]);
    }

    public function labels(Request $request)
    {
        $labels = ProjectLabel::where('project_id', $request->id);

        if(!$labels){
            return $this->respondFail('An error occured!', 404);
        }

        return $this->respondSuccess($labels);
    }

    public function create(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'title' => 'required',
            'start_date' => 'required',
        ]);
        
        $create = Project::create([
            'client_id' => $request->client_id,
            'title' => $request->title,
            'used_ownerness' => 100,
            'start_date' => $request->start_date,
            'finish_date' => $request->finish_date
        ]);

        $profit_share = User::find($request->user()->id)->profit_share;

        $create_user = ProjectOwner::create([
            'project_id' => $create->id,
            'user_id' => $request->user()->id,
            'profit_share' => $profit_share,
            'percentage' => 100
        ]);

        if($create && $create_user){
            return $this->respondSuccess($create->id, 'Project created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'title' => 'required'
        ]);

        $project = Project::find($request->id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $project->client_id = $request->client_id;
        $project->title = $request->title;
        $project->status_id = $request->status_id;
        $project->start_date = $request->start_date;
        $project->finish_date = $request->finish_date;
        $update = $project->save();


        ProjectLabel::where('project_id', $project->id)->delete();

        if($request->labels && gettype($request->labels) == 'array'){
           
            foreach ($request->labels as $label_id) {
                ProjectLabel::create([
                    'project_id' => $project->id,
                    'label_id' => $label_id
                ]);
            }
        }

        if($update){
            return $this->respondSuccess($update, 'Project saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $project = Project::find($request->id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $project->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Project deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function change_status_preparing(Request $request)
    {
        $project = Project::find($request->id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        if($project->core_status == 1){
            return $this->respondFail('Projektas jau turi šį statusą!');
        }else if($project->core_status == 2){
            $projectRentals = ProjectRental::where('project_id', $project->id)->get();
            $rentals = Rental::all();
            foreach ($projectRentals as $projectRental) {
                $rental = $rentals->where('id', $projectRental->rental_id)->first();
                $rental->used_quantity -= $projectRental->quantity;
                $rental->save();
            }
        }

        $project->core_status = 1;
        $updated = $project->save();

        if($updated){
            return $this->respondSuccess($updated, 'Projekto statusas pakeistas!');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function change_status_ongoing(Request $request)
    {
        $project = Project::find($request->id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        if($project->core_status == 2){
            return $this->respondFail('Projektas jau turi šį statusą!');
        }

        DB::beginTransaction();

        // For all project rentals set used quantity to project rental quantity
        $projectRentals = ProjectRental::where('project_id', $project->id)->get();
        $rentals = Rental::all();
        foreach ($projectRentals as $projectRental) {
            $rental = $rentals->where('id', $projectRental->rental_id)->first();
            //If rental quantity is smaller than project rental quantity return error and roll back
            if($rental->remaining_quantity < $projectRental->quantity){
                DB::rollBack();
                return $this->respondFail('Sandelyje nėra pakankamai prekių!');
            }
            $rental->used_quantity += $projectRental->quantity;
            $rental->save();
        }

        $project->core_status = 2;
        $updated = $project->save();

        if($updated){
            DB::commit();
            return $this->respondSuccess($updated, 'Projekto statusas pakeistas!');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function change_status_completed(Request $request)
    {
        $project = Project::find($request->id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        if($project->core_status == 3){
            return $this->respondFail('Projektas jau turi šį statusą!');
        }else if($project->core_status == 2){
            $projectRentals = ProjectRental::where('project_id', $project->id)->get();
            $rentals = Rental::all();
            foreach ($projectRentals as $projectRental) {
                $rental = $rentals->where('id', $projectRental->rental_id)->first();
                $rental->used_quantity -= $projectRental->quantity;
                $rental->save();
            }
        }

        $project->core_status = 3;
        $updated = $project->save();

        if($updated){
            return $this->respondSuccess($updated, 'Projekto statusas pakeistas!');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
