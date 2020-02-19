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


function mh_update_reminder_timestamp()
{
    // update reminder timestamp
    $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP'");
    if (mh_db_num_rows($check_query)) {
        $check = mh_db_fetch_array($check_query);
        if (MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP < time()) {
            mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . (time() + (7 * 24 * 60 * 60)) . "', last_modified = now() where configuration_key = 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP'");
        }
    } else {
        if (!defined('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP')) {
            $wait_first_time = 60;
            mh_insert_config_value(array('configuration_title' => 'Update Reminder Timestamp',
                'configuration_key' => 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP',
                'configuration_value' => time() + $wait_first_time,
                'configuration_description' => 'This is automatically updated. No need to edit.',
                'set_function' => ''
            ));
            define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP', time() + $wait_first_time);
        }
    }
}

function mh_update_check_timestamp()
{
    // update check timestamp
    $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MAILBEEZ_MAILHIVE_UPDATE_CHECK_TIMESTAMP'");
    if (mh_db_num_rows($check_query)) {
        $check = mh_db_fetch_array($check_query);
        mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . (time() + (7 * 24 * 60 * 60)) . "', last_modified = now() where configuration_key = 'MAILBEEZ_MAILHIVE_UPDATE_CHECK_TIMESTAMP'");
    } else {
        if (!defined('MAILBEEZ_MAILHIVE_UPDATE_CHECK_TIMESTAMP')) {
            mh_insert_config_value(array('configuration_title' => 'Update Check Timestamp',
                'configuration_key' => 'MAILBEEZ_MAILHIVE_UPDATE_CHECK_TIMESTAMP',
                'configuration_value' => time(),
                'configuration_description' => 'This is automatically updated. No need to edit.',
                'set_function' => ''
            ));
        }
    }
}

function mh_version_check_inline()
{
    $check_result_content = mh_versioncheck_getCurlContent(MAILBEEZ_VERSION_CHECK_URL . '&api=true');
    preg_match('/###(.*)###/', $check_result_content, $matches);
    list($check_result_upd_ser, $check_result_new_ser) = explode("#", $matches[1]);
    $check_result_upd_array = unserialize($check_result_upd_ser);
    $check_result_new_array = unserialize($check_result_new_ser);
    //print_r($check_result_upd_array);
    //print_r($check_result_new_array);
    //exit();

    foreach ($check_result_upd_array as $key => $value) {
        $_SESSION['mailbeez_upd_cnt'][$key] = count($check_result_upd_array[$key]);
        $_SESSION['mailbeez_upd_cnt_sum'] += count($check_result_upd_array[$key]);
        foreach ($check_result_upd_array[$key] as $key2 => $value2) {
            $_SESSION['mailbeez_upd'][$key2] = $value2['version'];
        }
    }
    foreach ($check_result_new_array as $key => $value) {
        $_SESSION['mailbeez_new_cnt'][$key] = count($check_result_new_array[$key]);
        $_SESSION['mailbeez_new_cnt_sum'] += count($check_result_new_array[$key]);
        foreach ($check_result_new_array[$key] as $key2 => $value2) {
            $_SESSION['mailbeez_new'][$key][$key2] = $value2;
        }
    }

    preg_match('/#!#(.*)#!#/', $check_result_content, $matches);
    $_SESSION['mailbeez_upd_msg'] = unserialize($matches[1]);

    mh_update_check_timestamp();
}

function mh_version_check_clear()
{
    unset($_SESSION['mailbeez_upd_cnt']);
    unset($_SESSION['mailbeez_new_cnt']);
    unset($_SESSION['mailbeez_upd_cnt_sum']);
    unset($_SESSION['mailbeez_new_cnt_sum']);
    unset($_SESSION['mailbeez_upd']);
    unset($_SESSION['mailbeez_new']);
    unset($_SESSION['mailbeez_upd_msg']);
}

function mh_versioncheck_getCurlContent($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $content = curl_exec($ch);

    if (empty($content)) {
        print curl_error($ch);
    } else {
        $info = curl_getinfo($ch);
        //print_r($info);
    }

    curl_close($ch);
    return $content;
}


function mh_check_module_versions()
{

    $release_module_compatibility = array();
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/version/module_compatibility.php');

    $output = '';
    foreach ($release_module_compatibility as $beez_type => $module_type_array) {

        $installed_version = false;
        foreach ($module_type_array as $module_code => $module_info_array) {
            switch ($beez_type) {
                case 'mailbeez':
                    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez.php');
                    $installed_version = mailbeez::get_installed_version($module_code);
                    break;
                case 'filterbeez':
                    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/filterbeez.php');
                    $installed_version = filterbeez::get_installed_version($module_code);
                    break;
                case 'reportbeez':

                    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/reportbeez.php');
                    $installed_version = reportbeez::get_installed_version($module_code);
                    break;
                case 'configbeez':

                    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');
                    $installed_version = configbeez::get_installed_version($module_code);
                    break;
                case 'dashboardbeez':

                    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');
                    $installed_version = dashboardbeez::get_installed_version($module_code);
                    break;

            }
            // check against the installed version
            if ($installed_version > 1 && $module_info_array['version'] > $installed_version) {
                // load module
                // load language file
                // output module title, link, download info -> avangate

                if (!is_object($GLOBALS[$module_code])) {
                    include_once(MH_DIR_FS_CATALOG . 'mailhive/' . $beez_type . '/' . $module_code . MH_FILE_EXTENSION);
                    if (class_exists($module_code)) {
                        $module_directory_current = MH_DIR_FS_CATALOG . 'mailhive/' . $beez_type . '/';
                        $module_path = mh_get_class_path($module_code);
                        mh_load_modules_language_files($module_directory_current, $module_path, MH_FILE_EXTENSION);
                        $GLOBALS[$module_code] = new $module_code;
                    }
                }

                if ($GLOBALS[$module_code]->title) {
                    $output .= '<li><a href="' . mh_language_documentation_url($GLOBALS[$module_code]->documentation_root . $GLOBALS[$module_code]->documentation_key) . '" target="_blank">' . $GLOBALS[$module_code]->title . ' (' . $installed_version . ')</a></li>';
                }
            }
        }
    }
    if ($output != '') {
        $output = '<ul>' . $output . '</ul>';
    }

    return $output;
}


?>