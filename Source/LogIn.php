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
 * This class manages the credentials for the DMS.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class LogIn extends ThemiSQL {

    public function __construct()
    {
        $this->setContent(ThemiSQL::FORMAT_CUSTOM, <<<'HTTP'
<form action="" method="POST">
    <div class="row">
        <label class="six columns" for="user">User:</label>
        <input type="text" class="six columns" name="user" id="user" required />
    </div>
    <div class="row">
        <label class="six columns" for="pwd">Password:</label>
        <input type="password" class="six columns" name="pwd" id="pwd" required />
    </div>
    <div class="row">
HTTP
                . '<input class="button-primary six columns" type="submit" value="' . Config::getPath(['gui', 'logIn']) . '"/><input class="six columns" type="reset" value="' . Config::getPath(['gui', 'reset']) . '"/></div></form>');
    }

    public function getTitle()
    {
        return 'Log in';
    }

}
