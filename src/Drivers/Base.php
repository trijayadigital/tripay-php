<?php

namespace Tripay\Drivers;

abstract class Base
{
    public function __construct()
    {
        // ..
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
    abstract public function request($method, $url, array $params = [], array $options = []);

    /**
     * Buat user-agent palsu.
     * Beberapa situs seperti github mewajibkan string user-agent.
     *
     * @return string
     */
    public function agent()
    {
        $year = (int) gmdate('Y');
        $year = ($year < 2020) ? 2020 : $year;
        $version = 77 + ($year - 2020) + 2;

        $agents = [
            'Windows' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
            'Linux' => 'Mozilla/5.0 (Linux x86_64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
            'Darwin' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:[v].0) Gecko/20100101 Firefox/[v].0',
            'BSD' => 'Mozilla/5.0 (X11; FreeBSD amd64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
            'Solaris' => 'Mozilla/5.0 (Solaris; Solaris x86_64; rv:[v].0) Gecko/20100101 Firefox/[v].0',
        ];

        $platform = $this->platform();
        $platform = $platform === 'Unknown' ? 'Linux' : $platform;

        return str_replace('[v]', $version, $agents[$platform]);
    }

    /**
     * Ambil sistem operasi server.
     *
     * @return string
     */
    public function platform()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            return 'Windows';
        }

        $platforms = [
            'Darwin' => 'Darwin',
            'DragonFly' => 'BSD',
            'FreeBSD' => 'BSD',
            'NetBSD' => 'BSD',
            'OpenBSD' => 'BSD',
            'Linux' => 'Linux',
            'SunOS' => 'Solaris',
        ];

        return isset($platforms[PHP_OS]) ? $platforms[PHP_OS] : 'Unknown';
    }
}
