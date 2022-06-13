<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request){
        $settings=Setting::first();
        return view('welcome',['setting'=>$settings]);
    }

    public function changeLang(Request $request,$locale ='en'){
        Session()->put('locale', $locale);
        \App::setLocale($locale);
        return redirect()->back();
    }
}
