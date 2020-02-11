<?php

if (!function_exists('print_nice'))
{
    function print_nice($rr, $d = false, $extra = "")
    {
        if ($d)
        {
            return ($extra ? "<pre>" . $extra . "</pre>" : '') . "<pre>" . print_r($rr, true) . "</pre>";
        }
        else
        {
            echo (($extra ? "<pre>" . $extra . "</pre>" : '') . "<pre>" . print_r($rr, true) . "</pre>");
        }
    }
}
if (!function_exists('p_n'))
{
    function p_n($rr, $d = false)
    {
        $bt = debug_backtrace();

        $caller1 = $bt[0];
        $caller2 = @$bt[1];

        $caller1['file'] = str_replace(WPHP_PLUGIN_DIR, "", $caller1['file']);
        $str             = $caller1['file'] . "@" . @$caller2['function'] . "():$caller1[line]";

        print_nice($rr, $d, $str);
    }
}
if (!function_exists('p_d'))
{
    function p_d($rr, $d = false)
    {
        $bt = debug_backtrace();

        $caller1 = $bt[0];
        $caller2 = @$bt[1];

        $caller1['file'] = str_replace(WPHP_PLUGIN_DIR, "", @$caller1['file']);
        $str             = $caller1['file'] . "@" . @$caller2['function'] . "():" . @$caller1['line'];

        if ($d)
        {
            ob_start();
            var_dump($rr);
            $rr = ob_get_clean();
            $d  = false;
        }
        print_nice($rr, $d, $str);
        die('');
    }
}
if (!function_exists('p_c'))
{
    function p_c($msg)
    {
        $bt = debug_backtrace();

        $caller1 = $bt[0];
        $caller2 = @$bt[1];

        $caller1['file'] = str_replace(WPHP_PLUGIN_DIR, "", $caller1['file']);
        $str             = microtime(true) . "-" . $caller1['file'] . "@" . @$caller2['function'] . "():$caller1[line]" . "-->";
        $msg             = json_encode($msg);
        echo ("<script>console.log('$str' );console.log($msg)</script>");
    }
}
if (!function_exists('p_l'))
{
    function p_l($msg, $dump = false)
    {
        if (!defined('WPHP_DEBUG') || !WPHP_DEBUG)
        {
            return;
        }
        $bt = debug_backtrace();

        $caller1 = $bt[0];
        $caller2 = @$bt[1];

        $caller1['file'] = str_replace(WPHP_PLUGIN_DIR, "", $caller1['file']);
        $str             = microtime(true) . "-" . $caller1['file'] . "@" . @$caller2['function'] . "():$caller1[line]" . "-->";

        error_log($str);

        if ($dump)
        {
            ob_start();
            var_dump($msg);
            $rr = ob_get_clean();
        }
        else
        {
            $rr = print_r($msg, 1);
        }

        error_log($rr);

    }
}
if (!function_exists('_wphp_http_post'))
{
    function _wphp_http_post($var, $default = null)
    {
        if (isset($_POST[$var]))
        {
            return $_POST[$var];
        }
        else
        {
            return $default;
        }
    }
}
if (!function_exists('wphp_allowed_post_types'))
{
    function wphp_allowed_post_types($joined = false)
    {
        static $post_types, $post_types_joined;
        if (!$post_types)
        {
            $post_types = wp_hide_post()->pluginAdmin()->allowedPostTypes();

        }
        if ($joined)
        {
            return $post_types_joined ? $post_types_joined : "post_type  in ('" . implode("','", $post_types) . "')";
        }
        else
        {
            return $post_types;
        }

    }
}
if (!function_exists('wphp_is_applicable'))
{
    function wphp_is_applicable($item_type, $wp_query = null)
    {

        $types = array_flip(wphp_allowed_post_types());
        unset($types['page']);
        $ret   = 0;
        $types = array_flip($types);

        if (wphp_is_post_sidebar($wp_query))
        {
            //p_n(__LINE__);
            $ret = 1;
        }
        elseif (is_admin() || is_singular())
        {

            if (@is_front_page())
            {
                $ret = 4;
            }
            else
            {
                $ret = 0;
            }
        }
        elseif (in_array($item_type, $types) || $item_type == 'page')
        {
            // p_n(__LINE__);
            $ret = 2;
        }
        else
        {

            $ret = 0;
        }
        p_l($ret);
        return $ret;

    }
}

