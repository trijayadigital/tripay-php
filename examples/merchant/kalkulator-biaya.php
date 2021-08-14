<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Merchant;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';

$channelCode = 'BRIVA';
$amount = 100000;

$merchant = (new Merchant($environment))
    ->apiKey($apiKey)
    ->channelCode($channelCode);


$calculate = $merchant->calculate($amount);

print_r($calculate);
