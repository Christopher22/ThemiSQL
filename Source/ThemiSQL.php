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
 * The base class for all other interfaces.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class ThemiSQL {

    const FORMAT_CUSTOM = 0;
    const FORMAT_SUCCESS = 1;
    const FORMAT_ERROR_USER = 400;
    const FORMAT_ERROR_SERVER = 500;
    
    const MISSING_GUI = '<ThemiSQL: Missing>';
    
    private $_content;

    public final function getContent()
    {
        return $this->_content;
    }

    public function getTitle()
    {
        return Config::getPath(['gui', 'title'], 'ThemiSQL');
    }

    public final function getCSS()
    {
        return 'Config/' . Config::get('css', 'config.css');
    }
    
    public final function setContent($format, $content = NULL)
    {
        if($format !== ThemiSQL::FORMAT_CUSTOM)
        {
            if($format === ThemiSQL::FORMAT_SUCCESS)
                $output = Config::getPath(['msg', '!success'], 'Success');
            elseif(($output = Config::getPath(['msg', "$content"])) === NULL)
            {
                \header("$_SERVER[SERVER_PROTOCOL] " . ($format === ThemiSQL::FORMAT_ERROR_USER ? 'Bad Request' : 'Internal Server Error'), TRUE, $content);
                 $output = "Error: $content";
            }
            
            $this->_content = '<h2 class="' . ($format === ThemiSQL::FORMAT_SUCCESS ? 'success' : 'error') . "\">$output</h2>";
        }
        else
            $this->_content = $content;
    }

    public static function getSite()
    {
        require __DIR__ . '/Utility/Config.php';
        Config::load();
        if (\filter_has_var(\INPUT_GET, 'view') && Config::load(\preg_replace('/[^A-Za-z0-9]/', '', \filter_input(\INPUT_GET, 'view', \FILTER_SANITIZE_STRING)) . '/config.json') === FALSE)
        {
            \header("$_SERVER[SERVER_PROTOCOL] 404 Not Found", TRUE, 404);
            Config::load('404/config.json');
        }

        if(($extra = Config::getPath(['site', 'method'])) !== NULL)
        {
            if($extra === 'redirect')
            {
                header('Location: ' . Config::getPath(['site', 'url'], 'http://example.com/'), TRUE);
                exit();
            }
            elseif ($extra === 'html') 
            {
                $tmp = new ThemiSQL();
                $tmp->setContent(ThemiSQL::FORMAT_CUSTOM, Config::getPath(['site', 'html'], 'Your custom HTML!'));
                return $tmp;
            }
        }

        if (!\filter_has_var(\INPUT_POST, 'user'))
        {
            require __DIR__ . '/LogIn.php';
            return new LogIn();
        }

        $user = \filter_input(\INPUT_POST, 'user');
        $password = \filter_input(\INPUT_POST, 'pwd');
        if (Config::getPath(['auth', 'method']) !== NULL)
        {
            require __DIR__ . '/Authentication/Authenticator.php';
            if (!Authenticator::isAuthenticated(Config::get('auth'), $user, $password))
            {
                $tmp = new ThemiSQL();
                $tmp->setContent(ThemiSQL::FORMAT_ERROR_USER, '!auth');
                return $tmp;
            }
        }

        require __DIR__ . '/Utility/Connector.php';
        $connect = new Connector($user, $password);

        if ($connect->getLastError() !== NULL)
        {
            $tmp = new ThemiSQL();
            $tmp->setContent(ThemiSQL::FORMAT_ERROR_USER, $connect->getLastError());
            return $tmp;
        }
        elseif (!\filter_has_var(\INPUT_POST, 'insert'))
        {
            require __DIR__ . '/Form.php';
            return new Form($connect);
        }
        else
        {
            require __DIR__ . '/Insert.php';
            return new Insert($connect);
        }
    }
}
