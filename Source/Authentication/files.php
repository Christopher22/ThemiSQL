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
 * Checks the existence of files to authenticate users.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class files extends Authenticator {
    
    private $path;
    
    public function __construct(array $config)
    {
        $this->path = \realpath(__DIR__ . '/../../Config/' . (isset($config['path']) ? $config['path'] : ''));
        if(\substr($this->path, -1) !== '/')
                $this->path .= '/';
    }
    
    public function isOK($user, $password)
    {
        $user = \str_replace('/', '', $user);
        if(isset($config['noPassword']) && $config['noPassword'] === TRUE)
            return \file_exists($this->path . "file_$user.tmp");
        elseif(isset($config['hash']))
            return (\file_exists($this->path . "file_$user.tmp") && @\file_get_contents($this->path . "file_$user.tmp") === \hash($config['hash'], $password));
        else
            return (\file_exists($this->path . "file_$user.tmp") && @\file_get_contents($this->path . "file_$user.tmp") === $password);
    }
   
     public function finished($user, $password) 
     {
         \unlink($this->path . "file_$user.tmp"); 
     }
}
    