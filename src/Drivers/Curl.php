<?php

namespace Tripay\Drivers;

use InvalidArgumentException;
use Exception;

class Curl extends Base
{
    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Kirim request.
     *
     * @param string $method
     * @param string $url
     * @param array  $params
     * @param array  $options
     *
     * @return \stdClass
     */
    public function request($method, $url, array $params = [], array $options = [])
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->agent());

        $query = empty($params) ? null : http_build_query($params, '', '&', PHP_QUERY_RFC1738);

        switch (strtolower($method)) {
            case 'get':
                $url .= $query ? '?'.$query : '';
                curl_setopt($curl, CURLOPT_HTTPGET, 1);
                break;

            case 'post':
                if ($query) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
                }

                if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
                    $options[CURLOPT_HTTPHEADER] = array_merge(
                        $options[CURLOPT_HTTPHEADER],
                        ['Content-Type: application/x-www-form-urlencoded']
                    );
                } else {
                    $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/x-www-form-urlencoded'];
                }

                curl_setopt($curl, CURLOPT_POST, 1);
                break;

            case 'put':
                if ($query) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
                }

                if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
                    $options[CURLOPT_HTTPHEADER] = array_merge(
                        $options[CURLOPT_HTTPHEADER],
                        ['Content-Type: application/x-www-form-urlencoded']
                    );
                } else {
                    $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/x-www-form-urlencoded'];
                }

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;

            case 'delete':
                if ($query) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
                }

                if (isset($options[CURLOPT_HTTPHEADER]) && is_array($options[CURLOPT_HTTPHEADER])) {
                    $options[CURLOPT_HTTPHEADER] = array_merge(
                        $options[CURLOPT_HTTPHEADER],
                        ['Content-Type: application/x-www-form-urlencoded']
                    );
                } else {
                    $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/x-www-form-urlencoded'];
                }

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;

            default: throw new InvalidArgumentException(sprintf(
                'Usupported request method: %s',
                strtoupper($method)
            ));
        }

        if (is_array($options) && ! empty($options)) {
            curl_setopt_array($curl, $options);
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        $response = curl_exec($curl);

        if (false === $response) {
            $code = curl_errno($curl);
            $message = curl_error($curl);
            curl_close($curl);

            throw new \Exception($message, $code);
        }

        curl_close($curl);

        return json_decode($response);
    }
}
