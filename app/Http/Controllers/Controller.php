<?php

namespace App\Http\Controllers;

use App\Utils\COG;
use Carbon\Carbon;
use App\Utils\Icbc;
use App\Utils\Hikvision;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function test()
    {
        $icbcClient = new Icbc();
        echo $icbcClient->test();
        die;
        // 获取token，2小时内有效
        // echo 1;
        return view('play');
    }

     public function error()
     {
         // 获取token，2小时内有效
         // echo 1;
         return view('error/error');
     }

    public function list(int $page, int $size)
    {
        $hikvision = new Hikvision();
        return $hikvision->getEquipments($page, $size);
    }

    public function realPlay(int $simNo, int $channel)
    {
        $hikvision = new Hikvision();

        $url = $hikvision->getRealPlayUrl($simNo, $channel);
        // dd($url);
        return view('play', ['url' => $url]);
    }

    /**
     * @throws GuzzleException
     * @throws ValidationException
     */
    public function playback(Request $request, int $simNo, int $channel)
    {
        // 验证参数
        $validator = Validator::make($request->all(), [
            'endTime'   => 'required|date_format:YmdHis',
            'startTime' => 'required|date_format:YmdHis',
        ]);

        if ($validator->fails()) {
            return redirect('/error')
                ->withErrors($validator)
                ->withInput();
        }

        $startTime = Carbon::createFromFormat('YmdHis', $request->input('startTime'))->format('Y-m-d H:i:s');
        $endTime   = Carbon::createFromFormat('YmdHis', $request->input('endTime'))->format('Y-m-d H:i:s');

        $hikvision = new Hikvision();
        $url       = $hikvision->getPlaybackUrl($simNo, $channel, $startTime, $endTime);
        return view('play', ['url' => $url]);
    }

    public function createTask(int $phone, int $type = 1, string $userName = 'test')
    {
        $now      = now();
        $callTime = [
            [
                "dialTimeStart" => $now->format('Y-m-d'),
                "dialTimeEnd"   => $now->format('Y-m-d'),
                "startTime"     => $now->format('H:i:s'),
                "endTime"       => $now->addSeconds(100)->format('H:i:s'), // 100秒内
            ],
        ];
        $phoneList = [
            // 一次只有一个用户的场景
            [
                "用户名称" => $userName, // 必填字段
                "手机号码" => $phone, // 必填字段
                // "自定义字段１ " => "自定义数值1",
            ],
        ];
        $cog = new COG();
        return $cog->createTask(
            $now->format('YmdHis'),
            '平安穗粤烟感警报测试，收到请回复',
            "2132133670", // 暂时写死
            $callTime,
            $phoneList,
            1,
            $type,
            0
        );
    }

    public function getMessTemplate()
    {
        $cog = new COG();
        return $cog->getMessTemplate();
    }

    public function getFlowTemplate()
    {
        $cog = new COG();
        return $cog->getFlowTemplate();
    }

    public function getShowPhone()
    {
        $cog = new COG();
        return $cog->getShowPhone();
    }

    public function getTaskList(int $page = 1, int $pageSize = 10)
    {
        $cog = new COG();
        return $cog->getTaskList($page, $pageSize);
    }
}
