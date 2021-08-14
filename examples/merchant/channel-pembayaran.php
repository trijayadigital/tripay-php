<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Merchant;

$environment = Environment::PRODUCTION;
$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';

$merchant = (new Merchant($environment))
    ->apiKey($apiKey);

    // Jika panggil, hanya akan ditampilkan data sesuai channel code saja
    // ->channelCode('BRIVA');

$channels = $merchant->channels();

print_r($channels);
