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
$data   = array(
    'skip'   => '0',
    'limit'  => '10',
    'filter' => [],
);

/** @var \Dflydev\Hawk\Client\Request $request */
$request = $client->createRequest(
    $credentials,
    'https://app.absence.io/api/v2/users',
    'POST',
    $data
);

$options = [
    'headers'     => [
        'content_type'                  => 'text/plain',
        $request->header()->fieldName() => $request->header()->fieldValue(),
    ],
    'form_params' => $data
];

/** @var \GuzzleHttp\Client $httpClient */
$httpClient = new \GuzzleHttp\Client(
    [
        'base_uri' => 'https://app.absence.io/api/v2/',
        'timeout'  => 2.0,
    ]
);

$res = $httpClient->post(
    'users',
    $options
);

var_dump($res->getStatusCode());

if ($res->getStatusCode() == 200) {
    var_dump($res->getHeader('content-type'));
    var_dump(json_decode($res->getBody()));
}
