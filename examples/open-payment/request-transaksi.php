<?php

require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Transaction;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';
$privateKey = 'uyPNa-5VDDJ-jPoNo-YxnJE-2caD9';

$merchantCode = 'T0004';
$merchantRef = 'TRX-123456'; // Isi sembarang
$channelCode = 'BRIVAOP';

$customerName = 'Asep Balon';

$transaction = (new Transaction($environment))
    ->apiKey($apiKey)
    ->privateKey($privateKey)
    ->merchantCode($merchantCode)
    ->channelCode($channelCode)
    ->merchantRef($merchantRef)
    ->customerName($customerName)

    ->forOpenPayment();

$response = $transaction->process();

print_r($response);
