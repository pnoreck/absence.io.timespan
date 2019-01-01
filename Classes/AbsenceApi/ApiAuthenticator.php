<?php
/*
 * Copyright 2018 - Steffen Hastädt
 *
 * t3fx@t3x.ch | www.t3x.ch
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace T3fx\AbsenceApi;

use Dflydev\Hawk\Client\Client;
use Dflydev\Hawk\Client\ClientBuilder;
use Dflydev\Hawk\Credentials\Credentials;
use T3fx\Library\Pattern\Singleton;

/**
 * Class ApiAuthenticator
 *
 * @package T3fx\AbsenceApi
 */
class ApiAuthenticator extends Singleton
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * @var Client
     */
    protected $client;


    /**
     * Initialize basic setup
     *
     * @return void
     */
    public function init()
    {
        $configFile = T3FX_ROOT . '/config.php';
        if (!is_file($configFile)) {
            die("Could not find the configuration file for ApiAuthenticator.");
        }

        $this->config = require $configFile;

        // Create a set of Hawk credentials
        $this->credentials = new Credentials(
            $this->config['key'],
            $this->config['algorithm'],
            $this->config['id']
        );

        /** @var Client $client */
        $this->client = ClientBuilder::create()->build();

    }

    /**
     * Create an authentication header for a new request
     *
     * @param string $url    The full request URL
     * @param string $method The request method (POST, GET, PUT etc)
     * @param array  $data   The request data as array
     *
     * @return mixed
     */
    public function createRequest(string $url, string $method, array $data)
    {
        return $this->client->createRequest(
            $this->credentials,
            $url,
            $method,
            $data
        );
    }

}
