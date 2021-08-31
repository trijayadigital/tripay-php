<?php

namespace Tripay;

class Transaction extends Base
{
    private $transactionType;
    private $returnUrl;

    private $customerName;
    private $customerEmail;
    private $customerPhone;

    private $expiresAfter;
    private $items = [];

    /**
     * Konstruktor.
     *
     * @param int $environment
     */
    public function __construct($environment)
    {
        // default expiry: 24 hours.
        $this->expiresAfter(1440);

        parent::__construct($environment);
    }

    /**
     * Set operasi untuk open payment.
     *
     * @return Transaction
     */
    public function forOpenPayment()
    {
        $this->transactionType = 'open';

        return $this;
    }

    /**
     * Set operasi untuk closed payment.
     *
     * @return Transaction
     */
    public function forClosedPayment()
    {
        $this->transactionType = 'closed';

        return $this;
    }

    /**
     * Tambahakan item ke list request.
     *
     * @param string $name
     * @param int    $price
     * @param int    $quantity
     * @param string $sku
     *
     * @return Transaction
     */
    public function addItem($name, $price, $quantity, $sku = null)
    {
        $this->items[] = [
            'sku' => (string) $sku,
            'name' => (string) $name,
            'price' => (int) $price,
            'quantity' => (int) $quantity,
        ];

        return $this;
    }

    /**
     * Set nama customer.
     *
     * @param string $name
     *
     * @return Transaction
     */
    public function customerName($name)
    {
        $this->customerName = (string) $name;

        return $this;
    }



    /**
     * Set email customer.
     *
     * @param string $email
     *
     * @return Transaction
     */
    public function customerEmail($email)
    {
        $this->customerEmail = (string) $email;

        return $this;
    }

    /**
     * Set nomor telepon customer.
     *
     * @param string $phone
     *
     * @return Transaction
     */
    public function customerPhone($phone)
    {
        $this->customerPhone = (string) $phone;

        return $this;
    }

    /**
     * Set return URL.
     *
     * @param string $url
     *
     * @return Transaction
     */
    public function returnUrl($url)
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exceptions\InvalidRedirectException(sprintf('Invalid URL fornat: %s', $url));
        }

        $this->returnUrl = $url;

        return $this;
    }

    /**
     * Set waktu kedaluwarsa invoice (dalam menit).
     *
     * @param int $minutes
     *
     * @return Transaction
     */
    public function expiresAfter($minutes)
    {
        $this->expiresAfter = time() + ((int) $minutes * 60);

        return $this;
    }

    /**
     * Proses data transaksi.
     *
     * @return \stdClass
     */
    public function process()
    {
        switch ($this->transactionType) {
            case 'open':
                $signature = hash_hmac(
                    'sha256',
                    $this->merchantCode.$this->channelCode.$this->merchantRef,
                    $this->privateKey
                );

                $payloads = [
                    'method' => $this->channelCode,
                    'merchant_ref' => $this->merchantRef,
                    'customer_name' => $this->customerName,
                    'signature' => $signature,
                ];

                return $this->request('post', 'open-payment/create', $payloads);

            case 'closed':
                $amount = 0;

                for ($i = 0; $i < count($this->items); $i++) {
                    $amount += (int) $this->items[$i]['price'] * (int) $this->items[$i]['quantity'];
                }

                $signature = hash_hmac(
                    'sha256',
                    $this->merchantCode.$this->merchantRef.$amount,
                    $this->privateKey
                );

                $payloads = [
                    'method' => $this->channelCode,
                    'merchant_ref' => $this->merchantRef,
                    'amount' => $amount,
                    'customer_name' => $this->customerName,
                    'customer_email' => $this->customerEmail,
                    'customer_phone' => $this->customerPhone,
                    'order_items' => $this->items,
                    'return_url' => $this->returnUrl,
                    'expired_time' => $this->expiresAfter,
                    'signature' => $signature,
                ];

                return $this->request('post', 'transaction/create', $payloads);

            default:
                throw new Exceptions\InvalidTransactionTypeException(sprintf(
                    'Only OPEN and CLOSED transaction are supported, got: %s',
                    $this->transactionType
                ));
        }
    }


    public function detail($uuid)
    {
        if (! is_string($uuid) || strlen(trim($uuid)) <= 0) {
            throw new Exceptions\InvalidTransactionUuidException('Transaction UUID should be a non empty string.');
        }

        switch ($this->transactionType) {
            case 'open':
                $payloads = [];
                return $this->request('get', 'open-payment/'.$uuid.'/detail', $payloads);

            case 'closed':
                $payloads = ['reference' => $uuid]; // TODO: apakah reference sama dengan uuid?
                return $this->request('get', 'transaction/detail', $payloads);

            default: throw new Exceptions\InvalidTransactionTypeException(sprintf(
                'Only OPEN and CLOSED transaction types are supported, got: %s',
                $this->transactionType
            ));

        }
    }


    public function payments($uuid)
    {
        if (! is_string($uuid) || strlen(trim($uuid)) <= 0) {
            throw new Exceptions\InvalidTransactionUuidException('Transaction UUID should be a non-empty string.');
        }

        $payloads = [];

        return $this->request('get', 'open-payment/'.$uuid.'/transactions', $payloads);
    }
}
