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
                case '新着':
                    $message = $this->newInfo($department);
                    break;
                case '重要':
                    $message = $this->importantInfo();
                    break;
                case 'イベント':
                    $message = $this->eventInfo();
                    break;
                case '質問':
                case '履修登録':
                case '証明書発行':
                case 'バス時刻表':
                    $watson = new Watson();
                    $message = $watson->watson($userId, $text);
                    break;
                default:
                    $message = $this->referenceInfo($text);
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
            // 時間の取得
            date_default_timezone_set('Asia/Tokyo');
            $today = date("Y-m-d H:i:s");
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

    public function newInfo($department)
    {
        $infomations = DB::table('informations')
            ->join('tags6', 'informations.id', '=', 'tags6.information_id')
            ->where($department, true)->whereNull('important')
            ->orderBy('posted_date', 'desc')->limit(5)->get();
        if ($infomations->isEmpty()) {
            $message = "新着情報はありません";
            // 時間の取得
            // date_default_timezone_set('Asia/Tokyo');
            // $today = date("Y-m-d H:i:s");
            // error_log($today);
        } else {
            $message = "";
            foreach ($infomations as $infomation) {
                $message .= $infomation->title . "　";
                $message .= $infomation->content . "　";
                $message .= "\n";
            }
        }
        return $message;
    }

    public function importantInfo()
    {
        $infomations = DB::table('informations')
            ->join('tags6', 'informations.id', '=', 'tags6.information_id')
            ->where('important', true)
            ->orderBy('posted_date', 'desc')->limit(10)->get();
        if ($infomations->isEmpty()) {
            $message = "重要情報はありません";
        } else {
            $message = "";
            foreach ($infomations as $infomation) {
                $message .= $infomation->title . "　";
                $message .= $infomation->content . "　";
                $message .= "\n";
            }
        }
        return $message;
    }

    public function eventInfo()
    {
        $eventInfomations = DB::table('event_informations')
            ->orderBy('posted_date', 'desc')->get();
        if ($eventInfomations->isEmpty()) {
            $message = "イベントはありません";
            // 時間の取得
            date_default_timezone_set('Asia/Tokyo');
            $today = date("Y-m-d H:i:s");
            error_log($today);
        } else {
            $message = "";
            foreach ($eventInfomations as $eventInfomation) {
                $title = mb_substr($eventInfomation->title, 0, 40);
                $message .= $title . "　";
                $content = mb_substr($eventInfomation->content, 0, 60);
                $message .= $content . "　";
                $message .= "\n";
            }
        }
        return $message;
    }

    public function referenceInfo($text)
    {
        $referenceInfomations = DB::table('reference_informations')
            ->where('lecture_name', $text)->get();
        if ($referenceInfomations->isEmpty()) {
            $message = "正しい講義名を入力してください";
            // 時間の取得
            date_default_timezone_set('Asia/Tokyo');
            $today = date("Y-m-d H:i:s");
            error_log($today);
        } else {
            error_log(count($referenceInfomations));
            $count = count($referenceInfomations);
            if ($count > 1) {
                $message = "講師の名前を入力してください";
            } else {
                $referenceInfomation = $referenceInfomations->first();
                error_log($referenceInfomation->reference_name);
                $message = "参考書は" . $referenceInfomation->reference_name . "です。";
            }
            // foreach ($eventInfomations as $eventInfomation) {
            //     $title = mb_substr($eventInfomation->title, 0, 40);
            //     $message .= $title . "　";
            //     $content = mb_substr($eventInfomation->content, 0, 60);
            //     $message .= $content . "　";
            //     $message .= "\n";
            // }
        }
        return $message;
    }
}
