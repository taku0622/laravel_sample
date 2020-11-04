<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ==========ここから追加==========
// use Illuminate\Support\Facades\Log;
// ==========ここまで追加==========

class LineBotController extends Controller
{
    public function index()
    {
        return view('linebot.index');
    }

    public function parrot(Request $request)
    {
        error_log("hello");
        // Log::debug($request->header());
        // Log::debug($request->input());
    }
}
