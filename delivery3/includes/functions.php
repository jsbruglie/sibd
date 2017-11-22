<?php

    /**
     * @file        functions.php
     *
     * @brief       Basic functions
     * 
     * @author      João Borrego
     *              Daniel Sousa
     *              Nuno Ferreira
     */

    require_once("constants.php");

    // TODO - Prepare statements

    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
    function query(/* $sql [, ... ] */)
    {
        // SQL statement
        $sql = func_get_arg(0);

        // parameters, if any
        $parameters = array_slice(func_get_args(), 1);

        // try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }

        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }

    function createTable($table, $column_names)
    {

        $table_html = '<table class="table">' . "\n";
        // table head
        $table_html .= "<thead>\n<tr>\n";        
        foreach ($column_names as $col)
        {
            $table_html .= "<th>" . $col[0] . "</th>\n";
        }
        $table_html .= "</tr>\n</thead>\n";
        
        // table body
        $table_html .= "<tbody>\n";
        foreach ($table as $row)
        {
            $table_html .= "<tr>\n";
            foreach ($column_names as $key => $col)
            {
                // Replace content of special columns
                if (count($col) == 3){
                    // Replace variable instances in regex by variable values
                    $exp = $col[2];
                    foreach($row as $key => $aux){
                        $exp = str_replace("$". $key, $row[$key], $exp);
                    }
                    $entry = $exp;
                } else {
                    $entry = $row[$col[1]];
                }
                $table_html .= '<td class="col-md-3">' . $entry . "</td>\n";
            }
            $table_html .= "</tr>\n";
        }
        $table_html .= "</tbody>\n";

        $table_html .= "</table>\n";

        return $table_html;
    }

?>
