<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Services\Gurunavi;
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
            // eventを見る
            // error_log(json_encode($event, JSON_UNESCAPED_UNICODE));
            // error_log($event);
            ###########################################################################
            // $gurunavi = new Gurunavi();
            // $gurunaviResponse = $gurunavi->searchRestaurants($event->getText());
            // if (array_key_exists('error', $gurunaviResponse)) {
            //     $replyText = $gurunaviResponse['error'][0]['message'];
            //     $replyToken = $event->getReplyToken();
            //     $lineBot->replyText($replyToken, $replyText);
            //     continue;
            // }
            // $replyText = '';
            // foreach ($gurunaviResponse['rest'] as $restaurant) {
            //     $replyText .=
            //         $restaurant['name'] . "\n" .
            //         $restaurant['url'] . "\n" .
            //         "\n";
            // }
            // $replyToken = $event->getReplyToken();
            // $lineBot->replyText($replyToken, $replyText);
            #############################################################
            // $userId = $event->getUserId();
            // $text = $event->getText();
            // $replyText = $this->watson($userId, $text);
            // $replyToken = $event->getReplyToken();
            // error_log("replytext : " . $replyText);
            // error_log("replytoken: " . $replyToken);
            // error_log("userId: " . $userId);
            // error_log("text: " . $text);
            // $this->postToApp($userId, $replyToken, $text);
            // $lineBot->replyText($replyToken, $replyText);
            // $lineBot->replyText($replyToken, $replyText);
            #############################################################
            // # データ取得
            // $userId = $event->getUserId();
            // $replyToken = $event->getReplyToken();
            // $text = $event->getText();


            // # ペットの呼び出し
            // $name = DB::table('pets')->where('name', $text)->value('id');
            // error_log($name);

            // $name = DB::table('pets');
            // error_log("1" . json_encode($name, JSON_UNESCAPED_UNICODE));
            // $name = DB::table('pets')->where('name', "こ");
            // error_log("2" . json_encode($name, JSON_UNESCAPED_UNICODE));
            // $name = DB::table('pets')->where('name', "こ")->value('id');
            // error_log("3" . json_encode($name, JSON_UNESCAPED_UNICODE));

            // # 今の名前を返信
            // $lineBot->replyText($replyToken, $name);

            # 名前の変更
            // $name = DB::table('pets')->where('id', 1)->update(['name' => $text]);

            // $afname = DB::table('pets')->where('id', 1)->value('name');
            // $message = "名前を変更しました\n" . $afname;
            // $lineBot->pushMessage($userId, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message));
            #############################################################
            # データ取得
            // $userId = $event->getUserId();
            // $replyToken = $event->getReplyToken();
            // $text = $event->getText();

            // $pet = new dbConnection();
            // $Response = $pet->searchPet($text);
            // $lineBot->replyText($replyToken, $Response);
            #############################################################
            // # データ取得
            // $userId = $event->getUserId();
            // $replyToken = $event->getReplyToken();
            // $text = $event->getText();
            // if ($text == "質問") {
            //     $watson = new Watson();
            //     $Response = $watson->watson($userId, $text);
            // } else {
            //     $Response = "元気出して";
            // }
            // // $watson = new Watson();
            // // $Response = $watson->watson($userId, $text);
            // $lineBot->replyText($replyToken, $Response);
            // #############################################################
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
                    $message = $department;
                    break;
            }
            $lineBot->replyText($replyToken, $message);
            ################################################################
        }
    }
    public function cancelInfo($department)
    {
        $cancelInfomations = DB::table('cancel_informations')->where('department', $department)->get();
        if ($cancelInfomations == NULL) {
            $message = "あなたの学部の休講案内はありません";
        } else {
            foreach ($cancelInfomations as $cancelInfomation) {
                error_log($cancelInfomation->date);
                error_log($cancelInfomation->period);
                error_log($cancelInfomation->lecture_name);
                error_log($cancelInfomation->department);
                error_log('https://service.cloud.teu.ac.jp/inside2/hachiouji/hachioji_common/cancel/');
                error_log('詳細');
            }
        }
        return $message;
    }

    // public function watson($userId, $text)
    // {
    //     define('TABLE_NAME_CONVERSATIONS', 'conversations');
    //     ###############################
    //     $data = array('input' => array("text" => $text));

    //     // 前回までの会話のデータがデータベースに保存されていれば
    //     if ($this->getLastConversationData($userId) !== PDO::PARAM_NULL) {
    //         $lastConversationData = $this->getLastConversationData($userId);

    //         // 前回までの会話のデータをパラメータに追加
    //         $data["context"] = array(
    //             "conversation_id" => $lastConversationData["conversation_id"],
    //             "system" => array(
    //                 "dialog_stack" => array(array("dialog_node" => $lastConversationData["dialog_node"])),
    //                 "dialog_turn_counter" => 1,
    //                 "dialog_request_counter" => 1
    //             )
    //         );
    //     }

    //     // ConversationサービスのREST API
    //     // chcp 65001 # コマンドプロンプトをutf-8にする
    //     // curl -X POST -u "apikey:{apikey}" --header "Content-Type:application/json" --data "{\"input\": {\"text\": \"Hello\"}}" "{url}/v1/workspaces/{workspace_id}/message?version=2020-04-01"
    //     $url = 'https://api.us-south.assistant.watson.cloud.ibm.com/v1/workspaces/' . getenv('WATSON_SKILL_ID') . '/message?version=2020-10-16';
    //     // 新規セッションを初期化
    //     $curl = curl_init($url);

    //     // オプション
    //     $options = array(
    //         // コンテンツタイプ
    //         CURLOPT_HTTPHEADER => array(
    //             'Content-Type: application/json',
    //         ),
    //         // 認証用
    //         CURLOPT_USERPWD => 'apikey:' . getenv('WATSON_API_KEY'),
    //         // POST
    //         CURLOPT_POST => true,
    //         // 内容
    //         CURLOPT_POSTFIELDS => json_encode($data),
    //         // curl_exec時にboolenでなく取得結果を返す
    //         CURLOPT_RETURNTRANSFER => true,
    //     );

    //     // オプションを適用
    //     curl_setopt_array($curl, $options);
    //     // セッションを実行し結果を取得
    //     $jsonString = curl_exec($curl);
    //     // 文字列を連想配列に変換
    //     $json = json_decode($jsonString, true);
    //     // error_log(print_r($json, true));
    //     // 会話データを取得
    //     $conversationId = $json["context"]["conversation_id"];
    //     $dialogNode = $json["context"]["system"]["dialog_stack"][0]["dialog_node"];

    //     // データベースに保存
    //     $conversationData = array('conversation_id' => $conversationId, 'dialog_node' => $dialogNode);
    //     $this->setLastConversationData($userId, $conversationData);

    //     // Conversationからの返答を取得
    //     $outputText = $json['output']['text'][count($json['output']['text']) - 1];
    //     ###############################
    //     // $messages = [
    //     //   "type" => "text",
    //     //   "text" =>  $outputText
    //     // ]
    //     // $messages = [
    //     //     "to" => [$userId],
    //     //     "type" => "text",
    //     //     "text" =>  $outputText
    //     // ];
    //     return $outputText;
    // }

    // # WATSON
    // // 会話データをデータベースに保存
    // function setLastConversationData($userId, $lastConversationData)
    // {
    //     $conversationId = $lastConversationData['conversation_id'];
    //     $dialogNode = $lastConversationData['dialog_node'];

    //     if ($this->getLastConversationData($userId) === PDO::PARAM_NULL) {
    //         $dbh = new dbConnection();
    //         $dbh = dbConnection::getConnection();
    //         $sql = 'insert into ' . TABLE_NAME_CONVERSATIONS . ' (conversation_id, dialog_node, userid) values (?, ?, pgp_sym_encrypt(?, \'' . getenv('DB_ENCRYPT_PASS') . '\'))';
    //         $sth = $dbh->prepare($sql);
    //         $sth->execute(array($conversationId, $dialogNode, $userId));
    //     } else {
    //         $dbh = new dbConnection();
    //         $dbh = dbConnection::getConnection();
    //         $sql = 'update ' . TABLE_NAME_CONVERSATIONS . ' set conversation_id = ?, dialog_node = ? where ? = pgp_sym_decrypt(userid, \'' . getenv('DB_ENCRYPT_PASS') . '\')';
    //         $sth = $dbh->prepare($sql);
    //         $sth->execute(array($conversationId, $dialogNode, $userId));
    //     }
    // }

    // // データベースから会話データを取得
    // function getLastConversationData($userId)
    // {
    //     $dbh = new dbConnection();
    //     $dbh = dbConnection::getConnection();
    //     $sql = 'select conversation_id, dialog_node from ' . TABLE_NAME_CONVERSATIONS . ' where ? = pgp_sym_decrypt(userid, \'' . getenv('DB_ENCRYPT_PASS') . '\')';
    //     $sth = $dbh->prepare($sql);
    //     $sth->execute(array($userId));
    //     if (!($row = $sth->fetch())) {
    //         return PDO::PARAM_NULL;
    //     } else {
    //         return array('conversation_id' => $row['conversation_id'], 'dialog_node' => $row['dialog_node']);
    //     }
    // }
}
