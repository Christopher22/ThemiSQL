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
 * This class allows the save insert of data into the DMS.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class Insert extends ThemiSQL {

    public function __construct(Connector $connector)
    {
        $column_list = '';
        $placeholders = '';
        $values = array();

        if(($columns = $connector->getColumns()) === FAlSE || ($foreign = $connector->getForeignColumns()) === FALSE)
        {
             $this->setContent(ThemiSQL::FORMAT_ERROR_SERVER, $connector->getLastError());
             return;
        }
        
        foreach ($columns as $column) 
        {
            if (\filter_has_var(\INPUT_POST, $column['column']))
            {
                $placeholder = ":$column[column]";

                $column_list .= "$column[column], ";
                $placeholders .= $placeholder . ', ';
                $values[$placeholder] = ((($inputValue = \trim(\filter_input(\INPUT_POST, $column['column'], \FILTER_UNSAFE_RAW))) !== '') ? $inputValue : NULL);             
            }
            else
            {
                $this->setContent(ThemiSQL::FORMAT_ERROR_USER, $connector->getLastError());
                return;
            }
        }

        foreach ($foreign as $key => $value)
        {
            {
                $this->setContent(ThemiSQL::FORMAT_ERROR_USER, $connector->getLastError());
                return;
            }
            if($value['canInsert'] && ($inputValue = \trim(\filter_input(\INPUT_POST, $key, \FILTER_UNSAFE_RAW))) !== '')
                if(!$connector->query("INSERT IGNORE INTO $value[table]($value[column]) VALUES (:value)", array(':value' => $inputValue), FALSE))
        }
        
        $column_list = \substr($column_list, 0, -2);
        $placeholders = \substr($placeholders, 0, -2);
        $query = 'INSERT INTO ' . Config::get('table') . " ($column_list) VALUES ($placeholders)";
        
        if ($connector->query($query, $values, FALSE) !== FALSE)
        {
            if (Config::getPath(['auth', 'method']) !== NULL)
                Authenticator::reportSuccess(Config::get('auth'), $connector->getUser(), $connector->getPassword());

            $this->setContent(ThemiSQL::FORMAT_SUCCESS);
        }
        else
            $this->setContent(ThemiSQL::FORMAT_ERROR_USER, $connector->getLastError());
    }
}
