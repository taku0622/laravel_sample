<?php

use App\Services\db;

require_once('db.php');

define('TABLE_NAME_CONVERSATIONS', 'conversations');

function watson($userId, $text)
{
  ###############################
  $data = array('input' => array("text" => $text));

  // 前回までの会話のデータがデータベースに保存されていれば
  if (getLastConversationData($userId) !== PDO::PARAM_NULL) {
    $lastConversationData = getLastConversationData($userId);

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

  // ConversationサービスのREST API
  // chcp 65001 # コマンドプロンプトをutf-8にする
  // curl -X POST -u "apikey:{apikey}" --header "Content-Type:application/json" --data "{\"input\": {\"text\": \"Hello\"}}" "{url}/v1/workspaces/{workspace_id}/message?version=2020-04-01"
  $url = 'https://api.us-south.assistant.watson.cloud.ibm.com/v1/workspaces/' . getenv('WATSON_SKILL_ID') . '/message?version=2020-10-16';
  // 新規セッションを初期化
  $curl = curl_init($url);

  // オプション
  $options = array(
    // コンテンツタイプ
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
    ),
    // 認証用
    CURLOPT_USERPWD => 'apikey:' . getenv('WATSON_API_KEY'),
    // POST
    CURLOPT_POST => true,
    // 内容
    CURLOPT_POSTFIELDS => json_encode($data),
    // curl_exec時にboolenでなく取得結果を返す
    CURLOPT_RETURNTRANSFER => true,
  );

  // オプションを適用
  curl_setopt_array($curl, $options);
  // セッションを実行し結果を取得
  $jsonString = curl_exec($curl);
  // 文字列を連想配列に変換
  $json = json_decode($jsonString, true);
  // error_log(print_r($json, true));
  // 会話データを取得
  $conversationId = $json["context"]["conversation_id"];
  $dialogNode = $json["context"]["system"]["dialog_stack"][0]["dialog_node"];

  // データベースに保存
  $conversationData = array('conversation_id' => $conversationId, 'dialog_node' => $dialogNode);
  setLastConversationData($userId, $conversationData);

  // Conversationからの返答を取得
  $outputText = $json['output']['text'][count($json['output']['text']) - 1];
  ###############################
  // $messages = [
  //   "type" => "text",
  //   "text" =>  $outputText
  // ]
  $messages = [
    "to" => [$userId],
    "type" => "text",
    "text" =>  $outputText
  ];
  return $messages;
}

# WATSON
// 会話データをデータベースに保存
function setLastConversationData($userId, $lastConversationData)
{
  $conversationId = $lastConversationData['conversation_id'];
  $dialogNode = $lastConversationData['dialog_node'];

  if (getLastConversationData($userId) === PDO::PARAM_NULL) {
    $dbh = dbConnection::getConnection();
    $sql = 'insert into ' . TABLE_NAME_CONVERSATIONS . ' (conversation_id, dialog_node, userid) values (?, ?, pgp_sym_encrypt(?, \'' . getenv('DB_ENCRYPT_PASS') . '\'))';
    $sth = $dbh->prepare($sql);
    $sth->execute(array($conversationId, $dialogNode, $userId));
  } else {
    $dbh = dbConnection::getConnection();
    $sql = 'update ' . TABLE_NAME_CONVERSATIONS . ' set conversation_id = ?, dialog_node = ? where ? = pgp_sym_decrypt(userid, \'' . getenv('DB_ENCRYPT_PASS') . '\')';
    $sth = $dbh->prepare($sql);
    $sth->execute(array($conversationId, $dialogNode, $userId));
  }
}

// データベースから会話データを取得
function getLastConversationData($userId)
{
  $dbh = dbConnection::getConnection();
  $sql = 'select conversation_id, dialog_node from ' . TABLE_NAME_CONVERSATIONS . ' where ? = pgp_sym_decrypt(userid, \'' . getenv('DB_ENCRYPT_PASS') . '\')';
  $sth = $dbh->prepare($sql);
  $sth->execute(array($userId));
  if (!($row = $sth->fetch())) {
    return PDO::PARAM_NULL;
  } else {
    return array('conversation_id' => $row['conversation_id'], 'dialog_node' => $row['dialog_node']);
  }
}
