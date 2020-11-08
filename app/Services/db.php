<?php
// heroku pg:psql --app sleepy-hamlet-66660
// create extension pgcrypto;
// \dx
// create table conversations(userid bytea, conversation_id text, dialog_node text);
// \dt
namespace App\Services;

use Illuminate\Support\Facades\DB;

// データベースへの接続を管理するクラス
class dbConnection
{
  public function searchPet(string $word): string
  {
    // # ペットの呼び出し
    $name = DB::table('pets')->where('name', $word)->value('id');
    $n = DB::table('pets')->where('name', "こ");
    error_log(gettype($n));
    error_log(json_encode($n, JSON_UNESCAPED_UNICODE));
    error_log($n);
    error_log($name);
    if (!$name) {
      $name = "not data";
    }
    return $name;
    // $client = new Client();
    // $response = $client
    //   ->get(self::RESTAURANTS_SEARCH_API_URL, [
    //     'query' => [
    //       'keyid' => getenv('GURUNAVI_ACCESS_KEY'),
    //       'freeword' => str_replace(' ', ',', $word),
    //     ],
    //     'http_errors' => false,
    //   ]);

    // return json_decode($response->getBody()->getContents(), true);
  }
  // // インスタンス
  // protected static $db;
  // // コンストラクタ
  // // private function __construct()
  // function __construct()
  // {

  //   try {
  //     // 環境変数からデータベースへの接続情報を取得し
  //     $url = parse_url(getenv('DATABASE_URL'));
  //     // データソース
  //     $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
  //     // 接続を確立
  //     self::$db = new PDO($dsn, $url['user'], $url['pass']);
  //     // エラー時例外を投げるように設定
  //     self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //   } catch (PDOException $e) {
  //     error_log('Connection Error: ' . $e->getMessage());
  //   }
  // }

  // // シングルトン。存在しない場合のみインスタンス化
  // public static function getConnection()
  // {
  //   if (!self::$db) {
  //     new dbConnection();
  //   }
  //   return self::$db;
  // }
}
