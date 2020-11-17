<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function restaurants(Request $request)
    {
        error_log("hello.....");
        return "success connect~";
    }
}
