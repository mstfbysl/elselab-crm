<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    use RespondTrait;

    public function list_all()
    {
        $clients = Client::all()->map(function($client){
            return [
                'id' => $client->id,
                'text' => $client->name
            ];
        });

        if(!$clients){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($clients);
    }

    public function list_datatable()
    {
        $clients = Client::get()->map(function($client){
            return [
                $client->id,
                $client->name,
                $client->code,
                $client->phone,
                $client->email,
                $client->vat_number,
                '<a href="#" onclick="onEdit('.$client->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> 
                <a href="#" onclick="onDelete('.$client->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$clients){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($clients, 1, $clients->count());
    }

    public function detail(Request $request)
    {
        $client = Client::find($request->id);

        if(!$client){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($client);
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'name' => 'required',
            'address' => 'required'
        ]);
        
        $create = Client::create([
            'type' => $request->type,
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'vat_number' => $request->vat_number,
            'comment' => $request->comment
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Client created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'name' => 'required',
            'address' => 'required'
        ]);

        $client = Client::find($request->id);

        if(!$client){
            return $this->respondFail('An error occured!', 500);
        }

        $client->type = $request->type;
        $client->name = $request->name;
        $client->code = $request->code;
        $client->address = $request->address;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->vat_number = $request->vat_number;
        $client->comment = $request->comment;
        $update = $client->save();

        if($update){
            return $this->respondSuccess($update, 'Client saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $client = Client::find($request->id);

        if(!$client){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $client->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Client deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
