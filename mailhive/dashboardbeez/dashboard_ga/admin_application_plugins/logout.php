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

    switch ($app_action) {
        case 'logout':
            mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN',
                'configuration_value' => ''
            ), true);

            mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID',
                'configuration_value' => ''
            ), true);
            unset($_SESSION['mh_ga_token']);
            break;
    }
}

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');
mh_redirect($back_url);

?>