<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6
 */

///////////////////////////////////////////////////////////////////////////////
///                                                                          //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

if (substr(DIR_FS_CATALOG, -1) != '/') {
    define('MH_DIR_FS_CATALOG', DIR_FS_CATALOG . '/');
} else {
    define('MH_DIR_FS_CATALOG', DIR_FS_CATALOG);
}

if (MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO == 'True') {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/clicktracker.php');
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');
    $mailbeez_clicktracker = new mb_clicktracker();
    $mailbeez_clicktracker->process();
}

if (isset($_GET['mid'])) {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/clicktracker.php');
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');
    $mailbeez_clicktracker = new mb_clicktracker();
    $mailbeez_clicktracker->record_click($_GET['mid'], $_SERVER['REQUEST_URI']);
}

if (isset($_SESSION['mailbeez_message_id']) && preg_match('/' . FILENAME_CHECKOUT_SUCCESS . '/', $_SERVER['PHP_SELF'])) {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/clicktracker.php');
    require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');
    $mailbeez_clicktracker = new mb_clicktracker();
    $mailbeez_clicktracker->record_order();
}
?>