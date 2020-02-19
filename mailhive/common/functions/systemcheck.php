<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2013 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.2

  version check functions
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


function mh_system_check()
{
    if (defined('MH_SYSTEMCHECK_DISABLED')) {
        return false;
    }

    $output = false;

    // cache
    if (isset($_SESSION['mh_system_check'])) {
        return $_SESSION['mh_system_check'];
    }

    $output .= mh_system_check_db();
    $output .= mh_system_check_php();

    $_SESSION['mh_system_check'] = $output;

    return $output;
}

function mh_system_check_refresh()
{
    unset($_SESSION['mh_system_check']);
}


function mh_system_check_db()
{
    if ((defined('MH_SYSTEMCHECK_DB_DISABLED') && MH_SYSTEMCHECK_DB_DISABLED == 'True') ||
        (defined('MAILBEEZ_SERVICE_DB_REPAIR_ORDER_STATUS') && MAILBEEZ_SERVICE_DB_REPAIR_ORDER_STATUS == 'True')
    ) {
        return false;
    }

    $output = '';
    // check for corrupted DB
    $query_sql = "select o.orders_id, o.orders_status, o.orders_date_finished, o.last_modified
                                from " . TABLE_ORDERS . " o
                                    left outer join " . TABLE_ORDERS_STATUS_HISTORY . " osh
                                    on (o.orders_id = osh.orders_id and o.orders_status = osh.orders_status_id)
                              where osh.orders_id is null";

    $query = mh_db_query($query_sql);

    if ($rows = mh_db_num_rows($query)) {
        $output .= '<li>' . sprintf(MAILBEEZ_SYSTEM_CHECK_MSG_CORRUPTED_DB, $rows) . '</li>';
    }

    return $output;
}


function mh_system_check_php()
{
    if (defined('MH_SYSTEMCHECK_PHP_DISABLED')) {
        return false;
    }

    $output = '';

    if (!function_exists('mb_detect_encoding')) {
        $output .= '<li>' . MAILBEEZ_SYSTEM_CHECK_MSG_PHP_MBSTRING . '</li>';
    }

    if (ini_get('suhosin.executor.disable_eval')) {
        $output .= '<li>' . MAILBEEZ_SYSTEM_CHECK_MSG_PHP_SUHOSIN_EVAL . '</li>';
    }
    if (stristr(ini_get('suhosin.executor.eval.blacklist'), 'gzinflate')) {
        $output .= '<li>' . MAILBEEZ_SYSTEM_CHECK_MSG_PHP_SUHOSIN_EVAL_GZINFLATE . '</li>';
    }

    // other potential issues with suhosin:
    // ini_get('suhosin.get.max_value_length')
    // ini_get('suhosin.memory_limit')

    return $output;
}


?>