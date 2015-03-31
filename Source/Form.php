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
 * This class presents the interface for an interaction with the DMS.
 * @author Christopher Gundler <c.gundler@mail.de>
 */
class Form extends ThemiSQL {

    public function __construct(Connector $connector)
    {
        $sql = Config::get('sql_values');
        if(($foreign = $connector->getForeignColumns()) === FALSE || ($columns = $connector->getColumns()) === FAlSE)
        {
             $this->setContent(ThemiSQL::FORMAT_ERROR_SERVER, $connector->getLastError());
             return;
        }
        
        $result = '<form method="POST" action=""><input type="hidden" name="insert" value="i" /><input type="hidden" name="user" value="' . $connector->getUser().  '" />' . ($connector->getPassword() !== NULL ? '<input type="hidden" name="pwd" value="' . $connector->getPassword().  '" />' . "\n" : "\n");

        foreach ($columns as $column) 
        {
            $result .= "<div class=\"row\"><label class=\"six columns\" for=\"$column[column]\">$column[label]</label>";
            if (isset($foreign[$column['column']]) && $foreign[$column['column']]['canInsert'] === FALSE) //If the user can not add own values...
            {
                $result .= "<select class=\"six columns\" name=\"$column[column]\" id=\"$column[column]\">\n";
                foreach ($foreign[$column['column']]['values'] as $value) {
                    $result .= "<option value=\"$value[value]\">$value[value]</option>\n";
                }
                $result .= "</select></div>\n";
            }
            else
            {
                if (isset($foreign[$column['column']])) //If the user can add new values, show recent inputs
                {
                    $result .= "<datalist id=\"$column[column]_data\">\n";
                    foreach ($foreign[$column['column']]['values'] as $value) {
                        $result .= "<option value=\"$value[value]\">$value[value]</option>\n";
                    }
                    $result .= "</datalist>\n";
                }
                
                $result .= "<input required class=\"six columns\" id=\"$column[column]\" name=\"$column[column]\"" . (isset($foreign[$column['column']]) ? " list=\"$column[column]_data\" " : ' ');
                if (isset($sql[$column['type']]) && \is_array($sql[$column['type']])) //Use custom config
                {
                    foreach ($sql[$column['type']] as $key => $value) {
                        $result .= "$key=\"$value\" ";
                    }
                    $result .= "/></div>\n";
                }
                else
                    $result .= "type=\"text\" /></div>\n";
            }
        }
        
        $result .= '<div class="row"><input class="six columns button-primary" type="submit" value="' . Config::getPath(['gui', 'insert'], ThemiSQL::MISSING_GUI) . '"><input class="six columns" type="reset" value="' . Config::getPath(['gui', 'reset'], ThemiSQL::MISSING_GUI) . '" /></div></form>';
        $this->setContent(ThemiSQL::FORMAT_CUSTOM, $result);
    }
}
