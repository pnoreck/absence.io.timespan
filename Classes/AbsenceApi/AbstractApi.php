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

/**
 * Class AbstractApi
 *
 * @package T3fx\AbsenceApi
 */
class AbstractApi
{

    /**
     * Absence API URL
     */
    const API_URL = 'https://app.absence.io/api/v2/';

    /**
     * Build the request URL
     *
     * @param string $request
     *
     * @return string
     */
    public function getApiUrl(string $request)
    {
        return static::API_URL . trim($request, '/');
    }

    /**
     * Create and return a GuzzleHttp\Client
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        /** @var \GuzzleHttp\Client $httpClient */
        return new \GuzzleHttp\Client(
            [
                'base_uri' => static::API_URL,
                'timeout'  => 2.0,
            ]
        );
    }
}
