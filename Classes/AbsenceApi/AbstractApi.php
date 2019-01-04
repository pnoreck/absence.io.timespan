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

use Psr\Http\Message\ResponseInterface;

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
    protected function getApiUrl(string $request)
    {
        return static::API_URL . trim($request, '/');
    }

    /**
     * Create and return a GuzzleHttp\Client
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        /** @var \GuzzleHttp\Client $httpClient */
        return new \GuzzleHttp\Client(
            [
                'base_uri' => static::API_URL,
                'timeout'  => 2.0,
            ]
        );
    }

    /**
     * Validate the the guzzle response
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     * @throws \Exception
     */
    protected function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new \Exception('Server responded with code ' . $response->getStatusCode());
        }

        $contentType = $response->getHeader('content-type');
        $contentType = (is_array($contentType)) ? reset($contentType) : $contentType;

        if (strpos($contentType, 'json') !== false) {
            return json_decode($response->getBody(), true);
        }

        return $response->getBody();
    }

    /**
     * Do a post request
     *
     * @param string $action
     * @param array  $data
     *
     * @return mixed
     * @throws \Exception
     */
    protected function postRequest(string $action, array $data)
    {
        /** @var ApiAuthenticator $api */
        $api     = ApiAuthenticator::getInstance();
        $request = $api->createRequest(
            $this->getApiUrl($action),
            'POST',
            $data
        );
        $options = [
            'headers'     => [
                'content_type'                  => 'application/json',
                $request->header()->fieldName() => $request->header()->fieldValue(),
            ],
            'form_params' => $data
        ];

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->post(
            $action,
            $options
        );

        return $this->validateResponse($response);
    }

    /**
     * Do a put request
     *
     * @param string $action
     * @param array  $data
     *
     * @return mixed
     * @throws \Exception
     */
    protected function putRequest(string $action, array $data)
    {
        /** @var ApiAuthenticator $api */
        $api     = ApiAuthenticator::getInstance();
        $request = $api->createRequest(
            $this->getApiUrl($action),
            'PUT',
            $data
        );
        $options = [
            'headers'     => [
                'content_type'                  => 'text/plain',
                $request->header()->fieldName() => $request->header()->fieldValue(),
            ],
            'form_params' => $data
        ];

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->put(
            $action,
            $options
        );

        return $this->validateResponse($response);
    }
}
