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
if (!is_file(__DIR__ . '/config.php')) {
    die("Could not find the configuration file.");
}

$config = require 'config.php';
$loader = require __DIR__ . '/vendor/autoload.php';

// Create a set of Hawk credentials
$credentials = new Dflydev\Hawk\Credentials\Credentials(
    $config['key'],
    $config['algorithm'],
    $config['id']
);

/** @var \Dflydev\Hawk\Client\Client $client */
$client = Dflydev\Hawk\Client\ClientBuilder::create()->build();

/** @var \Dflydev\Hawk\Client\Request $request */
$request = $client->createRequest(
    $credentials,
    'https://app.absence.io/api/v2/users',
    'GET',
    array(
        'skip'   => '0',
        'limit'  => '10',
        'filter' => [],
    )
);

$header = new \Dflydev\Hawk\Header\Header(
    'HTTP',
    'GET'
);

$result = $client->authenticate(
    $credentials,
    $request,
    $header,
    array(
        'skip'   => '0',
        'limit'  => '10',
        'filter' => [],
    )
);


var_dump($result);
