<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Merchant;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';

$page = 1;
$per_page = 25;

$merchant = (new Merchant($environment))
    ->apiKey($apiKey);

$transactions = $merchant->transactions($page, $per_page);

print_r($transactions);
