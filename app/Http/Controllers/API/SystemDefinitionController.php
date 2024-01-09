<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\SystemDefinitions;
use Illuminate\Http\Request;

class SystemDefinitionController extends Controller
{
    use RespondTrait;

    public function list_filter(Request $request)
    {
        $definitions = SystemDefinitions::where('type', $request->type)->get()->map(function($definition){
            return [
                'id' => ($definition->is_custom == 1) ? $definition->value : $definition->id,
                'text' => $definition->title
            ];
        });

        if(!$definitions){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($definitions);
    }
}
