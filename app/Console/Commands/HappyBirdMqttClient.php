<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\Exceptions\ClientNotConnectedToBrokerException;

class HappyBirdMqttClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'happy-bird-mqtt-client:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a happy bird MQTT client';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('welcome to happy bird MQTT client!');
        try {
            $mqtt = MQTT::connection();
            $date = now();
            $mqtt->subscribe('lnsendout/ruyue_mqtt/+/+/alarm/#', function (string $topic, string $message)use($date) {
                // $this->info("Received message:{$date} {$message} on topic {$topic}");
            }, 1);

            $mqtt->subscribe('lnsendout/ruyue_mqtt/+/+/rtdata/#', function (string $topic, string $message) use($date) {
                // $this->info("Received message:{$date} {$message} on topic {$topic}");
                //
                // 接收参数
                // $data                                                                         = json_decode($message, true);
                // list($lnsendout, $customerName, $projectName, $deviceId, $dataType, $version) = explode('/', $topic);
                // //
                // $result = match ($lnsendout) {
                //     'lndata'    => $this->handleLndata($customerName, $projectName, $deviceId, $dataType, $version, $data),// 设备数据主动上报
                //     'lncmd'     => $this->handleLncmd($customerName, $projectName, $deviceId, $dataType, $version, $data),// 平台向设备下发命令
                //     'lnreport'  => $this->handleLnreport($customerName, $projectName, $deviceId, $dataType, $version, $data),// 设备接收到命令后反馈给平台的数据
                //     'insendout' => $this->handleLnsendout($customerName, $projectName, $deviceId, $dataType, $version, $data),// 数据转发
                //     default     => '',
                // };
                //
                // echo $result; // 输出
            }, 1);
            $mqtt->loop(true);// 不退出
        } catch (ClientNotConnectedToBrokerException $e) {
            $this->error('Failed to establish connection to MQTT server,error message:' . $e);
        }
    }

    protected function handleLndata($customerName, $projectName, $deviceId, $dataType, $version, array $data = [])
    {
        switch($dataType) {
            case "rtdata": // 实时数据
                break;
            case "alarm": // 报警数据
                break;
            case "heartbeat": // 心跳数据
                break;
            case "cmd": // 命令下行数据
                break;
            default:break;
        }
        return true;
    }
}
