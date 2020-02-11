<?php

if (!function_exists('wphp_is_hide_frontpage_page'))
{
    function wphp_is_hide_frontpage_page($args)
    {
        global $wp;
        $ret = false;
        if (!empty($args['wp_query']))
        {

            $ret = ($args['wp_query']->get('page_id') == get_option('page_on_front') && get_option('page_on_front')) || empty($args['wp_query']->query_string);
        }
        return $ret || is_front_page();
    }
}
if (!function_exists('wphp_is_hide_always_page'))
{
    function wphp_is_hide_always_page()
    {

        return (is_front_page() || is_feed() || is_category() || is_tag() || is_date() || is_search());
    }
}
if (!function_exists('wphp_is_nohide_search_page'))
{
    function wphp_is_nohide_search_page()
    {

        return (is_front_page() || is_feed() || is_category() || is_tag() || is_date()) && !is_search();
    }
}

/**
 *
 * @return unknown_type
 */
if (!function_exists('wphp_is_post_front_post'))
{
    function wphp_is_post_front_post()
    {
         return is_front_page() || is_home();
    }
}
/**
 *
 * @return unknown_type
 */

if (!function_exists('wphp_is_post_feed_post'))
{
    function wphp_is_post_feed_post()
    {
        return is_feed();
    }
}
/**
 *
 * @return unknown_type
 */

if (!function_exists('wphp_is_post_category_post'))
{
    function wphp_is_post_category_post()
    {
        return !wphp_is_post_front_post() && !wphp_is_post_feed_post() && is_category();
    }
}
/**
 *
 * @return unknown_type
 */

if (!function_exists('wphp_is_post_tag_post'))
{
    function wphp_is_post_tag_post()
    {
        return !wphp_is_post_front_post() && !wphp_is_post_feed_post() && is_tag();
    }
}
/**
 *
 * @return unknown_type
 */

if (!function_exists('wphp_is_post_author_post'))
{
    function wphp_is_post_author_post()
    {
        return !wphp_is_post_front_post() && !wphp_is_post_feed_post() && is_author();
    }
}
/**
 *
 * @return unknown_type
 */

if (!function_exists('wphp_is_post_archive_post'))
{
    function wphp_is_post_archive_post()
    {
        return !wphp_is_post_front_post() && !wphp_is_post_feed_post() && is_date();
    }
}
/**
 *
 * @return unknown_type
 */

if (!function_exists('wphp_is_post_search_post'))
{
    function wphp_is_post_search_post($args)
    {
        if (isset($args['wp_query']) && $args['wp_query']->is_main_query())
        {
            return is_search();
        }
    }
}
if (!function_exists('wphp_is_post_rel_post'))
{
    function wphp_is_post_rel_post($args)
    {
        return wphp_is_rel_query($args);
    }
}
if (!function_exists('wphp_is_post_recent_post'))
{
    function wphp_is_post_recent_post($args)
    {

        return wphp_is_post_sidebar($args['wp_query']);
    }
}
if (!function_exists('wphp_is_post_sidebar'))
{
    function wphp_is_post_sidebar($wp_query)
    {
        $sidebar = false;
        if (property_exists($wp_query, 'query'))
        {
            $sidebar = !empty($wp_query->query['wphp_inside_recent_post_sidebar']) && $wp_query->query['wphp_inside_recent_post_sidebar'];
        }
        return $sidebar;

    }
}

function wphp_is_rel_query($args)
{
    return isset($args['from_rel_query']) && $args['from_rel_query'];
}
