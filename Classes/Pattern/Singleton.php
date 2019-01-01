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

namespace T3fx\Library\Pattern;

abstract class Singleton
{
    /**
     * instance
     *
     * Instances of singleton classes
     *
     * @var Singleton
     */
    protected static $instances = [];

    /**
     * get instance
     *
     * Create the instance of the class if not exist and return it
     *
     * @return   Singleton
     */
    final public static function getInstance()
    {
        $calledClass = get_called_class();
        if (!isset(self::$instances[$calledClass])) {
            self::$instances[$calledClass] = new $calledClass();
            if (method_exists(self::$instances[$calledClass], 'init')) {
                self::$instances[$calledClass]->init();
            }
        }
        return self::$instances[$calledClass];
    }

    /**
     * clone
     *
     * prohibit external copy of the instance
     */
    final private function __clone()
    {
    }

    /**
     * constructor
     *
     * prohibit external use of constructor
     */
    final private function __construct()
    {
    }
}
