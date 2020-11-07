<?php

namespace App\Services;

use GuzzleHttp\Client;

class CallWatsonAssistant
{
  /**
   * Watson Assistantを呼び出すモジュール
   *
   * @param string $spokenWord ユーザーが入力した文字列
   * @param array $context watson assistantのcontextデータ
   * @return json Watson AssistantをCallした結果
   */
  public function call(string $spokenWord, array $context)
  {
    if (count($context) > 0) {
      //以前から継続されている会話の場合
      $requestData  = json_encode(['input' => ['text' => $spokenWord], 'context' => $context]);
    } else {
      $requestData  = json_encode(['input' => ['text' => $spokenWord]]);
    }
    $headers = ['Content-Type' => 'application/json', 'Content-Length' => strlen($requestData)];
    $curlOpts = [
      CURLOPT_USERPWD        => 'apikey' . ':' . getenv('WATSON_API_KEY'),
      CURLOPT_POSTFIELDS     => $requestData,
    ];
    $guzzleClient = new Client(['base_uri' => 'https://api.us-south.assistant.watson.cloud.ibm.com/v1/workspaces/']);
    $path         = getenv('WATSON_SKILL_ID') . '/message?version=2020-10-16';
    return $guzzleClient->request('POST', $path, ['headers' => $headers, 'curl' => $curlOpts])->getBody()->getContents();
  }
}
