<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Stripe extends BaseConfig
{
    public $publishableKey = 'pk_test_YourPublishableKeyHere';
    public $secretKey = 'sk_test_YourSecretKeyHere';
    public $webhookSecret = 'whsec_YourWebhookSecretHere';
    public $apiVersion = '2023-10-16';
    public $currency = 'idr';
}