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
        define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL', '
	folgenden Code in "includes/application_bottom.php" einf&uuml;gen:
	<pre style="background-color: #fff; text-align: left">
    // MailBeez
    if (defined(\'MAILBEEZ_MOUSEFLOW_STATUS\') && MAILBEEZ_MOUSEFLOW_STATUS == \'True\') {
        require_once(DIR_FS_CATALOG . \'mailhive/configbeez/config_mouseflow/includes/mouseflow_inc.php\');
    }
    // - MailBeez
    </pre>');
        break;
    case 'creloaded':
        define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL', 'Das RCI module "mailbeez_cron_simple_applicationbottom_bottom.php"  welches sich im Ordner "extras_creloaded" dieses Downloads befindet in das folgende Verzeichnis kopieren<br><br />
        [cre-root]/includes/runtime/applicationbottom/');
        break;
    case 'zencart':
        define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL', '
	folgenden Code in "[zencart-root]/includes/modules/footer.php" einf&uuml;gen:
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

define('MAILBEEZ_MOUSEFLOW_TEXT_TITLE', 'Mouseflow Realtime web user studies Integration');
define('MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION', 'Integriere Mouseflow in das Shopsystem und konfiguriere f&uuml;r welche Benutzer Mouseflow aktiv ist

<div class="tipp"><b>So geht\'s:</b><br />
' . MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION_INSTALL . '

<br />
<br />
  </div>

<div class="pro">Jetzt f&uuml;r <a href="http://www.mailbeez.com/documentation/configbeez/config_mouseflow/' . MH_LINKID_1 . '" target="_blank">Mouseflow</a> registrieren und
mit dem folgenden  <b>500 Credits-Code</b>  kostenlos starten:
<br><div style="font-weight: bold; font-size: 14px;text-align:center; padding: 7px; margin-top: 7px; border: 1px solid red; background-color:#fcfcfc;">MAILBEEZ12</div>
</div>
  ');

define('MAILBEEZ_MOUSEFLOW_STATUS_TITLE', 'Mouseflow Integration aktivieren');
define('MAILBEEZ_MOUSEFLOW_STATUS_DESC', 'Soll die Mouseflow Integration aktiv sein?');

define('MAILBEEZ_MOUSEFLOW_CONFIG_TITLE', 'Mouseflow auf Kan&auml;le begrenzen');
define('MAILBEEZ_MOUSEFLOW_CONFIG_DESC', 'Mouseflow nimmt das Benutzerverhalten f&uuml;r Besucher auf, die &uuml;ber folgende Quellen (links) kommen:');

define('MAILBEEZ_MOUSEFLOW_CODE_TITLE', 'Dein Mouseflow Code');
define('MAILBEEZ_MOUSEFLOW_CODE_DESC', 'Der komplette Mouseflow wie er in der Mouseflow Administration zu finden ist');

define('MAILBEEZ_MOUSEFLOW_IP_EXCLUDE_TITLE', 'Besucher mit diesen IPs ausschliessen');
define('MAILBEEZ_MOUSEFLOW_IP_EXCLUDE_DESC', 'Das Benutzerverhalten f&uuml;r Besucher mit diesen IP-Adressen wird nicht aufgenommen, z.B. Admins');

?>