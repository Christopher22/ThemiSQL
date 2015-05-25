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
        $output = '<form id="TSQL_login" action="" method="POST"><div class="row"><label class="six columns" for="user">' . Config::getPath(['gui', 'username'], 'User:') . '</label><input type="text" class="six columns" name="user" id="user" required /></div>';
        
        if(Config::get('no_password') !== TRUE)
            $output .= '<div class="row"><label class="six columns" for="pwd">' . Config::getPath(['gui', 'password'], 'Password:') . '</label><input type="password" class="six columns" name="pwd" id="pwd" required /></div>';

        $this->setContent(ThemiSQL::FORMAT_CUSTOM, $output . '<div class="row"><input class="button-primary six columns" type="submit" value="' . Config::getPath(['gui', 'logIn'], ThemiSQL::MISSING_GUI) . '"/><input class="six columns" type="reset" value="' . Config::getPath(['gui', 'reset'], ThemiSQL::MISSING_GUI) . '"/></div></form>');
    }

    public function getTitle()
    {
        return Config::getPath(['gui', 'title'], 'Log in');
    }

}
