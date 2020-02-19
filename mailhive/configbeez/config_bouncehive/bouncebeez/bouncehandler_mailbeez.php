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

if (defined('MAILBEEZ_BOUNCEHIVE_STATUS') && MAILBEEZ_BOUNCEHIVE_STATUS == 'True') {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_bouncehive_advanced/bouncebeez/bouncehandler_mailbeez.php');
}

?>