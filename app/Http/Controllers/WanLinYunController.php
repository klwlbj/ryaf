<?php

namespace App\Http\Controllers;

use App\Utils\WanLinYun;
use Illuminate\Http\Request;
use App\Http\Class\WanLinYunClass;

class WanLinYunController extends BaseController
{
    public array $rule = [
        'deviceCode'   => 'required|string|min:1',
        'deviceType'   => 'required|string|in:603',
        'deviceStatus' => 'required|in:1,2,3',
        'netStatus'    => 'required|in:1,2,3',
        'time'         => 'required|timestamp',
        'content'      => 'nullable|string',
        'detail'       => 'required|array',
    ];

    public function heartbeat(Request $request)
    {
        $wanLinYun = new WanLinYunClass();
        $params    = $request->json()->all();
        $res       = $wanLinYun->heartbeat($params);
        return $this->response($res);
    }

    public function event(Request $request)
    {
        $wanLinYun = new WanLinYunClass();
        $params    = $request->json()->all();
        $res       = $wanLinYun->event($params);
        return $this->response($res);
    }

    public function offline(Request $request)
    {
        $wanLinYun = new WanLinYunClass();
        $params    = $request->json()->all();
        $res       = $wanLinYun->offline($params);
        return $this->response($res);
    }

    public function iccid(Request $request)
    {
        $wanLinYun = new WanLinYunClass();
        $params    = $request->json()->all();
        $res       = $wanLinYun->iccid($params);
        return $this->response($res);
    }

    public function remoteControl(Request $request){
        $client = new WanLinYun();
        $json    = $request->json()->all();
        return $client->remoteControl($json);
    }
}
