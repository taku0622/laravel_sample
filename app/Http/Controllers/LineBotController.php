<?php

namespace App\Http\Controllers;

use App\Services\Gurunavi;

use Illuminate\Http\Request;

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class LineBotController extends Controller
{
    public function index()
    {
        return view('linebot.index');
    }

    public function restaurants(Request $request)
    {
        error_log("hello.....");

        $httpClient = new CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
        $lineBot = new LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

        $signature = $request->header('x-line-signature');
        if (!$lineBot->validateSignature($request->getContent(), $signature)) {
            abort(400, 'Invalid signature');
        }

        $events = $lineBot->parseEventRequest($request->getContent(), $signature);

        foreach ($events as $event) {
            if (!($event instanceof TextMessage)) {
                continue;
            }
            // eventを見る
            error_log(json_encode($event, JSON_UNESCAPED_UNICODE));
            error_log($event);

            $gurunavi = new Gurunavi();
            $gurunaviResponse = $gurunavi->searchRestaurants($event->getText());

            if (array_key_exists('error', $gurunaviResponse)) {
                $replyText = $gurunaviResponse['error'][0]['message'];
                $replyToken = $event->getReplyToken();
                $lineBot->replyText($replyToken, $replyText);
                continue;
            }

            $replyText = '';
            foreach ($gurunaviResponse['rest'] as $restaurant) {
                $replyText .=
                    $restaurant['name'] . "\n" .
                    $restaurant['url'] . "\n" .
                    "\n";
            }

            $replyToken = $event->getReplyToken();
            // error_log("replytext : " . $replyText);
            $lineBot->replyText($replyToken, $replyText);
        }
    }

    // public function post(Type $var = null)
    // {
    //     # code...
    // }
}
