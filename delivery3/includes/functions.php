<?php

    /**
     * @file    functions.php
     *
     * @brief   Basic functions
     * 
     * @author  João Borrego
     *          Daniel Sousa
     *          Nuno Ferreira
     */

    require_once("constants.php");

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

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = [])
    {
        // if template exists, render it
        if (file_exists("../templates/$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("../templates/header.php");

            // render template
            require("../templates/$template");

            // render footer
            require("../templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

    function createTable($table, $column_names)
    {

        $table_html = '<table class="table">' . "\n";
        // table head
        $table_html .= "<thead>\n<tr>\n";        
        foreach ($column_names as $col)
        {
            $table_html .= "<th>" . $col . "</th>\n";
        }
        $table_html .= "</tr>\n</thead>\n";
        
        // table body
        $table_html .= "<tbody>\n";
        foreach ($table as $row)
        {
            $table_html .= "<tr>\n";
            foreach ($column_names as $col)
            {
                $table_html .= "<td>" . $row[$col] . "</td>\n";
            }
            $table_html .= "</tr>\n";
        }
        $table_html .= "</tbody>\n";

        $table_html .= "</table>\n";

        return $table_html;
    }

?>
