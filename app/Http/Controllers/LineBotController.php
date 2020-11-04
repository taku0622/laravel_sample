<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ==========ここから追加==========
// use Illuminate\Support\Facades\Log;
// ==========ここまで追加==========

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LineBotController extends Controller
{
    public function index()
    {
        return view('linebot.index');
    }

    public function parrot(Request $request)
    {
        error_log($request->header());
        error_log($request->input();
        // Log::debug($request->header());
        // Log::debug($request->input());

        $httpClient = new CurlHTTPClient(env('LINE_ACCESS_TOKEN'));
        $lineBot = new LINEBot($httpClient, ['channelSecret' => env('LINE_CHANNEL_SECRET')]);

        $signature = $request->header('x-line-signature');
        if (!$lineBot->validateSignature($request->getContent(), $signature)) {
            abort(400, 'Invalid signature');
        }
    }
}
