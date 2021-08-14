<?php

namespace Tripay\Drivers;

use InvalidArgumentException;
use Exception;

class Stream extends Base
{
    /**
     * Konstruktor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Jalankan request.
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
        // TODO: implementasikan koneksi via stream socket.
    }
}
