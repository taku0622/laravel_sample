<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function insertStudent(Request $request)
    {
        error_log("hello.....");
        error_log("request is" . $request->getContent());
        return "success connect~";
    }
}
