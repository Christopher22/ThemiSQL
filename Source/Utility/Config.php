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

    public static function load($file = 'config.json')
    {
        $values = (($path = \realpath(__DIR__ . '/../../Config/' . $file)) !== FALSE ? \json_decode(\file_get_contents($path), TRUE) : NULL);
     
        if(self::$_data !== NULL && $values !== NULL)
            self::$_data = \array_replace_recursive(self::$_data, $values);
        elseif($values !== NULL)
            self::$_data = $values;
        else
            return FALSE;
        
        return TRUE;
    }
   
    public static function get($value, $onError = NULL)
    {
        return isset(self::$_data[$value]) ? self::$_data[$value] : $onError;
    }

    public static function getPath(array $path , $onError = NULL)
    {
        $tmp = &self::$_data;
        foreach($path as $value) {
            if(isset($tmp[$value]))
                $tmp = &$tmp[$value];
            else
                return $onError;
        }
        return $tmp;
    }
}
