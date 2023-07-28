<?php

namespace App\Http\Class;

use App\Models\wlyDevice;
use Illuminate\Support\Facades\DB;
use App\Models\wlyDeviceChangeLogs;
use Illuminate\Support\Facades\Log;

class WanLinYunClass
{
    public array $rule = [
        'deviceCode'   => 'required|string|min:1',
        'deviceType'   => 'required|string|size:3',
        'deviceStatus' => 'required|in:1,2,3',
        'netStatus'    => 'required|in:1,2,3',
        'time'         => 'required|int',
        'content'      => 'nullable|string',
        'detail'       => 'required|array',
    ];

    public function common(array $params)
    {
        return $this->handleAndSaveParams($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    private function handleAndSaveParams(array $params)
    {
        // 打印日志
        $json      = json_encode($params);
        $timestamp = now();
        Log::info("Array: {$json} | Timestamp: {$timestamp}");
        // 暂时直接返回
        return [];

        // // 获取参数值
        // $deviceCode = $params['deviceCode'];
        //
        // // 获取 detail 参数值
        // $params['time']    = date('Y-m-d H:i:s', $params['time']);
        // $params['content'] = $params['content'] ?? '';
        // $detail            = $params['detail'];
        // unset($params['detail'], $params['deviceCode']);
        //
        // $newParams = array_merge($detail, $params);
        //
        // // 插入数据库
        // return DB::transaction(function () use ($newParams, $deviceCode) {
        //     $data = WlyDevice::updateOrCreate(
        //         ['device_code' => $deviceCode],
        //         $newParams
        //     );
        //     WlyDeviceChangeLogs::create(array_merge($newParams, ['device_code' => $deviceCode]));
        //     return $data->toArray();
        // }, 3);
    }
}
