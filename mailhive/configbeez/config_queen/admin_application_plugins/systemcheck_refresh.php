<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6.3
 */


///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
if ($app_action == 'systemcheck_refresh') {
    $back_url = mh_href_link(FILENAME_MAILBEEZ, '');

    mh_system_check_refresh();
    mh_redirect($back_url);
}
?>
