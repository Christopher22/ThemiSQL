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
 * Executes custom PHP to authenticate users.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class php extends Authenticator {
    
    private $code;
    
    public function __construct(array $config)
    {
        $this->code = (isset($config['code']) ? $config['code'] : 'return FALSE;');
    }
    
    public function isOK($user, $password)
    {
        return (eval($this->code) === TRUE);
    }
   
}
    