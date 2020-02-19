<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
$msg = '';
$msg_type = '';


if (mh_not_null($app_action)) {
    $id = $_GET['profile_id'];

    switch ($app_action) {
        case 'set':
            mh_insert_config_value(array('configuration_title' => 'Profile ID',
                'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID',
                'configuration_value' => $id,
                'configuration_description' => 'This profile ID will only be stored in your Shop Database (not encrypted)',
                'set_function' => ''
            ), true);
            break;
    }
}

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');
mh_redirect($back_url);

?>