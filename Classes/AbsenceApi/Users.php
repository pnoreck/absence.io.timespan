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

    /**
     * Request a user list
     *
     * @param int   $skip
     * @param int   $limit
     * @param array $filter
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getUsers(int $skip = 0, int $limit = 10, array $filter = [])
    {
        $users = $this->postRequest(
            'users',
            [
                'skip'   => $skip,
                'limit'  => $limit,
                'filter' => $filter,
            ]
        );

        // Check if we have any result
        if (!is_array($users) || $users['count'] < 1 || !is_array($users['data'])) {
            return false;
        }

        // Simplify the result
        $output = [];
        foreach ($users['data'] as $user) {
            $output[$user['email']] = $user['_id'];
        }

        return $output;
    }

    /**
     * Search for timespans in the given range for the given usery
     *
     * @param string      $userId
     * @param string|null $start
     * @param string|null $end
     * @param int         $skip
     * @param int         $limit
     *
     * @return array
     * @throws \Exception
     */
    public function getTimespans(
        string $userId,
        string $start = null,
        string $end = null,
        int $skip = 0,
        int $limit = 10
    ) {

        $start = (is_null($start))
            ? strtotime('-1 day')
            : (
            (preg_match('/^[0-9]+$/i', $start))
                ? intval($start)
                : strtotime($start)
            );
        $end   = (is_null($end))
            ? time()
            : (
            (preg_match('/^[0-9]+$/i', $end))
                ? intval($end)
                : strtotime($end)
            );


        return $this->postRequest(
            'timespans',
            [
                'filter' => [
                    "userId" => $userId,
                    "start"  => ['$gte' => date('Y-m-d', $start)],
                    "end"    => ['$lt' => date('Y-m-d', $end)],
                ],
                'skip'   => $skip,
                'limit'  => $limit,
            ]
        );
    }

    /**
     *
     *
     * @param string   $userId
     * @param int      $start
     * @param int|null $end
     *
     * @return mixed
     * @throws \Exception
     */
    public function createTimespan(string $userId, int $start, int $end = null)
    {
        $start = (is_null($start))
            ? time()
            : (
            (preg_match('/^[0-9]+$/i', $start))
                ? intval($start)
                : strtotime($start)
            );

        $datetime = new \DateTime();
        $datetime->setTimestamp($start);
        $start   = $datetime->format(\DateTime::ATOM);
        $options = [
            "userId"       => $userId,
            // "start"        => $start,
            "start"        => "2019-01-01T10:12:09.428Z",
            "end"          => "2019-01-01T10:12:15.287Z",
            "timezoneName" => "CET",
            "timezone"     => "+0100",
            "type"         => "work"
        ];

        try {
            $response = $this->postRequest(
                'timespans/create',
                $options
            );
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return false;
        }

        return $response;
    }

    /**
     * Update an existing timespan
     *
     * @param string $timespanId
     * @param array  $updateFields
     *
     * @return bool|mixed
     */
    public function updateTimespan(string $timespanId, array $updateFields)
    {
        try {
            $response = $this->putRequest(
                'timespans/' . $timespanId,
                $updateFields
            );
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            return false;
        }

        return $response;
    }

}
