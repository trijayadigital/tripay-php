<?php

namespace Tripay;

class Merchant extends Base
{
    /**
     * Konstruktor.
     *
     * @param int $environment
     */
    public function __construct($environment)
    {
        parent::__construct($environment);
    }

    /**
     * Mengambil instruksi pembayaran dari masing-masing channel.
     *
     * @return \stdClass
     */
    public function instructions()
    {
        $payloads = [
            'code' => $this->channelCode,
        ];

        return $this->request('get', 'payment/instruction', $payloads);
    }

    /**
     * Mendapatkan daftar channel pembayaran yang aktif pada akun merchant anda
     * beserta informasi lengkap termasuk biaya transaksi dari masing-masing channel.
     *
     * @return \stdClass
     */
    public function channels()
    {
        $payloads = [
            'code' => $this->channelCode,
        ];

        return $this->request('get', 'merchant/payment-channel', $payloads);
    }

    /**
     * Mendapatkan rincian perhitungan biaya transaksi untuk masing-masing channel
     * berdasarkan nominal yang ditentukan.
     *
     * @param int $price
     *
     * @return \stdClass
     */
    public function calculate($price)
    {
        $payloads = [
            'code' => $this->channelCode,
            'amount' => (int) $price,
        ];

        return $this->request('get', 'merchant/fee-calculator', $payloads);
    }

    /**
     * Mendapatkan daftar transaksi merchant.
     *
     * @param int $page
     * @param int $per_page
     *
     * @return \stdClass
     */
    public function transactions($page = 1, $per_page = 25)
    {
        $payloads = [
            'code' => $this->channelCode,
            'page' => (int) $page,
            'per_page' => (int) $per_page,
        ];

        return $this->request('get', 'merchant/transactions', $payloads);
    }
}
