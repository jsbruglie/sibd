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

    /**
     * Returns a database handle
     *
     * If not set, creates the handle instance and connects to DB
     *
     * @return     PDO   The database handle.
     */
    function getDatabaseHandle()
    {
        if (!isset($handle))
        {
            // If not declare static variable in order to have a single instance
            static $handle;
            try
            {
                // Connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

                // Ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                // Ensure an exception is thrown whenever a query fails
                $handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (Exception $e)
            {
                // Trigger error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }
        return $handle;
    }

    /**
     * Executes SQL statement, possibly with parameters
     *
     * @throws     PDOException on failure
     *
     * @param      string   $sql         The sql query
     * @param      array    $parameters  The array of parameters
     *
     * @return     An array of all rows in result set or false on (non-fatal) error.
     */
    function query($sql, $parameters)
    {
        // Get the database handle
        $handle = getDatabaseHandle();

        // prepare SQL statement
        $statement = $handle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        if ($statement === false){
            // Trigger error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result sets rows, if any
        if ($results !== false){
            try {
                $output = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $output;
            } catch (PDOException $e) {
                // Exception is thrown when there is nothing to fetch. (e.g. successful insertion or update)
                // It is safe to disregard it and return success
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Attempts performing a database query
     *
     * @param      string   $sql            The sql query
     * @param      array    $parameters     The array of parameters
     *
     * @return     boolean  Whether the query was successful or not
     */
    function tryQuery($sql, $parameters)
    {
        try
        {
            return query($sql, $parameters);
        }
        catch (PDOException $e)
        {
            //echo $e->getMessage();
            return false;
        }
    }

    /**
     * Performs a transaction for a given array of sql queries
     *
     * @param      array    $queries  The array of sql queries
     *
     * @return     boolean  Whether the transaction was successful
     */
    function transact($queries)
    {
        $handle = getDatabaseHandle();

        $handle->beginTransaction();
        try
        {
            foreach ($queries as $request)
            {
                $sql = $request[0];
                $parameters = (count($request) == 1)? [] : $request[1];
                $statement = $handle->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $results = $statement->execute($parameters);
            }
        }
        catch (PDOException $e)
        {
            $handle->rollBack();
            $error = $e->getMessage();
            return array(false, $error);
        }
        $handle->commit();
        return array(true, null);
    }

    /**
     * Creates a table.
     *
     * @param      <type>  $table         The table
     * @param      <type>  $column_spec   The array of column specifications
     *
     * @return     string  The HTML string of the desired table
     */
    function createTable($table, $column_spec)
    {
        $num_cols = count($column_spec);

        $table_html = '<table class="table table-sm">' . "\n";
        // table head
        $table_html .= "<thead>\n<tr>\n";
        foreach ($column_spec as $col)
        {
            $table_html .= "<th>" . $col[0] . "</th>\n";
        }
        $table_html .= "</tr>\n</thead>\n";

        // table body
        $table_html .= "<tbody>\n";
        foreach ($table as $row)
        {
            $table_html .= "<tr>\n";
            foreach ($column_spec as $key => $col)
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
                $table_html .= '<td>' . $entry . "</td>\n";
            }
            $table_html .= "</tr>\n";
        }
        $table_html .= "</tbody>\n";
        $table_html .= "</table>\n";

        return $table_html;
    }

?>
