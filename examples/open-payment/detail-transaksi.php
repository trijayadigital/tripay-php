<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Transaction;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';
$uuid = 'T0004-OP2-OK0JP5'; // Didapat setelah menjalankan request transaksi


$transaction = (new Transaction($environment))
    ->apiKey($apiKey)

    ->forOpenPayment();


$detail = $transaction->detail($uuid);

print_r($detail);
