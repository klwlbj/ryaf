<?php

namespace App\Http\Controllers;

use App\Utils\Hikvision;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
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
        // 获取token，2小时内有效
        // echo 1;
        return view('play');
    }

    public function realPlay(int $simNo, int $channel)
    {
        $hikvision = new Hikvision();

        $url = $hikvision->getRealPlayUrl($simNo, $channel);
        // dd($url);
        return view('play', ['url' => $url]);

    }

    public function playback(Request $request, int $simNo, int $channel)
    {
        // 验证参数
        $this->validate($request, [
            'endTime'  => 'required|date_format:Y-m-d H:i:s',
            'statTime' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $hikvision = new Hikvision();
        $url = $hikvision->getRealPlayUrl($simNo, $channel);
        return view('play', ['url' => $url]);

    }
}
