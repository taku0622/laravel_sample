<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\json_encode;

class Watson
{
  public function watson($userId, $text)
  {
    $data = array('input' => array("text" => $text));
    #####################################################
    // 前回までの会話のデータがデータベースに保存されていれば
    if ($this->getLastConversationData($userId)) {
      $lastConversationData = $this->getLastConversationData($userId);

      // 前回までの会話のデータをパラメータに追加
      $data["context"] = array(
        "conversation_id" => $lastConversationData["conversation_id"],
        "system" => array(
          "dialog_stack" => array(array("dialog_node" => $lastConversationData["dialog_node"])),
          "dialog_turn_counter" => 1,
          "dialog_request_counter" => 1
        )
      );
    }

    #####################################################
    $data_json = json_encode($data, JSON_UNESCAPED_UNICODE);
    $headers = ['Content-Type' => 'application/json', 'Content-Length' => strlen($data_json)];
    $curlOpts = [
      CURLOPT_USERPWD        => 'apikey:' . getenv('WATSON_API_KEY'),
      CURLOPT_POSTFIELDS     => $data_json,
    ];
    // $guzzleClient = new Client(['base_uri' => 'https://gateway-fra.watsonplatform.net/assistant/api/v1/workspaces/']);
    $client = new Client(['base_uri' => 'https://api.us-south.assistant.watson.cloud.ibm.com/v1/workspaces/']);
    $path = getenv('WATSON_SKILL_ID') . '/message?version=2020-10-16';
    $response = $client->request('POST', $path, ['headers' => $headers, 'curl' => $curlOpts])->getBody()->getContents();
    error_log(json_encode($response, JSON_UNESCAPED_UNICODE));
    $json = json_decode($response, true);
    $conversationId = $json["context"]["conversation_id"];
    $dialogNode = $json["context"]["system"]["dialog_stack"][0]["dialog_node"];
    error_log($conversationId);
    error_log($dialogNode);

    // return $guzzleClient
    #####################################################
    // $client = new Client();
    // $response = $client
    //   ->post(self::WATSON_API_URL, [
    //     'query' => [
    //       'keyid' => getenv('GURUNAVI_ACCESS_KEY'),
    //       'freeword' => str_replace(' ', ',', $word),
    //     ],
    //     'http_errors' => false,
    //   ]);

    // return json_decode($response->getBody()->getContents(), true);
  }

  public function getLastConversationData($userId)
  {
    $data = DB::table('conversations')->where('userid', $userId)->get()->first();
    // $dbh = dbConnection::getConnection();
    // $sql = 'select conversation_id, dialog_node from ' . TABLE_NAME_CONVERSATIONS . ' where ? = pgp_sym_decrypt(userid, \'' . getenv('DB_ENCRYPT_PASS') . '\')';
    // $sth = $dbh->prepare($sql);
    // $sth->execute(array($userId));
    if (!$data) {
      return NULL;
    } else {
      return array('conversation_id' => $data->conversation_id, 'dialog_node' => $data->dialog_node);
    }
  }
}
