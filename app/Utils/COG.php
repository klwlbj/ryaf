<?php

namespace App\Utils;

use ReflectionMethod;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class COG
{
    private const HOST = 'https://ai-notify.uniin.cn/notice/open/api/v2';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // 关闭 SSL 验证
        ]);
    }

    public function getSignAndTimeStamp(): array
    {
        $loginAccount = config('cog.account');
        $appSecret    = config('cog.password');
        $timeStamp    = intval(microtime(true) * 1000);

        $basicParam = $loginAccount . '&' . $appSecret . '&' . $timeStamp;
        return [md5($basicParam), $timeStamp];
    }

    /**
     * 获取短信模板
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMessTemplate(): array
    {
        list($sign, $timeStamp) = $this->getSignAndTimeStamp();
        $headers                = [
            'userAccount' => config('cog.account'),
            'signType'    => 'md5',
            'sign'        => $sign,
            'timestamp'   => $timeStamp,
        ];
        sleep(1);
        $response = $this->client->request('POST', self::HOST . '/getMessTemplate', [
            'headers' => $headers,
        ]);
        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }
        return [];
    }

    /**
     * 获取话术流程模板(即业务场景)
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFlowTemplate(): array
    {
        list($sign, $timeStamp) = $this->getSignAndTimeStamp();
        $headers                = [
            'userAccount' => config('cog.account'),
            'signType'    => 'md5',
            'sign'        => $sign,
            'timestamp'   => $timeStamp,
        ];
        sleep(1);

        $response = $this->client->request('POST', self::HOST . '/getFlowTemplate', [
            'headers' => $headers,
        ]);
        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }
        return [];

    }

    /**
     * 获取外显号码数据
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
     public function getShowPhone(): array
     {
         list($sign, $timeStamp) = $this->getSignAndTimeStamp();
         $headers                = [
             'userAccount' => config('cog.account'),
             'signType'    => 'md5',
             'sign'        => $sign,
             'timestamp'   => $timeStamp,
         ];
         sleep(1);

         $response = $this->client->request('POST', self::HOST . '/getShowPhone', [
             'headers' => $headers,
         ]);
         if ($response->getStatusCode() === 200) {
             return json_decode($response->getBody()->getContents(), true);
         }
         return [];
     }

    /**
     * 创建任务
     * @param string $name
     * @param string $content
     * @param string $showPhone
     * @param array $callTime
     * @param array $phoneList
     * @param int $phoneListType
     * @param int $type
     * @param int $dialSetting
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \ReflectionException
     */
     public function createTask(
         string $name,
         string $content,
         string $showPhone,
         array $callTime,
         array $phoneList,
         int $phoneListType,
         int $type,
         int $dialSetting,
     ) {
         // 验证方法入参
         $rules = [
             'name'          => 'required|string|max:15',
             'type'          => 'required|int|between:0,2',
             'content'       => 'required|string',
             'showPhone'     => 'required|string',
             'dialSetting'   => 'required|int',
             'callTime'      => 'required|array',
             'phoneListType' => 'required|int|between:0,1',
             'phoneList'     => 'required|array',
         ];
         try {
             $this->validateParams(debug_backtrace()[0]['args'], __FUNCTION__, $rules);
         } catch (ValidationException $e) {
             dump($e->getMessage());
         }

         list($sign, $timeStamp) = $this->getSignAndTimeStamp();
         $headers                = [
             'userAccount' => config('cog.account'),
             'signType'    => 'md5',
             'sign'        => $sign,
             'timestamp'   => $timeStamp,
         ];
         sleep(1);

         $response = $this->client->request('POST', self::HOST . '/createTask', [
             'multipart' => [
                 [
                     'name'     => 'name',
                     'contents' => $name,
                 ],
                 [
                     'name'     => 'type',
                     'contents' => $type,
                 ],
                 [
                     'name'     => 'content',
                     'contents' => $content,
                 ],
                 [
                     'name'     => 'showPhone',
                     'contents' => $showPhone,
                 ],
                 [
                     'name'     => 'dialSetting',
                     'contents' => $dialSetting,
                 ],
                 [
                     'name'     => 'callTime',
                     'contents' => json_encode($callTime),
                 ],
                 [
                     'name'     => 'phoneListType',
                     'contents' => $phoneListType,
                 ],
                 [
                     'name'     => 'phoneList',
                     'contents' => json_encode($phoneList),
                 ],

                 [
                     'name'     => 'redialRange',
                     'contents' => 0,
                 ],
                 [
                     'name'     => 'redialTimes',
                     'contents' => 0,
                 ],
                 [
                     'name'     => 'msgTemplateId',
                     'contents' => 1087874, // 暂时写死
                 ],
             ],
             'headers'   => $headers,
         ]);
         if ($response->getStatusCode() === 200) {
             return json_decode($response->getBody()->getContents(), true);
         }

         return [];
     }

    /**
     * @param array $args
     * @param string $functionName
     * @param array $rules
     * @return array
     * @throws ValidationException
     * @throws \ReflectionException
     */
     private function validateParams(array $args, string $functionName, array $rules)
     {
         $reflection = new ReflectionMethod($this, $functionName);
         $params     = $reflection->getParameters();

         $result = [];
         foreach ($params as $key => $param) {
             $name          = $param->getName();
             $value         = $args[$key] ?? null;
             $result[$name] = $value;
         }
         $validator = Validator::make($result, $rules);

         if ($validator->fails()) {
             throw new ValidationException($validator);
         }

         return $result;
     }

    /**
     * @param int $page
     * @param int $pageSize
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTaskList(int $page, int $pageSize): array
    {
        list($sign, $timeStamp) = $this->getSignAndTimeStamp();
        $headers                = [
            'userAccount' => config('cog.account'),
            'signType'    => 'md5',
            'sign'        => $sign,
            'timestamp'   => $timeStamp,
        ];
        sleep(1);

        $response = $this->client->request('POST', self::HOST . '/searchTask', [
            'headers' => $headers,
            'json' => [
                'page' => $page,
                'pageSize' => $pageSize,
            ],

        ]);
        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }
        return [];
    }
}
