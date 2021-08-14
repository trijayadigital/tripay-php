<?php

namespace Tripay;

abstract class Base
{
    protected $environment;
    protected $base;

    protected $apiKey;
    protected $privateKey;

    protected $channelCode;
    protected $merchantCode;
    protected $merchantRef;

    /**
     * Konstruktor
     *
     * @param int $environment
     */
    public function __construct($environment)
    {
        switch ($environment) {
            case Constants\Environment::PRODUCTION:
                $this->base = Constants\Endpoint::BASE_LIVE;
                break;

            case Constants\Environment::DEVELOPMENT:
                $this->base = Constants\Endpoint::BASE_SANDBOX;
                break;

            default:
                throw new Exceptions\InvalidEndpointException(
                    'Only Environment::PRODUCTION and Environment::DEVELOPMENT are supported'
                );
        }

        $this->environment = $environment;
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

        return $this->sendRequest('payment/instruction', $payloads);
    }

    /**
     * Set api key.
     *
     * @param string $apiKey
     *
     * @return Merchant
     */
    public function apiKey($apiKey)
    {
        $this->apiKey = (string) $apiKey;

        return $this;
    }

    /**
     * Set private key.
     *
     * @param string $privateKey
     *
     * @return Merchant
     */
    public function privateKey($privateKey)
    {
        $this->privateKey = (string) $privateKey;

        return $this;
    }

    /**
     * Set payment channel.
     *
     * @param string $channelCode
     *
     * @return Merchant
     */
    public function channelCode($channelCode)
    {
        $this->channelCode = (string) $channelCode;

        return $this;
    }

    /**
     * Set merchant code.
     *
     * @param string $merchantCode
     *
     * @return Merchant
     */
    public function merchantCode($merchantCode)
    {
        $this->merchantCode = (string) $merchantCode;

        return $this;
    }

    /**
     * Set reference code.
     *
     * @param string $merchantRef
     *
     * @return Merchant
     */
    public function merchantRef($merchantRef)
    {
        $this->merchantRef = (string) $merchantRef;

        return $this;
    }

    /**
     * Kirim request.
     *
     * @param string $uri
     * @param array  $payloads
     *
     * @return \stdClass
     */

    protected function request($method, $uri, array $payloads)
    {
        $method = (string) $method;
        $uri = $this->base.$uri;

        // TODO: gunakan socket jika cURL tidak tersedia di server.
        $driver = new Drivers\Curl();
        $options = [
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$this->apiKey],
            CURLOPT_FAILONERROR => false,
        ];

        // if (extension_loaded('curl') && is_callable('curl_init')) {
        //     $driver = new Drivers\Curl();
        //     $options = [
        //         CURLOPT_HEADER => false,
        //         CURLOPT_HTTPHEADER => ['Authorization: Bearer '.$this->apiKey],
        //         CURLOPT_FAILONERROR => false,
        //     ];
        // } elseif (function_exists('stream_context_create') && is_callable('stream_context_create')) {
        //     $driver = new Drivers\Stream();
        //     $options = [
        //         // ..
        //     ];
        // } else {
        //     throw new \RuntimeException(
        //         'Please enable curl or stream context before using this library.'
        //     );
        // }

        if (! in_array($method, ['get', 'post', 'put', 'delete'])) {
            throw new Exceptions\InvalidRequestTypeException(sprintf(
                'Only GET, POST, PUT and DELETE request are currently suported. Got: %s',
                $this->requestType
            ));
        }

        return $driver->request($method, $uri, $payloads, $options);
    }
}
