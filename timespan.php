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

defined('T3FX_ROOT') or define('T3FX_ROOT', __DIR__);

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('T3fx\\', __DIR__ . '/Classes');

$userApi = new T3fx\AbsenceApi\Users();
$users   = $userApi->getUsers();

if (isset($argv[1]) &&
    filter_var($argv[1], FILTER_VALIDATE_EMAIL) &&
    isset($users[$argv[1]])
) {
    $currentUserId = $users[$argv[1]];
    $timespans     = $userApi->getTimespans($currentUserId);
    $start         = true;
    foreach ($timespans["data"] as $timespan) {
        if ($timespan["end"] === null) {
            $datetime = new \DateTime();
            $end      = $datetime->format(\DateTime::ISO8601);
            $datetime->setTimestamp(time() - 60);
            $userApi->updateTimespan(
                $timespan["_id"],
                [
                    'timezoneName' => $timespan["timezoneName"],
                    'timezone'     => $timespan["timezone"],
                    'end'          => $end,
                ]
            );
            $start = false;
            echo 'Timespan stopped.' . PHP_EOL;
        }
    }

    if ($start) {
        if ($userApi->createTimespan($currentUserId, time())) {
            echo 'Timespan started.' . PHP_EOL;
        }
    }
}
