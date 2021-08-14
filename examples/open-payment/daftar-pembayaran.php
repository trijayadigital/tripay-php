<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Transaction;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';
$uuid = 'T0001-OP1-7lXjI'; // Didapat setelah menjalankan request transaksi


$transaction = (new Transaction($environment))
    ->apiKey($apiKey)

    ->forOpenPayment();


$payments = $transaction->payments($uuid);

print_r($payments);
