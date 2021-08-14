<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Transaction;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';
$reference = 'T00042MIWYR'; // Didapat setelah menjalankan request transaksi


$transaction = (new Transaction($environment))
    ->apiKey($apiKey)

    ->forClosedPayment();


$detail = $transaction->detail($reference);

print_r($detail);
