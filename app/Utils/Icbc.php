<?php

namespace App\Utils;

use App\MyVendor\Icbc\CustomizeICBCPay;

class Icbc
{
    public function __construct()
    {
        $this->client = new CustomizeICBCPay();
    }

    public function test()
    {
        return $this->client->test();
    }
}
