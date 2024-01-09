<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function swap($locale, Request $request){
        // available language in template array
        $availLocale=[
            'en'=>'en',
            'lt'=>'lt'
        ];
        // check for existing language
        if(array_key_exists($locale,$availLocale)){
            session()->put('locale',$locale);
            App::setLocale($locale);
            $request->user()->language = $locale;
            $request->user()->save();
        }
        return redirect()->back();
    }
}