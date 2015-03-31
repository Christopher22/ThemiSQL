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
 * This class provides access to the DMS.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class Connector {

    private $_lastError, $_sql, $_acc;

    public function __construct($user, $password = NULL)
    {
        try {
            $this->_sql = new \PDO(Config::get('dsn'), $user, $password);
        } catch (\PDOException $exc) {
            $this->_lastError = $exc->getCode();
            return;
        }
        $this->_acc = array($user, $password);
    }

    public function getUser()
    {
        return $this->_acc[0];
    }

    public function getPassword()
    {
        return $this->_acc[1];
    }

    public function getLastError()
    {
        return $this->_lastError;
    }

    public function getForeignColumns()
    {
        static $cache = FALSE;
        if ($cache === FALSE && $this->_lastError === NULL)
        {
            if (($foreign = $this->query(Config::getPath(['query', 'foreignKeys']), array(':table' => Config::get('table')))) !== FALSE)
            {
                try {
                    if (($checkInsert = $this->_sql->prepare(Config::getPath(['query', 'canInsert']))) === FALSE)
                    {
                        $this->_lastError = $this->_sql->errorCode();
                        return FALSE;
                    }
                } catch (\Exception $ex) {
                    return FALSE;
                }

                $cache = array();

                for ($i = 0, $size = \count($foreign); $i < $size; ++$i) {
                    $data = $this->_sql->query("SELECT {$foreign[$i]['column']} AS 'value' FROM {$foreign[$i]['table']}", \PDO::FETCH_ASSOC);
                    $can = ($checkInsert->execute(array(':table' => $foreign[$i]['table'], ':column' => $foreign[$i]['column'])) && ($tmp = $checkInsert->fetch(\PDO::FETCH_NUM)) !== FALSE && $tmp[0] === '1');
                    $cache[$foreign[$i]['ref_column']] = array('table' => $foreign[$i]['table'], 'column' => $foreign[$i]['column'], 'values' => $data, 'canInsert' => $can);
                }
            }
            else
                $this->_lastError = $this->_sql->errorCode();
        }
        return $cache;
    }

    public function query($preparedStatement, array $values)
    {
        try {
            $provider = $this->_sql->prepare($preparedStatement);
        } catch (\Exception $ex) {
            return FALSE;
        }

        if ($provider === FALSE || $provider->execute($values) === FALSE)
        {
            $this->_lastError = ($provider === FALSE ? $this->_sql->errorCode() : $provider->errorCode());
            return FALSE;
        }

        return $provider->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getColumns()
    {
        static $cache = FALSE;
        if ($cache === FALSE && $this->_lastError === NULL && ($cache = $this->query(Config::getPath(['query', 'column']), array(':table' => Config::get('table')))) === FALSE)
            $this->status = $this->_sql->errorCode();
        return $cache;
    }
}
