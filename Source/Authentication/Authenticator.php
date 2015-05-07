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
 * The base class for all other authenticators.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
abstract class Authenticator {
    
    public abstract function isOK($user, $password);
    public function finished($user, $password) {}
    
    public static function reportSuccess(array $config, $user, $password)
    {
        $result = self::loadClass($config['method'], $config);
        if($result !== NULL)
            $result->finished($user, $password);
    }
    
    public static function isAuthenticated(array $config, $user, $password)
    {
        $result = self::loadClass($config['method'], $config);
        return ($result !== NULL && $result->isOK($user, $password) === TRUE);
    }
    
    private static function loadClass($name, $constructor = NULL)
    {
        $className = '\\ThemiSQL\\' . $name;
        
        if (!\class_exists($className) && \is_file(__DIR__ . "/$name.php"))
             require __DIR__ . "/$name.php";

        return (\is_subclass_of($className, '\ThemiSQL\Authenticator') ? new $className($constructor) : NULL);
    }
}