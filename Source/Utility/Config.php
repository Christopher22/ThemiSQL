<?php

/*  Copyright (C) 2015 Christopher Gundler <c.gundler@mail.de>
*   This file is part of ThemiSQL.
* 
*   This program is free software: you can redistribute it and/or modify
*   it under the terms of the GNU Affero General Public License as published
*   by the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
 */

namespace ThemiSQL;

/**
 * This class allows easy global access to the configuration of ThemiSQL.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
abstract class Config {

    private static $_data = NULL;
    
    public static function isValid()
    {
        return self::$_data !== NULL;
    }

    public static function load($path = '/../../Config/config.json')
    {
        self::$_data = (\file_exists(__DIR__ . $path) ? \json_decode(\file_get_contents(__DIR__ . $path), TRUE) : NULL);
    }
    
    public static function get($value)
    {
        return isset(self::$_data[$value]) ? self::$_data[$value] : NULL;
    }

    public static function getPath(array $path)
    {
        $tmp = &self::$_data;
        foreach($path as $value) {
            if(isset($tmp[$value]))
                $tmp = &$tmp[$value];
            else
                return NULL;
        }
        return $tmp;
    }
}