if (!function_exists('wphp_'))
{
    function wphp_($text)
    {
        return __($text, 'wp-hide-post');
    }
}
if (!function_exists('wphp_is_demo'))
{
    function wphp_is_demo()
    {

        return $_SERVER['HTTP_HOST'] == 'wphidepost.loc';
    }
}
if (!function_exists('wphp_get_setting'))
{
    function wphp_get_setting($section, $option = false, $default = false)
    {
        static $default_setting;
        if (!$default_setting)
        {
            $default_setting = wphp_get_default_setting();
        }
        $options = get_option($section);

        if (!$option)
        {
            return $options;
        }
        if (isset($options[$option]))
        {
            return $options[$option];
        }
        elseif ($default)
        {

            if (isset($default_setting[$option]))
            {
                return $default_setting[$option];
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }
}
if (!function_exists('wphp_visibility_types'))
{
    function wphp_visibility_types($joined = false)
    {
        static $post_visibility_joined;
        $post_visibility = wp_hide_post()->pluginAdmin()->get_post_visibility_types();
        return $post_visibility;
        if ($joined)
        {
            if ($post_visibility_joined)
            {
                return $post_visibility_joined;
            }
            else
            {
                if (!count($post_visibility_joined))
                {
                    $post_visibility_joined = "";
                }
                else
                {
                    $post_visibility_joined = "in ('" . implode("','", array_keys($post_visibility)) . "')";
                }
                return $post_visibility_joined;
            }
        }
        else
        {
            return $post_visibility;
        }
    }
}

if (!function_exists('array_column'))
{
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc   = func_num_args();
        $params = func_get_args();
        if ($argc < 2)
        {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0]))
        {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        )
        {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        )
        {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput     = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey  = null;
        if (isset($params[2]))
        {
            if (is_float($params[2]) || is_int($params[2]))
            {
                $paramsIndexKey = (int) $params[2];
            }
            else
            {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row)
        {
            $key    = $value    = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row))
            {
                $keySet = true;
                $key    = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null)
            {
                $valueSet = true;
                $value    = $row;
            }
            elseif (is_array($row) && array_key_exists($paramsColumnKey, $row))
            {
                $valueSet = true;
                $value    = $row[$paramsColumnKey];
            }
            if ($valueSet)
            {
                if ($keySet)
                {
                    $resultArray[$key] = $value;
                }
                else
                {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }
}

function wphp_get_host()
{
    $host = false;

    if (defined('WPE_APIKEY'))
    {
        $host = 'WP Engine';
    }
    elseif (defined('PAGELYBIN'))
    {
        $host = 'Pagely';
    }
    elseif (DB_HOST == 'localhost:/tmp/mysql5.sock')
    {
        $host = 'ICDSoft';
    }
    elseif (DB_HOST == 'mysqlv5')
    {
        $host = 'NetworkSolutions';
    }
    elseif (strpos(DB_HOST, 'ipagemysql.com') !== false)
    {
        $host = 'iPage';
    }
    elseif (strpos(DB_HOST, 'ipowermysql.com') !== false)
    {
        $host = 'IPower';
    }
    elseif (strpos(DB_HOST, '.gridserver.com') !== false)
    {
        $host = 'MediaTemple Grid';
    }
    elseif (strpos(DB_HOST, '.pair.com') !== false)
    {
        $host = 'pair Networks';
    }
    elseif (strpos(DB_HOST, '.stabletransit.com') !== false)
    {
        $host = 'Rackspace Cloud';
    }
    elseif (strpos(DB_HOST, '.sysfix.eu') !== false)
    {
        $host = 'SysFix.eu Power Hosting';
    }
    elseif (strpos($_SERVER['SERVER_NAME'], 'Flywheel') !== false)
    {
        $host = 'Flywheel';
    }
    else
    {
        // Adding a general fallback for data gathering
        $host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
    }

    return $host;
}

function scb_custom_post_types($output = 'objects')
{

    $args = array(
        'public'   => true,
        '_builtin' => true,
    );

    $operator = 'or'; // 'and' or 'or'

    $types = (array) get_post_types($args, $output, $operator);
    unset($types['revision']);
    unset($types['nav_menu_item']);
    unset($types['attachment']);

    return empty($types) || !is_array($types) ? array() : $types;

}

function wphp_ispro()
{
    return (defined('WPHP_PRO') && WPHP_PRO) || wp_hide_post()->info('id') == 'wp-hide-post-pro';
}
