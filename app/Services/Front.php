<?php

namespace App\Services;

use GuzzleHttp\Client;

class Front
{
  private const RESTAURANTS_SEARCH_API_URL = 'https://tut-php-api.herokuapp.com/api';

  public function post()
  {
    $client = new Client();
    // $response = $client
    //   ->post(self::RESTAURANTS_SEARCH_API_URL, [
    //     'query' => [
    //       'keyid' => getenv('GURUNAVI_ACCESS_KEY'),
    //       'freeword' => str_replace(' ', ',', $word),
    //     ],
    //     'http_errors' => false,
    //   ]);
    $response = $client
      ->post(self::RESTAURANTS_SEARCH_API_URL, [
        'debug' => true,
        'json' => ['foo3' => 'bar3'],
        // 'http_errors' => false,
      ]);
    return json_decode($response->getBody(), true);
    // error_log($response->getBody());
  }
}
