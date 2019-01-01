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

$users = new T3fx\AbsenceApi\Users();
$users->getUsers();
