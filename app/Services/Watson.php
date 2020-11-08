<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;


class Watson
{
  private const RESTAURANTS_SEARCH_API_URL = 'https://api.gnavi.co.jp/RestSearchAPI/v3/';

  public function watson($userId, $text)
  {
    $data = array('input' => array("text" => $text));
    $client = new Client();
    $response = $client
      ->get(self::RESTAURANTS_SEARCH_API_URL, [
        'query' => [
          'keyid' => getenv('GURUNAVI_ACCESS_KEY'),
          'freeword' => str_replace(' ', ',', $word),
        ],
        'http_errors' => false,
      ]);

    return json_decode($response->getBody()->getContents(), true);
  }

  public // データベースから会話データを取得
  function getLastConversationData($userId)
  {
    $name = DB::table('conversations')->where('userid', $userId);
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
}
