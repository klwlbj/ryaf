<?php

namespace App\Utils;

use GuzzleHttp\Client;

class WanLinYun
{
    public const HOST = 'http://api.wanlinyun.com/index.php';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }
    public function remoteControl($json)
    {
        $response = $this->client->request('POST', self::HOST . '?act=Api&method=three_power_set', [
            'json' => $json,
        ]);
        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        }
        return false;
    }
}
