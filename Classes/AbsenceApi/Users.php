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
 * Class Users
 *
 * @package T3fx\AbsenceApi
 */
class Users extends AbstractApi
{

    public function getUsers()
    {
        $data = array(
            'skip'   => '0',
            'limit'  => '10',
            'filter' => [],
        );

        /** @var ApiAuthenticator $api */
        $api     = ApiAuthenticator::getInstance();
        $request = $api->createRequest(
            $this->getApiUrl('users'),

        );

    }
}
