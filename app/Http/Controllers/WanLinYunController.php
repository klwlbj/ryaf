<?php

namespace App\Http\Controllers;

use App\Utils\WanLinYun;
use Illuminate\Http\Request;
use App\Http\Class\WanLinYunClass;

class WanLinYunController extends BaseController
{
    public function common(Request $request)
    {
        $wanLinYun = new WanLinYunClass();
        $params    = $request->json()->all();
        $wanLinYun->common($params);
        echo "Inspection pass";
        die;

        return $this->response($res);
    }

    public function remoteControl($chipcode, $clientId, $runTime, $switchState)
    {
        $client = new WanLinYun();
        return $client->remoteControl($chipcode, $clientId, $runTime, $switchState);
    }
}
