<?php
require '../../autoload.php';

use Tripay\Constants\Environment;
use Tripay\Transaction;

$environment = Environment::PRODUCTION;

$apiKey = 'CzAxUSKPk7e5vWxhxz7GpXjHVwlFT2VtslWlxuke';
$privateKey = 'uyPNa-5VDDJ-jPoNo-YxnJE-2caD9';

$merchantCode = 'T0004';
$merchantRef = 'TRX-123456'; // Isi sembarang
$channelCode = 'BRIVA';

$minutes = 1440; // Waktu kedaluwarsa invoice (dalam menit, default 1440 = 24 jam);

$customerName = 'Asep Balon';
$customerEmail = 'asep.balon@gmail.com';
$customerPhone = '0812345667890';




$transaction = (new Transaction($environment))
    ->apiKey($apiKey)
    ->privateKey($privateKey)
    ->merchantCode($merchantCode)
    ->merchantRef($merchantRef)
    ->channelCode($channelCode)

    ->expiresAfter($minutes)

    ->customerName($customerName)
    ->customerEmail($customerEmail)
    ->customerPhone($customerPhone)

    // ->addItem('Nama Produk', 'Harga Satuan', 'Jumlah', 'Kode SKU')
    ->addItem('Nama Produk 1', 100000, 2, 'SKU-PRODUK-1')
    ->addItem('Nama Produk 2', 100000, 6, 'SKU-PRODUK-2')
    ->addItem('Nama Produk 3', 100000, 3, 'SKU-PRODUK-3')
    ->addItem('Nama Produk 4', 100000, 1, 'SKU-PRODUK-4')

    ->forClosedPayment();


$response = $transaction->process();

print_r($response);
