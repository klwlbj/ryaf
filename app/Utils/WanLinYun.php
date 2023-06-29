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
    public function remoteControl($chipcode, $clientId, $runTime, $switchState)
    {
        $response = $this->client->request('POST', self::HOST . '?act=Api&method=three_power_set', [
            'json' => [
                "token"     => ENV('WLY_TOKEN'),
                "user_name" => ENV('WLY_USERNAME'),
                "setdata"   => [
                    "chipcode"     => $chipcode,
                    "client_id"    => $clientId,
                    "run_time"     => "00",
                    "switch_state" => "01",
                    "loop"         => "01",
                ],
            ],
        ]);
        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        }
        return false;
    }
}
