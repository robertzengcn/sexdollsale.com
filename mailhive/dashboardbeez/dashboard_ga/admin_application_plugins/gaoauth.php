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

    $mh_ga_client_id = $_GET['clientid'];
    $mh_ga_client_secret = $_GET['clientsecret'];

    switch ($app_action) {
        case 'config':
            mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_REPORT_GA_CLIENT_ID',
                'configuration_value' => $mh_ga_client_id
            ), true);
            mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_REPORT_GA_CLIENT_SECRET',
                'configuration_value' => $mh_ga_client_secret
            ), true);

            break;
    }
}

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');
mh_redirect($back_url);

?>