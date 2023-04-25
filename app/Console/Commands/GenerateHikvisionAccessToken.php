<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Utils\Hikvision;

class GenerateHikvisionAccessToken extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'g-hikvision-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从海康威视SDK平台获取Token';

    protected $host = 'http://183.56.220.198:8088/';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hikvision = new Hikvision();
        $token = $hikvision->getAccessToken();
        if ($token !== false) {
            $this->line($token);
        } else {
            $this->line('token获取失败');
        }
    }
}
