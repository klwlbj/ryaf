<?php

namespace App\Utils;

use Error;
use GuzzleHttp\Client;

class Hikvision
{
    private const HOST = 'http://183.56.220.198:8088/';

    private string $token;
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * 获取Token
     * @return false|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken()
    {
        // 先从Redis中获取 todo

        $response = $this->client->request('GET', self::HOST . 'gci/api/token', [
            'query' => [
                "client_id"     => config('hikvision.client_id'),
                "client_secret" => config('hikvision.client_secret'),
                "grant_type"    => 'client_credentials',
                "scope"         => "video",
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            // 缓存至Redis，有效期5000秒，实际token是2小时内过期 todo
            $this->token = json_decode($response->getBody()->getContents(), true)['access_token'];
            return $this->token;
        }
        return false;
    }

    public function getEquipments(int $page, int $size)
    {
        if ($this->getAccessToken() !== false) {
            $headers  = ['Authorization' => 'Bearer ' . $this->token];
            $response = $this->client->request('GET', self::HOST . 'gci/api/video/basic/getDeviceList', [
                'query'   => [
                    "pageNo"   => $page,
                    "pageSize" => $size,
                ],
                'headers' => $headers,
            ]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true)['data'];
            }
        } else {
            throw new Error('no token');
        }
    }

    public function getEquipment($plateNo, $simNo)
    {
    }

    /**
     * 获取实时播放链接
     * @param string $simNo
     * @param int $channel
     * @param int $bitType
     * @param int $playerType
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRealPlayUrl(string $simNo, int $channel, int $bitType = 1, int $playerType = 1)
    {
        if ($this->getAccessToken() !== false) {
            $headers = [
                'Authorization' => 'Bearer ' . $this->token,
            ];

            $response = $this->client->request('POST', self::HOST . 'gci/api/video/RealPlay', [
                'json'    => [
                    "simNo"    => $simNo,
                    "channel"  => $channel,
                    "bitType"  => $bitType,
                    "playType" => $playerType,
                ],
                'headers' => $headers,
            ]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true)['data']['playH5Url'] ?? '';
            }
        }
        throw new Error('no token');
    }


    public function getPlaybackUrl(int $simNo, int $channel, string $startTime, string $endTime, int $bitType = 1, int $playerType = 1)
    {
        if ($this->getAccessToken() !== false) {
            $headers = [
                'Authorization' => 'Bearer ' . $this->token,
            ];

            $response = $this->client->request('POST', self::HOST . 'gci/api/video/PlayBack', [
                'json'    => [
                    "simNo"     => $simNo,
                    "channel"   => $channel,
                    "bitType"   => $bitType,
                    "playType"  => $playerType,
                    'startTime' => $startTime,
                    'endTime'   => $endTime,
                ],
                'headers' => $headers,
            ]);
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true)['data']['playH5Url'] ?? '';
            }
        }
        throw new Error('no token');
    }
}
