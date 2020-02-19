<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// obsolete

/*
require_once(MH_DIR_FS_CATALOG . 'mailhive/reportbeez/report_ga/classes/gapi-1.3/gapi.class.php');

$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
$msg = '';
$msg_type = '';


if (mh_not_null($app_action)) {

    $ga_email = $_GET['email'];
    $ga_password = $_GET['password'];

    switch ($app_action) {
        case 'login':
            try {
                $ga = new gapi($ga_email, $ga_password);
            }
            catch (Exception $e) {
                $back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home&ga_login_error=true');
                mh_redirect($back_url);
            }

            $mh_gh_token = $ga->getAuthToken();

            mh_insert_config_value(array('configuration_title' => 'GA Token',
                'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN',
                'configuration_value' => $mh_gh_token,
                'configuration_description' => 'This token gives access to GA',
                'set_function' => ''
            ), true);

            break;
    }
}

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');
mh_redirect($back_url);
'
*/

?>