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

switch (MH_PLATFORM) {
    case 'oscommerce':
    case 'digistore':
    case 'xtc':
    case 'gambio':
    case 'mercari':
        define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL', 'Add the following code to your "includes/application_bottom.php":
	<pre style="background-color: #fff; text-align: left">
    // MailBeez
    if (defined(\'MAILBEEZ_MOUSEFLOW_STATUS\') && MAILBEEZ_MOUSEFLOW_STATUS == \'True\') {
        require_once(DIR_FS_CATALOG . \'mailhive/configbeez/config_mouseflow/includes/mouseflow_inc.php\');
    }
    // - MailBeez
    </pre>');
        break;
    case 'creloaded':
        define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL', 'Copy the RCI "mailbeez_mouseflow_applicationbottom_bottom.php" located in "extras_creloaded" of this package into the folder<br><br />
        [cre-root]/includes/runtime/applicationbottom/');
        break;
    case 'zencart':
        define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL', 'Add the following code to your "[zencart-root]/includes/modules/footer.php" file:
	<pre style="background-color: #fff; text-align: left">
    // MailBeez
    if (defined(\'MAILBEEZ_MOUSEFLOW_STATUS\') && MAILBEEZ_MOUSEFLOW_STATUS == \'True\') {
        require_once(DIR_FS_CATALOG . \'mailhive/configbeez/config_mouseflow/includes/mouseflow_inc.php\');
    }
    // - MailBeez
    </pre>');
        break;
    default:
}

define('MAILBEEZ_MOUSEFLOW_TEXT_TITLE', 'Mouseflow Realtime Web User Studies Integration');
define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION', 'Integrate Mouseflow into your store and control what is recorded

<div class="tipp"><b>How to Install:</b><br />
' . MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL . '

<br />
<br />
  </div>
<div class="pro">Register now for <a href="http://www.mailbeez.com/documentation/configbeez/config_mouseflow/' . MH_LINKID_1 . '" target="_blank">Mouseflow</a> and start for <b>FREE</b> with this <b>500 Credits-Code</b>:
<br><div style="font-weight: bold; font-size: 14px;text-align:center; padding: 7px; margin-top: 7px; border: 1px solid red; background-color:#fcfcfc;">MAILBEEZ12</div>
</div>
  ');

define('MAILBEEZ_MOUSEFLOW_STATUS_TITLE', 'Activate Mouseflow Integration');
define('MAILBEEZ_MOUSEFLOW_STATUS_DESC', 'Do you want to activate the Mouseflow Integration?');

define('MAILBEEZ_MOUSEFLOW_CONFIG_TITLE', 'Mouseflow Recording is Active For');
define('MAILBEEZ_MOUSEFLOW_CONFIG_DESC', 'Choose the channel you would like to record for');

define('MAILBEEZ_MOUSEFLOW_CODE_TITLE', 'Your Mouseflow Code');
define('MAILBEEZ_MOUSEFLOW_CODE_DESC', 'The complete Mouseflow code as you find it in your Mouseflow administration');

define('MAILBEEZ_MOUSEFLOW_IP_EXCLUDE_TITLE', 'Exclude Visitors Based on IPs');
define('MAILBEEZ_MOUSEFLOW_IP_EXCLUDE_DESC', 'Exclude visitors using these IPs from recordings');

?>