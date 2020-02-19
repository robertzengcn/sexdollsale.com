<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


if (!class_exists('mailHive')) {

    if (!function_exists('mh_strip_magic_slashes')) {
        function mh_strip_magic_slashes($str)
        {
            return get_magic_quotes_gpc() ? stripslashes($str) : $str;
        }
    }


    // skip when called on mailhive.php
    if (defined('MAILBEEZ_MOUSEFLOW_STATUS') && MAILBEEZ_MOUSEFLOW_STATUS == 'True') {
        // check referrer
        // check session setting
        $mouseflow_do_recording = false;
        if ($_SESSION['MAILBEEZ_MOUSEFLOW_RECORDING'] == 'True') {
            //echo "mouseflow: session";
            $mouseflow_do_recording = true;
        } elseif (mouseflow_check_ip_exclude()) {
            //echo "mouseflow: ip exclude";
            $mouseflow_do_recording = true;
        } elseif (mouseflow_check_channel()) {
            //echo "mouseflow: channel";
            $mouseflow_do_recording = true;
        }

        if ($mouseflow_do_recording) {
            $_SESSION['MAILBEEZ_MOUSEFLOW_RECORDING'] = 'True';
            echo mh_strip_magic_slashes(MAILBEEZ_MOUSEFLOW_CODE);
            //unset( $_SESSION['MAILBEEZ_MOUSEFLOW_RECORDING']);
        }
    }
}

function mouseflow_check_channel()
{
    $page = $_SERVER['REQUEST_URI'];
    if (preg_match("/" . MAILBEEZ_MOUSEFLOW_MAILBEEZ_URL_ID . "/", $page) > 0) {
        $channel = 'MailBeez';
    } else {
        $channel = 'Other';
    }

    $open_channels = array();
    $cfg_array = explode(", ", MAILBEEZ_MOUSEFLOW_CONFIG);
    while (list(, $key) = each($cfg_array)) {
        $open_channels[$key] = 1;
    }

    if (isset($open_channels[$channel]) && $open_channels[$channel] == 1) {
        return true;
    }

    return false;
}

function mouseflow_check_ip_exclude()
{
    $ip = $_SERVER['REMOTE_ADDR'];

    $ip_array = explode("\n", MAILBEEZ_MOUSEFLOW_IP_EXCLUDE);
    foreach ($ip_array as $ip_string) {
        $ip_string = trim($ip_string);
        if ($ip_string == '') {
            continue;
        }
        if (preg_match("/" . $ip_string . "/", $ip) > 0) {
            return true;
        }
    }

    return false;
}


?>