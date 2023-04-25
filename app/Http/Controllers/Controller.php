<?php

namespace App\Http\Controllers;

use App\Utils\Hikvision;
use Carbon\Carbon;
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
            'endTime'  => 'required|date_format:YmdHis',
            'startTime' => 'required|date_format:YmdHis',
        ]);

        if ($validator->fails()) {
            return redirect('/error')
                ->withErrors($validator)
                ->withInput();
        }

        $startTime = Carbon::createFromFormat('YmdHis', $request->input('startTime'))->format('Y-m-d H:i:s');
        $endTime = Carbon::createFromFormat('YmdHis', $request->input('endTime'))->format('Y-m-d H:i:s');

        // $startTime = $request->input('startTime');
        // $endTime   = $request->input('endTime');

        $hikvision = new Hikvision();
        $url       = $hikvision->getPlaybackUrl($simNo, $channel, $startTime, $endTime);
        return view('play', ['url' => $url]);
    }
}
