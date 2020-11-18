<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
// use App\Services\Gurunavi;
use App\Services\Watson;
use Illuminate\Http\Request;

// use App\Models\Pet;
// use App\Models\Conversation;

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
            # データ取得
            $userId = $event->getUserId();
            $replyToken = $event->getReplyToken();
            $text = $event->getText();

            # 学生の呼び出し
            error_log("userid : " . $userId);
            $department = DB::table('students')->where('user_id', $userId)->value('department');
            error_log($department);

            # 今の名前を返信
            // $lineBot->replyText($replyToken, $department);
            switch ($text) {
                case '休講':
                    $message = $this->cancelInfo($department);
                    break;
                default:
                    $watson = new Watson();
                    $message = $watson->watson($userId, $text);
                    break;
            }
            $lineBot->replyText($replyToken, $message);
            ################################################################
        }
    }
    public function cancelInfo($department)
    {
        $cancelInfomations = DB::table('cancel_informations')->where('department', $department)->get();
        if ($cancelInfomations->isEmpty()) {
            $message = "あなたの学部の休講案内はありません";
            date_default_timezone_set('Asia/Tokyo');
            $today = date("Y-m-d");
            error_log($today);
        } else {
            $message = "";
            foreach ($cancelInfomations as $cancelInfomation) {
                $message .= $cancelInfomation->date . "　";
                $message .= $cancelInfomation->period . "　";
                $message .= $cancelInfomation->lecture_name . "\n";
                $message .= $cancelInfomation->department . "\n\n";
            }
        }
        return $message;
    }
}
