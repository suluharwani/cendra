<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public $serverKey = 'SB-Mid-server-YourServerKeyHere';
    public $clientKey = 'SB-Mid-client-YourClientKeyHere';
    public $isProduction = false;
    public $isSanitized = true;
    public $is3ds = true;
    public $merchantId = 'G123456789';
}