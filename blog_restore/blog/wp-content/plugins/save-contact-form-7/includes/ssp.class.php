<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class SSP {

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    static function data_output($columns, $data) {
        $out = array();
        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];
                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                } else {
                    //echo $data[$i][$j];
                    //echo $columns[$j]['db'];
                    $row[$j] = $data[$i][$j];
                }
            }
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Database connection
     *
     * Obtain an PHP PDO connection from a connection details array
     *
     *  @param  array $conn SQL connection details. The array should have
     *    the following properties
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     *  @return resource PDO connection
     */
    static function db($conn) {
        if (is_array($conn)) {
            return self::sql_connect($conn);
        }
        return $conn;
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    static function limit($request, $columns) {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    static function order($request, $columns) {
        $order = '';
        $columns = self::cols_without_concat($columns); // returns array without concatenated string in fields name
        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                            'ASC' :
                            'DESC';

                    $orderBy[] = '' . $column['db'] . ' ' . $dir;
                }
            }

            $order = 'ORDER BY ' . implode(', ', $orderBy);
        }

        return $order;
    }

    static function customeorder($request, $columns) {
        $order = '';

        $columns = self::cols_without_concat($columns); // returns array without concatenated string in fields name

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');
            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];
                //$columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx]['db'];
                //if ($requestColumn['orderable'] == 'true') {
                $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';
                $orderBy[] = $column . ' ' . $dir;
                //}
            }
            $order = 'ORDER BY ' . implode(', ', $orderBy);
        }
        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    static function filter($request, $columns, &$bindings) {

        $globalSearch = array();
        $columnSearch = array();
        $columns = self::cols_without_concat($columns); // returns array without concatenated string in fields name
        $dtColumns = self::pluck($columns, 'db');
        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {

                $requestColumn = $request['columns'][$i];
                $columnIdx = array_key_exists($requestColumn['data'], $dtColumns);
                if ($columnIdx == 1) {
                    $column = $columns[$i];
                }
                if ($requestColumn['searchable'] == 'true') {
                    $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                    $globalSearch[] = "" . $column['db'] . " LIKE " . $binding;
                }
            }
        }

        // Individual column filtering
        for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
            $requestColumn = $request['columns'][$i];
            $columnIdx = array_search($requestColumn['data'], $dtColumns);
            $column = $columns[$columnIdx];

            $str = $requestColumn['search']['value'];

            if ($requestColumn['searchable'] == 'true' &&
                    $str != '') {
                $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                $columnSearch[] = "" . $column['db'] . " LIKE " . $binding;
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                    implode(' AND ', $columnSearch) :
                    $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    static function customefilter($request, $columns, &$bindings) {

        $columns = self::cols_without_concat($columns); // returns array without concatenated string in fields name
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search'] != '') {
            $str = $request['search'];

            for ($i = 0, $ien = count($dtColumns); $i < $ien; $i++) {
                $column = $columns[$i];
                $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                $globalSearch[] =  $column['db'] . " LIKE " . $binding;
            }
        }

        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn['search']['value'];

                if ($requestColumn['searchable'] == 'true' &&
                        $str != '') {
                    $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                    $columnSearch[] = $column['db'] . " LIKE " . $binding;
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                    implode(' AND ', $columnSearch) :
                    $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    static function simple($request, $conn, $table, $primaryKey, $columns, $search = NULL, $sort, $ordertype, $join = NULL) {

        $bindings = array();
        $db = self::db($conn);
        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
//        $order = self::order($request, $columns);
//         $where = self::filter($request, $columns, $bindings);


        if (isset($sort) && $sort != '') {
            $requestt['order'][0]['column'] = $sort;
            $requestt['order'][0]['dir'] = $ordertype;
            $order = self::customeorder($requestt, $columns);
        } else {
            $order = self::order($request, $columns);
        }
        if (isset($search) && $search != '') {

            $request['search'] = $search;
            $where = self::customefilter($request, $columns, $bindings);
            
        } else {
            $where = self::filter($request, $columns, $bindings);
            
        }
        
//         if ($where != '') {
//            $where = $where . " And UserID='" . $_SESSION['USERID'] . "'";
//        } else {
//            $where = "Where UserID='" . $_SESSION['USERID'] . "'";
//        }
//
//        echo "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
//			 FROM $table 
//                                $where
//			 $order
//			 $limit";
//        exit;
        // Main query to actually get the data
        if ($join == NULL ) {
                
            $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table
			 $where
			 $order
			 $limit"
            );
        } else if ($join == 'possibleValue') {
            $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table join possValTab on id=iPossValTabID
			 $where
			 $order
			 $limit"
            );
        } else if ($join == 'applicationField') {
            $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table join mapTab on id=iMapTabID
			 $where
			 $order
			 $limit"
            );
        } else if ($join == 'applanguage') {
            $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table join tbl_appID on id=iAppTableID
			 $where
			 $order
			 $limit"
            );
        } else if ($join == 'dateDesc') {

            $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table
			 $where order by tModifyTime DESC
			
			 $limit"
            );
        } else if ($join == 'userdefaultvalue') {
            if ($where != '') {
                $where = $where . " And UserID='" . $_SESSION['USERID'] . "'";
            } else {
                $where = "Where UserID='" . $_SESSION['USERID'] . "'";
            }
            $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table 
                                $where
			 $order
			 $limit"
            );
        }
        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, "SELECT FOUND_ROWS()"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db, "SELECT COUNT({$primaryKey})
			 FROM   $table"
        );
        $recordsTotal = $resTotalLength[0][0];
        /*
         * Output
         */

        $columnsArr = array_map(function($val) {
            return str_replace('`', '', $val);
        }, $columns);
        return array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columnsArr, $data)
        );
    }

    /**
     * The difference between this method and the simple one, is that you can
     * apply additional where conditions to the SQL queries. These can be in
     * one of two forms:
     *
     * * 'Result condition' - This is applied to the result set, but not the
     *   overall paging information query - i.e. it will not effect the number
     *   of records that a user sees they can have access to. This should be
     *   used when you want apply a filtering condition that the user has sent.
     * * 'All condition' - This is applied to all queries that are made and
     *   reduces the number of records that the user can access. This should be
     *   used in conditions where you don't want the user to ever have access to
     *   particular records (for example, restricting by a login id).
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereResult WHERE condition to apply to the result set
     *  @param  string $whereAll WHERE condition to apply to all queries
     *  @return array          Server-side processing response array
     */
    static function complex($request, $conn, $table, $primaryKey, $columns, $whereResult = null, $whereAll = null) {
        $bindings = array();
        $db = self::db($conn);
        $localWhereResult = array();
        $localWhereAll = array();
        $whereAllSql = '';

        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        $whereResult = self::_flatten($whereResult);
        $whereAll = self::_flatten($whereAll);

        if ($whereResult) {
            $where = $where ?
                    $where . ' AND ' . $whereResult :
                    'WHERE ' . $whereResult;
        }

        if ($whereAll) {
            $where = $where ?
                    $where . ' AND ' . $whereAll :
                    'WHERE ' . $whereAll;

            $whereAllSql = 'WHERE ' . $whereAll;
        }

        // Main query to actually get the data
        $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", self::pluck($columns, 'db')) . "
			 FROM $table
			 $where
			 $order
			 $limit"
        );

        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, "SELECT FOUND_ROWS()"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db, $bindings, "SELECT COUNT({$primaryKey})
			 FROM   $table " .
                        $whereAllSql
        );
        $recordsTotal = $resTotalLength[0][0];

        /*
         * Output
         */
        return array(
            "sEcho" => intval($request['draw']),
            "iTotalRecords" => intval($recordsTotal),
            "iTotalDisplayRecords" => intval($recordsFiltered),
            "aaData" => self::data_output($columns, $data)
        );
    }

    /**
     * Connect to the database
     *
     * @param  array $sql_details SQL server connection details array, with the
     *   properties:
     *     * host - host name
     *     * db   - database name
     *     * user - user name
     *     * pass - user password
     * @return resource Database connection handle
     */
    static function sql_connect($sql_details) {
        try {
            $db = @new PDO(
                    "mysql:host={$sql_details['host']};dbname={$sql_details['db']}", $sql_details['user'], $sql_details['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        } catch (PDOException $e) {
            self::fatal(
                    "An error occurred while connecting to the database. " .
                    "The error reported by the server was: " . $e->getMessage()
            );
        }

        return $db;
    }

    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    static function sql_exec($db, $bindings, $sql = null) {
        // Argument shifting
        if ($sql === null) {
            $sql = $bindings;
        }

        $stmt = $db->prepare($sql);
        //echo $sql;
        // Bind parameters
        if (is_array($bindings)) {
            for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
                $binding = $bindings[$i];
                $stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
            }
        }
        // Execute
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            self::fatal("An SQL error occurred: " . $e->getMessage());
        }

        // Return all
        return $stmt->fetchAll();
    }

    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Internal methods
     */

    /**
     * Throw a fatal error.
     *
     * This writes out an error message in a JSON string which DataTables will
     * see and show to the user in the browser.
     *
     * @param  string $msg Message to send to the client
     */
    static function fatal($msg) {
        echo json_encode(array(
            "error" => $msg
        ));

        exit(0);
    }

    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    static function bind(&$a, $val, $type) {
        $key = ':binding_' . count($a);

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    static function pluck($a, $prop) {

        $out = array();
        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }

    /**
     * Return a string from an array or a string
     *
     * @param  array|string $a Array to join
     * @param  string $join Glue for the concatenation
     * @return string Joined string
     */
    static function _flatten($a, $join = ' AND ') {
        if (!$a) {
            return '';
        } else if ($a && is_array($a)) {
            return implode($join, $a);
        }
        return $a;
    }

    //custom function

    static function cols_without_concat($columns) {
        /* array created for where clause without concatenated string */
        $searchCols = array();
        foreach ($columns as $column) {
            if (strpos($column['db'], "CONCAT") !== false) {
                $col_name = explode("as ", $column['db']);
                $nimblecolname = isset($col_name[1]) ? $col_name[1] : null;
                $searchCols[] = array("db" => $nimblecolname, "dt" => $column['dt']);
            } else {
                $searchCols[] = array("db" => $column['db'], "dt" => $column['dt']);
            }
        }
        return $searchCols;
    }

}
