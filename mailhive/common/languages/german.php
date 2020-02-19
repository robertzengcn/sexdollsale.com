<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired und in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

mh_define('MH_INSTALL_INTRO', 'Bitte installiere MailHive mit Klick auf den Button');
mh_define('MH_INSTALL_SUPPORT', 'Solltest du Schwierigkeiten mit der Installation haben, bitte die  <a href="http://www.mailbeez.com/documentation/installation/" target="_blank"><b><u>Installations Anleitung</u></b></a> lesen.<br />
									<br />
									Nachdem du dies mindestens 3x gemacht hast, darfst du gerne den <a href="http://www.mailbeez.com/support/" target="_blank"><b><u>MailBeez-Support</u></b></a> nutzen ;-)');

mh_define('MH_RATE_TRUSTPILOT_LINK', 'bitte bewerte MailBeez auf Trustpilot');

mh_define('MH_SECURE_URL', 'Sichere Cronjob-URL (f&uuml;hrt sofort alle aktiven MailBeez Module aus - im konfiguierten Mode)');

mh_define('MH_BUTTON_VERSION_CHECK', 'Pr&uuml;fe auf Updates');
mh_define('MH_BUTTON_BACK_CONFIGURATION', 'zur Konfiguration');
mh_define('MH_BUTTON_BACK_DASHBOARD', 'zum Dashboard');
mh_define('MH_BUTTON_BACK_REPORTS', 'zu Berichten');

mh_define('MH_DASHBOARD_CONFIG', 'konfigurieren');
mh_define('MH_DASHBOARD_REMOVE', 'x');

mh_define('MH_HEADING_TITLE', 'MailBeez - Einfaches Automatisches Emailmarketing');
mh_define('TEXT_DOCUMENTATION', 'Dokumentation verf&uuml;gbar');
mh_define('TEXT_VIEW_ONLINE', 'Online ansehen');
mh_define('TEXT_UPGRADE_MAILBEEZ', 'Diese Modul erfordert MailBeez Version %s or h&ouml;her. Bitte MailBeez updaten.');
mh_define('WARNING_SIMULATE', 'SIMULATION-MODE: es werden keine Emails verschickt');
mh_define('WARNING_OFFLINE', 'DISABLED: MailBeez werden nicht ausgef&uuml;hrt');

mh_define('MH_NO_MODULE', 'Keine Module vorhanden.');

mh_define('MH_TAB_HOME', 'Dashboard');
mh_define('MH_TAB_MAILBEEZ', 'MailBeez Module');
mh_define('MH_TAB_CONFIGURATION', 'Konfiguration');
mh_define('MH_TAB_FILTER', 'Filter &amp; Hilfsmodule');
mh_define('MH_TAB_REPORT', 'Berichte');
mh_define('MH_TAB_ABOUT', '&Uuml;ber MailBeez');
mh_define('MH_HEADER_DASHBOARD_MODULES', 'Dashboard Module');
mh_define('MH_MSG_EMPTY_DASHBOARD_AREA', 'Hier ist Platz f&uuml;r ein Dashboard-Modul');

mh_define('MH_HOME_ACTIONS', 'Aktionen');
mh_define('MH_HOME_RESOURCES', 'Mehr Informationen');

mh_define('MH_DOWNLOAD_LINK_LIST', 'Finde weitere MailBeez Module...');
mh_define('MH_DASHBOARD_CONFIG_BUTTON', 'Dashboard konfigurieren');

// config
mh_define('MAILBEEZ_MAILHIVE_TEXT_TITLE', 'MailHive - Grundeinstellungen');
if (MAILBEEZ_CONFIG_INSTALLED == 'config_queen.php' && MAILBEEZ_INSTALLED == '') {
    mh_define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Grundeinstellungen von MailHive.');
} else {
    mh_define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Grundeinstellungen von MailHive. <br />
		<br />MailHive kann erst entfernt werden, wenn alle installierten Module entfernt wurden.');
}

mh_define('MAILBEEZ_MAILHIVE_STATUS_TITLE', 'Modul aktivieren?');
mh_define('MAILBEEZ_MAILHIVE_STATUS_DESC', 'MailHive und MailBeez aktivieren');

mh_define('MAILBEEZ_MAILHIVE_COPY_TITLE', 'Kopie verschicken');
mh_define('MAILBEEZ_MAILHIVE_COPY_DESC', 'Eine Kopie der generierten Emails verschicken?');

mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_TITLE', 'Kopie verschicken an');
mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_DESC', 'Email-Adresse, an die eine Kopie gehen soll');

mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_TITLE', 'Max. Anzahl an Kopien');
mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_DESC', 'Maximale Anzahl an Kopien per Modul');

mh_define('MAILBEEZ_MAILHIVE_TOKEN_TITLE', 'Sicherheits-Schl&uuml;ssel');
mh_define('MAILBEEZ_MAILHIVE_TOKEN_DESC', 'Teil der URL, um unberechtigen Aufruf zu vermeiden. Automatisch generiert oder eigenen Schl&uuml;ssel angeben.');

mh_define('MAILBEEZ_MAILHIVE_POPUP_MODE_TITLE', 'Popup Fenster');
mh_define('MAILBEEZ_MAILHIVE_POPUP_MODE_DESC', 'Bitte nur &auml;ndern, falls die AJAX (CeeBox) Popups nicht richtig &ouml;ffnen - z.B. bei der Vorschau der Vorlagen');

mh_define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TITLE', 'Versions-Check Erinnerung');
mh_define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_DESC', 'Automatisch erinnern, auf neue Version zu pr&uuml;fen?');

mh_define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_TITLE', '"Early Check" aktivieren');
mh_define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_DESC', 'Falls vom Modul verwendet, kann der "Early Check" aktiviert oder deaktiviert werden. "Early Check" entfernt schon bei Generieren der Sende-Liste Empf&auml;nger, welche bereits eine Email erhalten haben.');


// config_dashboard
mh_define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_TITLE', 'Dashboard Konfiguration');
mh_define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_DESCRIPTION', 'W&auml;hle, wie dein MailBeez Dashboard aussehen soll');

mh_define('MAILBEEZ_CONFIG_DASHBOARD_START_TITLE', 'Start-Tab');
mh_define('MAILBEEZ_CONFIG_DASHBOARD_START_DESC', 'Mit welchem Tab starten? (empfohlen: home)');


// config_googleanalytics
mh_define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_TITLE', 'Google Analytics Integration f&uuml;r Automatische Kampagnen');
mh_define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_DESCRIPTION', 'Konfiguration f&uuml;r Google Analytics URL Rewrite.<br /><br />
	<img src="' . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . "/mailhive/common/images/ga.png" . '" width="181" height="33" alt="" border="0" align="absmiddle" hspace="1">');

mh_define('MAILBEEZ_MAILHIVE_GA_ENABLED_TITLE', 'Google Analytics Integration aktiv');
mh_define('MAILBEEZ_MAILHIVE_GA_ENABLED_DESCRIPTION', 'Google Analytics Integration (automatisches Umschreiben von Links) aktivieren?');

mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_TITLE', 'Google Analytics Umschreib-Modus');
mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_DESC', 'Welche Links sollen umgebschrieben werden?');

mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_FORMAT_TITLE', 'Google Analytics URls in TXT Format umschreiben');
mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_FORMAT_DESC', 'In TXT Emails die URLs umschreiben?');

mh_define('MAILBEEZ_MAILHIVE_GA_MEDIUM_TITLE', 'Google Analytics Kampagne "Medium"');
mh_define('MAILBEEZ_MAILHIVE_GA_MEDIUM_DESC', 'Welches "Medium" soll f&uuml;r die Google Analytics Kampagnen genutzt werden? (standard: email)');

mh_define('MAILBEEZ_MAILHIVE_GA_SOURCE_TITLE', 'Google Analytics Kampagne "Source"');
mh_define('MAILBEEZ_MAILHIVE_GA_SOURCE_DESC', 'Welche "Source" soll f&uuml;r die Google Analytics Kampagnen genutzt werden? (standard: MailBeez)');

// config_simulation
mh_define('MAILBEEZ_CONFIG_SIMULATION_TEXT_TITLE', 'Simulation');
mh_define('MAILBEEZ_CONFIG_SIMULATION_TEXT_DESCRIPTION', 'Einstellungen f&uuml;r MailBeez Advanced Simulations.<br />
	<br />Advanced Simulations erlaubt, vollst&auml;ndige und realistische Simulationen inkl. Versand-Tracking durchzuf&uuml;hren.
	Simulations Emails werden NICHT an Kunden geschickt (auch wenn es so aussieht), sondern nur an die konfigurierte Email Adresse');

mh_define('MAILBEEZ_MAILHIVE_MODE_TITLE', 'Betriebsart');
mh_define('MAILBEEZ_MAILHIVE_MODE_DESC', 'Zum Testen bitte "Simulation" w&auml;hlen, in "Produktion" werden Emails an Kunden verschickt!');

mh_define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_TITLE', 'Email-Adresse f&uuml;r Simulationen');
mh_define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_DESC', 'An welche Email-Adresse sollen die Simulations-Emails geschickt werden?');

mh_define('MAILBEEZ_CONFIG_SIMULATION_COPY_TITLE', 'Kopie-Versand bei Simulation');
mh_define('MAILBEEZ_CONFIG_SIMULATION_COPY_DESC', 'Kopien an die konfigurierte Kopie-Adresse (' . MAILBEEZ_MAILHIVE_EMAIL_COPY . ') auch im Simulations Modus verschicken?');

mh_define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_TITLE', 'Simulations-Protokollierung');
mh_define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_DESC', 'Im Simulations Modus den Versand protokollieren? Zum Neu-Start von Simulationen das Simulations-Protokoll l&ouml;schen');


// config_template_engine
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_TITLE', 'Template System');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_DESCRIPTION', 'Konfiguration der Smarty Template Engine.<br />
	<br />	<a href="http://www.smarty.net" target="_blank"><img src="' . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . "/mailhive/common/images/smarty_icon.gif" . '" width="88" height="31" alt="" border="0" align="absmiddle" hspace="1"></a>');

mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_TITLE', 'Kompatibilit&auml;t des Template-Systems');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_DESC', '"True" f&uuml;r Unterst&uuml;tzung von 1.x MailBeez Templates');

mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_TITLE', 'Pfad zu Smarty');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_DESC', 'Pfad zum Smarty Template system /Smarty.class.php<br>im Ordner <br />mailhive/common/classes/');

// config_event_log
mh_define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_TITLE', 'Ereignis Protokollierung');
mh_define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_DESCRIPTION', 'Einstellungen f&uuml;r die Protokollierung von Ereignissen bei der Ausf&uuml;hrung von MailBeez.');


// about
mh_define('MH_ABOUT', '<b style="font-size: 20px; font-weight: bold;">&Uuml;ber MailBeez ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '') . '</b><br /><br />
	MailBeez Version ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '') . ',	erkannte Plattform: <b>' . MH_PLATFORM . '</b><br />
	
	Entwickelt von: Cord F. Rosted <a href="mailto:' . MAILBEEZ_CONTACT_EMAIL . '">' . MAILBEEZ_CONTACT_EMAIL . '</a> <br />
	(kontakt auf Deutsch, English, Dansk)');

mh_define('MH_ABOUT_BUTTONS_FEATURE', 'Funktionen anfragen');
mh_define('MH_ABOUT_BUTTONS_RATE_READ', 'Bewertungen lesen');
mh_define('MH_ABOUT_BUTTONS_RATE_RATE', 'Eigene Bewertung abgeben');

$trustpilot_evaluate = 'http://www.trustpilot.de/evaluate/www.mailbeez.com';


mh_define('MH_MAILBEEZ_LOVE', 'Dir gef&auml;llt MailBeez?');
mh_define('MH_MAILBEEZ_LOVE_TEXT', 'Haben die fleissigen MailBeez geholfen, deine alten Kunden wiederzugewinnen? <br />
	Und zu mehr Umsatz durch die vielen Produktbewertungen?	
	<br /><br />
	Du darst gerne den MailBeez mit einer Spende danken - und Dich auf die Weiterentwicklung freuen.');

mh_define('MH_MAILBEEZ_LOVE_BTN', 'btn_donate_DE.gif');


// new with MailBeez V2.1

mh_define('MH_VERSIONCHECK_INFO_DASHBOARD', 'Es gibt neue Dashboard Module und/oder neu Versionen. Bitte in der Dashboard Konfiguration pr&uuml;fen.');
mh_define('MH_VERSIONCHECK_INFO_NEW', 'Diese %s Module sind noch nicht installiert');
mh_define('MH_VERSIONCHECK_INFO_NEW_MORE', 'mehr Information');
mh_define('MH_VERSIONCHECK_INFO_NEWVERSION', 'Neue Version');


// new with MailBeez V2.2
// config_process_control
mh_define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_TITLE', 'MailHive Prozess Kontrolle');
mh_define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_DESCRIPTION', 'Einstellungen f&uuml;r Prozess Kontrolle Control Settings - Nur f&uuml;r Nerds!');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TITLE', 'MailHive Prozess Kontrolle aktiv?');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_DESC', 'MailHive Prozess Kontrolle ist aktiv bei True (empfohlen).');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_TITLE', 'Lock Periode');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_DESC', 'Lock Periode in Sekunden.');

// action plugin view templates
mh_define('MAILBEEZ_ACTION_VIEW_TEMPLATE_HEADLINE', 'Email Templates');
mh_define('MAILBEEZ_ACTION_VIEW_TEMPLATE_TEXT', 'Vorschau der Email Templates:');
mh_define('MAILBEEZ_BUTTON_VIEW_HTML', 'HTML');
mh_define('MAILBEEZ_BUTTON_VIEW_TXT', 'TXT');
mh_define('MAILBEEZ_BUTTON_VIEW_HTML_RESPONSIVE', 'Mobil');

// action plugin list recipients
mh_define('MAILBEEZ_ACTION_LIST_RECIPIENTS_HEADLINE', 'Empf&auml;nger');
mh_define('MAILBEEZ_ACTION_LIST_RECIPIENTS_TEXT', 'Die aktuellen Empf&auml;nger auflisten:');
mh_define('MAILBEEZ_BUTTON_LIST_RECIPIENTS', 'Zeigen');

// action plugin send testmail
mh_define('MAILBEEZ_ACTION_SEND_TESTMAIL_HEADLINE', 'Sende Test Email');
mh_define('MAILBEEZ_ACTION_SEND_TESTMAIL_TEXT', 'Sende eine Test Email mit Test Daten:');
mh_define('MAILBEEZ_BUTTON_SEND_TESTMAIL', 'Senden...');

// action plugin run module
mh_define('MAILBEEZ_ACTION_RUN_MODULE_HEADLINE', 'Modul Ausf&uuml;hren');
mh_define('MAILBEEZ_ACTION_RUN_MODULE_TEXT', 'Dieses Modul ausf&uuml;hren im Modus: ' . MAILBEEZ_MAILHIVE_MODE);
mh_define('MAILBEEZ_BUTTON_RUN_MODULE', 'Ausf&uuml;hren...');

// action plugin edit dashboard
mh_define('MAILBEEZ_ACTION_EDIT_DASHBOARD_HEADLINE', 'Dashboard Module');
mh_define('MAILBEEZ_ACTION_EDIT_DASHBOARD_TEXT', 'Dashboard Module hinzuf&uuml;gen, entfernen und bearbeiten');
mh_define('MAILBEEZ_BUTTON_EDIT_DASHBOARD', 'Bearbeiten...');


// action plugin control simulation
mh_define('MAILBEEZ_ACTION_SIMULATION_RESTART_HEADLINE', 'Simulation');
mh_define('MAILBEEZ_ACTION_SIMULATION_RESTART_TEXT', 'Simulation neu starten - alle Simulationsdaten werden gel&ouml;scht.');
mh_define('MAILBEEZ_ACTION_SIMULATION_RESTART_OK', 'Simulation neu gestartet.');
mh_define('MAILBEEZ_BUTTON_SIMULATION_RESTART', 'Neu-Start');

// action plugin template engine
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_HEADLINE', 'Template System');
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_TEXT', 'Alle kompilierten Template Dateien l&ouml;schen.');
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_OK', 'Dateien gel&ouml;scht');
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_INFO', 'Anzahl kompilierter Template Dateien');
mh_define('MAILBEEZ_BUTTON_TEMPLATEENGINE_CLEAR', 'Clear');


mh_define('MAILBEEZ_VERSION_CHECK_MSG_INTRO', 'MailBeez sagt:');

mh_define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_TITLE', 'Emails beim Senden zeigen');
mh_define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_DESC', 'W&auml;hle True, um die generierten Emails beim Senden zu sehen.');

mh_define('MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT', (MAILBEEZ_MAILHIVE_MODE == 'simulate') ? 'Schalte auf "production"' : 'Schalte auf "simulate"');


// new in MailBeez V2.5 - kill process
// config_process_control
mh_define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_HEADLINE', 'Prozess abbrechen');
mh_define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_TEXT', 'Einmal gestartet, kann der Versandprozess einige Stunden ablaufen - z.B. bei gedrosseltem Versand.
<br />Mit klick auf "STOP" wird der Versandprozess baldm&ouml;glichst nach dem Versand der n&auml;chsten Email abgebrochen.');
mh_define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_OK', 'Prozess Abbruch eingeleitet');
mh_define('MAILBEEZ_BUTTON_PROCESS_CONTROL_KILL', 'STOP');

// new in MailBeez V2.5 - configure email engine
// config_email_engine
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_TITLE', 'Email System');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_DESCRIPTION', 'Konfiguration des Email Systems - nur &auml;ndern, wenn es Probleme gibt');


mh_define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_TITLE', 'Zencart Email Template System umgehen?');
mh_define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_DESC', 'M&ouml;chtest du das Zencart Email Template System umgehen?<br />Bei "False" wird der von MailBeez generierte Inhalt in das Template "emails/email_template_default.html" oder  "emails/email_template_mailbeez.html" - falls vorhanden - eingef&uuml;gt. ');

mh_define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_TITLE', 'Double Dot Bugfix');
mh_define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_DESC', 'In seltenen F&auml;llen wird ein zweiter punkt eingef&uuml;gt, z.B.. file.php wird zu file..php, image.png becomes image..png. Diesen Bug versuchen zu fixen?');


mh_define('MAILBEEZ_MODE_SET_SIMULATE_TEXT', 'Simulation');
mh_define('MAILBEEZ_MODE_SET_PRODUCTION_TEXT', 'Produktion');


// new in MailBeez V2.6
// support for WP Online Store
mh_define('MAILBEEZ_CONFIG_WPOLS_TEXT_TITLE', 'WP Online Store Integration');
mh_define('MAILBEEZ_CONFIG_WPOLS_TEXT_DESCRIPTION', 'Einstellungen f&uuml;r MailBeez auf WP Online Store');

mh_define('MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID_TITLE', 'Wordpress Page ID der WP Online Store Seite');
mh_define('MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID_DESC', 'Bitte die ID der Seite eingeben, auf der sich der Shortcode [WP_online_store] befindet.<br />Beim Bearbeiten der Seite enth&auml;lt die URL die ID, z.B. "post.php?post=<b>794</b>&action=edit" - in diesem Beispiel bitte 794 notieren und hier eintragen.');


// Email Engine
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TITLE', 'Email System');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_DESC', 'Bitte das Email System ausw&auml;hlen');

mh_define('MAILBEEZ_REPLY_TO_ADDRESS_TITLE', 'PHPMailer: Reply-to Adresse');
mh_define('MAILBEEZ_REPLY_TO_ADDRESS_DESC', 'Reply-to Adresse, wichtige Einstellung fuer R&uuml;ckl&auml;ufer Verarbeitung: R&uuml;ckl&auml;ufer gehen an die Sender Adresse, Kunden-Antworten gehen an die Reply-to Adresse - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_REPLY_TO_ADDRESS_NAME_TITLE', 'PHPMailer: Reply-to name');
mh_define('MAILBEEZ_REPLY_TO_ADDRESS_NAME_DESC', 'Reply-to name, wichtige Einstellung fuer R&uuml;ckl&auml;ufer Verarbeitung - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_FROM_OVERRIDE_TITLE', 'PHPMailer: &Uuml;berschreibe Sender Name und Adresse');
mh_define('MAILBEEZ_FROM_OVERRIDE_DESC', 'W&auml;hle, ob der Sender Name und Adresse aus den Modulen &uuml;berschrieben werden soll. Bounce-Emails werde an diese Adresse kommen.<br/>Bei False m&uuml;ssen die Absender-Adressen in allen Modulen auf die Bounce-Email Adresse ge&auml;ndert werden.');


mh_define('MAILBEEZ_FROM_ADDRESS_TITLE', 'PHPMailer: From Adresse');
mh_define('MAILBEEZ_FROM_ADDRESS_DESC', 'From Adresse - wichtige Einstellung fuer R&uuml;ckl&auml;ufer Verarbeitung: Anwort-Emails werden an diese Email Adresse gehen - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_FROM_ADDRESS_NAME_TITLE', 'PHPMailer: From Name');
mh_define('MAILBEEZ_FROM_ADDRESS_NAME_DESC', 'FromNname, wichtige Einstellung fuer R&uuml;ckl&auml;ufer Verarbeitung - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SENDER_ADDRESS_TITLE', 'PHPMailer: Sender Adresse (Return-Path)');
mh_define('MAILBEEZ_SENDER_ADDRESS_DESC', 'Sender Adresse (Return-Path), wichtige Einstellung fuer R&uuml;ckl&auml;ufer Verarbeitung:
Bounce-Emails werden an diese Email Adresse geschickt,  Kunden-Antworten gehen an From Adresse. Dies muss die Email-Adresse der BounceHive Mailbox sein.');

mh_define('MAILBEEZ_SENDER_ADDRESS_NAME_TITLE', 'PHPMailer: Sender Name');
mh_define('MAILBEEZ_SENDER_ADDRESS_NAME_DESC', 'Sender Name, wichtige Einstellung fuer R&uuml;ckl&auml;ufer Verarbeitung.');

mh_define('MAILBEEZ_EMAIL_TRANSPORT_TITLE', 'PHPMailer: Transport Methode');
mh_define('MAILBEEZ_EMAIL_TRANSPORT_DESC', 'Transport Methode einstellen - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SENDMAIL_PATH_TITLE', 'PHPMailer: Sendmail Path');
mh_define('MAILBEEZ_SENDMAIL_PATH_DESC', 'Sendmail Path - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_AUTH_TITLE', 'PHPMailer: Smtp Authentication');
mh_define('MAILBEEZ_SMTP_AUTH_DESC', 'Smtp Authentication - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_USERNAME_TITLE', 'PHPMailer: Smtp Username');
mh_define('MAILBEEZ_SMTP_USERNAME_DESC', 'Smtp Username - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_PASSWORD_TITLE', 'PHPMailer: Smtp Password');
mh_define('MAILBEEZ_SMTP_PASSWORD_DESC', 'Smtp Password - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_MAIN_SERVER_TITLE', 'PHPMailer: Smtp Server');
mh_define('MAILBEEZ_SMTP_MAIN_SERVER_DESC', 'Smtp Server - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_BACKUP_SERVER_TITLE', 'PHPMailer: Smtp Backup Server');
mh_define('MAILBEEZ_SMTP_BACKUP_SERVER_DESC', 'Smtp Backup Server - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_SECURE_TITLE', 'PHPMailer: Smtp Security');
mh_define('MAILBEEZ_SMTP_SECURE_DESC', 'Smtp security einstellen - nur f&uuml;r PHPMailer');

mh_define('MAILBEEZ_SMTP_PORT_TITLE', 'PHPMailer: Smtp port');
mh_define('MAILBEEZ_SMTP_PORT_DESC', 'Smtp port einstellen, default ist 25 - nur f&uuml;r PHPMailer');


mh_define('MAILBEEZ_DKIM_SELECTOR_TITLE', 'PHPMailer 5.2.1: DKIM Selector');
mh_define('MAILBEEZ_DKIM_SELECTOR_DESC', 'F&uuml;r die Verwendung von DKIM - nur f&uuml;r PHPMailer 5.2.1');

mh_define('MAILBEEZ_DKIM_IDENTIY_TITLE', 'PHPMailer 5.2.1: DKIM Identity');
mh_define('MAILBEEZ_DKIM_IDENTIY_DESC', 'F&uuml;r die Verwendung von DKIM  - nur f&uuml;r PHPMailer 5.2.1
                                             <br />optional, in der Form einer Email Adresse "you@yourdomain.com"');
mh_define('MAILBEEZ_DKIM_PASSPHRASE_TITLE', 'PHPMailer 5.2.1: DKIM Passphrase');
mh_define('MAILBEEZ_DKIM_PASSPHRASE_DESC', 'F&uuml;r die Verwendung von DKIM - nur f&uuml;r PHPMailer 5.2.1');

mh_define('MAILBEEZ_DKIM_DOMAIN_TITLE', 'PHPMailer 5.2.1: DKIM Domain');
mh_define('MAILBEEZ_DKIM_DOMAIN_DESC', 'F&uuml;r die Verwendung von DKIM  - nur f&uuml;r PHPMailer 5.2.1
                                         <br />optional, in der Form einer Email Adresse "you@yourdomain.com"');

mh_define('MAILBEEZ_DKIM_PRIVATE_TITLE', 'PHPMailer 5.2.1: DKIM Private');
mh_define('MAILBEEZ_DKIM_PRIVATE_DESC', 'F&uuml;r die Verwendung von DKIM  - nur f&uuml;r PHPMailer 5.2.1
                                         <br />optional, in der Form einer Email Adresse "you@yourdomain.com"');

mh_define('MAILBEEZ_EMAIL_USE_TXT_ONLY_TITLE', 'PHPMailer: Nur TXT Emails senden');
mh_define('MAILBEEZ_EMAIL_USE_TXT_ONLY_DESC', 'Alle Emails nur im TXT Format senden.');


// Bounce handling
mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_PROMO_TEXT_TITLE', 'BounceHive R&uuml;ckl&auml;ufer Verarbeitung');
mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_PROMO_TEXT_DESCRIPTION', 'Ung&uuml;ltige Email-Adressen f&uuml;hren zu R&uuml;ckl&auml;ufern ("undeliverable Email..."). Neben diesen nervigen Emails erh&ouml;hen R&uuml;ckl&auml;ufer auch <b>das Risiko als Spam-Versender eingestuft zu werden</b>. Mit der BounceHive R&uuml;ckl&auml;ufer Verarbeitung k&ouml;nnen Sie diese Emails <b>automatisch verarbeiten</b> und ung&uuml;ltige Email-Adressen von zuf&uuml;nftigen Email-Aktionen ausschliessen.
 <br/>
 <div class="pro">Um die Verarbeitung von R&uuml;ckl&auml;ufer zu nutzen, bitte das Modul <a href="http://www.mailbeez.com/documentation/configbeez/config_bouncehive_advanced' . MH_LINKID_1 . '" target="_blank">BounceHive R&uuml;ckl&auml;ufer Verarbeitung</a> installieren.</div>');

mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_TEXT_TITLE', 'BounceHive R&uuml;ckl&auml;ufer Verarbeitung');
mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_TEXT_DESCRIPTION', 'Konfiguration der Verarbeitung von R&uuml;ckl&auml;ufern, z.B. bei nicht mehr existiernenden Email-Konto. Diese Email-Adressen werden protokolliert und erhalten keine weiteren Emails.' . ((preg_match('/PHPMailer/', MAILBEEZ_CONFIG_EMAIL_ENGINE)) ? '' : '<br /><b>ERFORDERT PHPMailer</b> - bitte das MailBeez Email System auf die Verwendung von PHPMailer konfigurieren.'));


mh_define('MAILBEEZ_BOUNCEHIVE_STATUS_TITLE', 'Aktiviere die R&uuml;ckl&auml;ufer-Verarbeitung');
mh_define('MAILBEEZ_BOUNCEHIVE_STATUS_DESC', 'Dieses Modul aktivieren und konfigurieren, um R&uuml;ckl&auml;ufer-Emails automatisch zu verarbeiten - erfordert, dass MailBeez PHPMailer verwendet.');

mh_define('MAILBEEZ_BOUNCEHIVE_MSG_LOG_TITLE', 'Protokollierung von Bounce Messages');
mh_define('MAILBEEZ_BOUNCEHIVE_MSG_LOG_DESC', 'Bounces Messages protokollieren - Dieses k&ouml;nnen mehr als 1kb per Bounce einnehmen. Die Messages werden in Tabelle "' . TABLE_MAILBEEZ_BOUNCE_MSG_LOG . '" protokolliert - Diese Tabelle kann jederzeit manuell geleert werden.');


mh_define('MAILBEEZ_BOUNCEHIVE_DO_RUN_TITLE', 'R&uuml;ckl&auml;ufer Verarbeitung bei jeder Ausf&uuml;hrung von MailHive');
mh_define('MAILBEEZ_BOUNCEHIVE_DO_RUN_DESC', 'Wenn die Verarbeitung h&auml;ufiger ablaufen soll, bitte "False" w&auml;hlen und einen eingenen Cronjob anlegen:<br/> ' . MAILBEEZ_MAILHIVE_URL_DIRECT . '?m=service_handler_bouncehive&ma=bounce_process');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_TITLE', 'BounceHive IMAP MailServer');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_DESC', 'der MailServer wo die R&uuml;ckl&auml;ufer Emails ankommen');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_USERNAME_TITLE', 'BounceHive MailServer Benutzername');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_USERNAME_DESC', 'Benutzername');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PASSWORD_TITLE', 'BounceHive MailServer Password');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PASSWORD_DESC', 'Password');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PORT_TITLE', 'BounceHive MailServer Port');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PORT_DESC', 'Standard ist 143');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SERVICE_OPTION_TITLE', 'BounceHive MailServer Service Optionen');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SERVICE_OPTION_DESC', 'Optionen (none, tls, notls, ssl, etc.), Standard ist "notls"');

mh_define('MAILBEEZ_BOUNCEHIVE_MAXMESSAGES_TITLE', 'BounceHive Max. Anzahl an Emails');
mh_define('MAILBEEZ_BOUNCEHIVE_MAXMESSAGES_DESC', 'Maximale Anzahl an Emails die per Durchlauf verarbeitet werden');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_HARDMAILBOX_TITLE', 'Ordner f&uuml;r harte R&uuml;ckl&auml;ufer');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_HARDMAILBOX_DESC', 'Mailserver-Ordner - wird ggf. angelegt');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SOFTMAILBOX_TITLE', 'Ordner f&uuml;r weiche R&uuml;ckl&auml;ufer');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SOFTMAILBOX_DESC', 'Mailserver-Ordner - wird ggf. angelegt');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PROCESSEDMAILBOX_TITLE', 'Ordner f&uuml;r verarbeitete Nicht-R&uuml;ckl&auml;ufer');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PROCESSEDMAILBOX_DESC', 'Mailserver-Ordner - wird ggf. angelegt');


mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_DAYS_TITLE', 'Softbounce: Wartezeit');
mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_DAYS_DESC', 'Nach einem Softbounce: Wieviele Tage soll MailBeez warten, bevor erneut eine Email an diese Adresse geschickt wird? Zwischenzeitlich anfallende Emails verfallen.');

mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_TITLE', 'Softbounce: Anzahl f&uuml;r Hardbounce Einordnung');
mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_DESC', 'Nach dieser Anzahl von Softbounces in dem konfigurierten Zeitraum wird ein erneuter Softbounce als Hardbounce eingeordnet');

mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_DAYS_TITLE', 'Softbounce: Zeitraum f&uuml;r Hardbounce Einordnung');
mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_DAYS_DESC', 'Der Zeitraum f&uuml;r die Einordnung eines erneuten Softbounces als Hardbounce');


// MailBeez Analytics

mh_define('MAILBEEZ_CONFIG_ANALYTICS_TEXT_TITLE', 'MailBeez Analytics');
mh_define('MAILBEEZ_CONFIG_ANALYTICS_TEXT_DESCRIPTION', "Konfiguriere MailBeez Analytics. Nutze MailBeez Analytics um z.B. das &Ouml;ffnen von Emails, Klicks usw. auszuwerten.
");

mh_define('MAILBEEZ_ANALYTICS_STATUS_TITLE', 'MailBeez Analytics aktivieren');
mh_define('MAILBEEZ_ANALYTICS_STATUS_DESC', "Am Ende der Datei 'includes/application_top.php' muss folgender Code vorhanden sein:
<pre>
// MailBeez Click and Order tracker
require(DIR_FS_CATALOG . 'mailhive/includes/clicktracker.php');
</pre>");

mh_define('MAILBEEZ_ANALYTICS_DO_RUN_TITLE', 'MailBeez Analytics Informationen mit jeder Ausf&uuml;hrung von MailHive verarbeiten');
mh_define('MAILBEEZ_ANALYTICS_DO_RUN_DESC', 'Bei False bitte das Modul "Service Steuerung f&uuml;r MailBeez Analytics" manuell ausf&uuml;hren oder einen eigenen Cronjob aufsetzen: ' . MAILBEEZ_MAILHIVE_URL_DIRECT . '?m=service_handler_analytics&ma=tracking_process');

mh_define('MAILBEEZ_ANALYTICS_AUTOINSERT_PIX_TITLE', 'Tracking Pix automatisch einf&uuml;gen');
mh_define('MAILBEEZ_ANALYTICS_AUTOINSERT_PIX_DESC', 'F&uuml;gt das Tracking Pixel automatisch am Ende des Modul-Bodys ein, bei Layout-Problemen bitte auf False setzen und folgendes in das Haupt-Template z.B. vor dem Schliessen des body-Tags einf&uuml;gen: <b>{$MAILBEEZ_TRACKER_PIX}</b>');

mh_define('MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO_TITLE', 'Echtzeit &Ouml;ffnungsraten');
mh_define('MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO_DESC', 'Die &Ouml;ffnungsraten werden alle 30sec aktualisiert. F&uuml;r High-Traffic Seiten ggf. deaktivieren und zusammen mit MailHive ausf&uuml;hren oder einen separaten Cronjob anlegen.');

mh_define('MAILBEEZ_ANALYTICS_STATUS_BUTTON', 'Direktes Empf&auml;nger Tracking aktiveren');


mh_define('MAILBEEZ_ANALYTICS_REWRITE_FORMAT_TITLE', 'Click-Tracking auch im TXT-Format?');
mh_define('MAILBEEZ_ANALYTICS_REWRITE_FORMAT_DESC', 'Auch im TXT-Format die URLs um Click-Tracking erweitern?');

mh_define('MAILBEEZ_MAILHIVE_MODE_SIM_RESTART_BUTTON', 'Neu starten');
mh_define('MAILBEEZ_SIMULATION_RESTARTED_MSG', 'Simulation neu gestartet');

mh_define('MAILBEEZ_CONFIG_SPAMCOMPLIANCE_TEXT_TITLE', 'Antispam Regeln');
mh_define('MAILBEEZ_CONFIG_SPAMCOMPLIANCE_TEXT_DESCRIPTION', 'Mit den restriktiven Einstellungen halten Sie die strengen Antispam Regeln ein. Abh&auml;ngig von lokalen Gesetzgebungen kann es zul&auml;ssig sein, von diesen Regeln abzuweichen. Sie sind selbst daf&uuml;r verantwortlich, sich &uuml;ber die gesetzlichen Bestimmungen zu informieren und sind voll f&uuml;r die Nutzung von MailBeez verantwortlich.
<br/>
<br/>
Mehr Kontrolle erhalten Sie mit den Modulen
<br/>
 "<a href="http://www.mailbeez.com/documentation/configbeez/config_spamcompliance_advanced/' . MH_LINKID_1 . '&lang=de" target="_blank">Antispam Regeln Profi</a>" und
<br/>
 "<a href="http://www.mailbeez.com/documentation/configbeez/config_block_admin/' . MH_LINKID_1 . '&lang=de" target="_blank">Erweitertes Opt-out mit Admin</a>"');

mh_define('MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION_TITLE', 'Newsletter Abo Check');
mh_define('MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION_DESC', 'Nur Kunden mit einem Newsletter Abo werden von MailBeez generierte Emails erhalten. Einige Module, z.B. Zahlungserinnerungen, werden diesen Check ignorieren. (Restriktiv: "True")<br />
<br>
<br>
Mehr Kontrolle bekommen Sie mit dem Modul "<a href="http://www.mailbeez.com/documentation/configbeez/config_spamcompliance_advanced/' . MH_LINKID_1 . '&lang=de" target="_blank">Antispam Regeln Profi</a>"');


mh_define('MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR_TITLE', 'Opt-Out Verhalten');
mh_define('MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR_DESC', 'W&auml;hle was ein Klick auf den Opt-Out link bewirkt+<br />
<b>Module</b>: Nur das Modul wird blockiert<br>
<b>Global</b>: Alle Module werden blockiert<br>
Einige Module, z.B. Zahlungserinnerungen, werden die Einstellung "Global" ignorieren. (Restriktiv: "Global"")<br />
Mehr Kontrolle gibt das Module "<a href="http://www.mailbeez.com/documentation/configbeez/config_block_admin/' . MH_LINKID_1 . '&lang=de" target="_blank">Erweitertes Opt-out mit Admin</a>"');


mh_define('MAILBEEZ_REPORT_DETAILS', '<img src="' . MH_CATALOG_SERVER . MH_ADMIN_DIR_WS_IMAGES .'details.png" width="12"
                                                     height="12" align="absmiddle" hspace="3" border="0">Details');

mh_define('MAILBEEZ_REPORT_DATE', 'Datum');
mh_define('MAILBEEZ_REPORT_LINK_URL', 'Link-URL');
mh_define('MAILBEEZ_REPORT_USER_AGENT', 'Email-Programm');
mh_define('MAILBEEZ_REPORT_ORDER', 'Bestellung Nr.');

mh_define('MAILBEEZ_REPORT_NO_OPEN_LOG', 'Keine Daten gefunden.<br>Die Daten sind eventuell gel&ouml;scht oder es handelt sich um ein indirektes &Ouml;ffnen ausgel&ouml;st durch einen Klick.');
mh_define('MAILBEEZ_REPORT_NO_CLICK_TRACKING', 'Keine Klick-Daten gefunden.');
mh_define('MAILBEEZ_REPORT_NO_ORDER', 'Keine Bestell-Daten gefunden.');

mh_define('MAILBEEZ_TEXT_DISPLAY_NUMBER_OF_ITEMS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Eintr&auml;gen)');

mh_define('MAILBEEZ_ANALYTICS_SPLITPAGE_NUM_TITLE', 'Anzahl Daten-Reihen per Seite');
mh_define('MAILBEEZ_ANALYTICS_SPLITPAGE_NUM_DESC', 'Anzahl Daten-Reihen per Seite');

mh_define('MAILBEEZ_CERTIFICATE_BUTTON', 'Bearbeiten');
mh_define('TABLE_HEADING_CERTIFICATE_INFO', 'Zertifikat');
mh_define('TABLE_HEADING_CERTIFICATE_HELP_TOP', 'Bitte das Zertifikat f&uuml;r dieses Modul hinterlegen. Falls mit der Liefer-Email von Avangate kein Zertifikat geliefert wurde, bitte  <a href="http://www.mailbeez.com/about/contact/" target="_blank">MailBeez kontaktieren</a>.');

mh_define('MAILBEEZ_CERTIFICATE_STATUS_VALID', 'g&uuml;ltig');
mh_define('MAILBEEZ_CERTIFICATE_STATUS_INVALID', 'ung&uuml;ltig');
mh_define('MAILBEEZ_CERTIFICATE_STATUS_ENTER', 'bitte eingeben');


// MailBeez V2.7
mh_define('MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT_TITLE', 'Email Betreff Konvertierung');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT_DESC', 'Email Betreff konvertieren, um Darstellungsprobleme mit Sonderzeichen zu vermeiden');


mh_define('MAILBEEZ_INSTALL_WARNING_TEMPLATE_C', "<h1>Bitte die Server-Konfiguration pr&uuml;fen:</h1>Der Ordner <blockquote><b>" . MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR . "</b></blockquote> muss Schreibrechte besitzen. <br><br><font color='red'>Bitte stellen Sie sicher, dass dieser Ordner Schreibrechte besitzt - Diese k&ouml;nnen mit Hilfe eines FTP-Programmes vergeben werden.<br>
Falls der Ordner Schreibrechte besitzt, bitte den Ordner leeren.</font> <br><br>Dann diese Seite neu laden.");

mh_define('MAILBEEZ_VERSION_CHECK_MSG_UPDATE_MODULES', 'Diese Module sind inkompatible mit MailBeez V. ' . MAILBEEZ_VERSION_DISPLAY .  '. Bitte installieren Sie sofort die aktuelle Version:');

mh_define('MAILBEEZ_VERSION_CHECK_MSG_UPDATE_MODULES_TEXT', 'Bitte laden Sie die aktuelle Version der Module &uuml;ber den Download-Link, welchen Sie mit der Liefer-Email von Avangate erhalten haben. <br>Dann folgen Sie bitte der Update/Installations-Anleitung des Modules.');


// MailBeez V2.7.4
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_EMOGRIFY_TITLE', 'CSS in Inline Styles konvertieren');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_EMOGRIFY_DESC', 'F&uuml;r beste Darstellung in den verschiedenen Email-Programmen auf True setzen. Erfordert PHP5.');

mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE_TITLE', 'PHPMailer: PHPMailer Debug Mode');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE_DESC', 'Debug Mode f&uuml;r PHPMailer aktivieren');

mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP_TITLE', 'PHPMailer: TXT Zeilenumbruch');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP_DESC', 'Anzahl Zeichen &fuuml;r Zeilenumbruch. 0 deaktiviert den Zeilenumbruch');

mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_TITLE', 'Email Encoding');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_DESC', 'Encoding f&uuml;r Emails. Bitte leer lassen f&uuml;r automatische Erkennung. Werte sind z.B. "UTF-8", "ISO-8859-1"');

mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_MEMORY_LIMIT_TITLE', 'PHP Speicher Limit');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_MEMORY_LIMIT_DESC', 'PHP Speicher Grenze einstellen, Standard 512M');

mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INGORE_USER_ABORT_TITLE', 'Setze Ignore User Abort');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INGORE_USER_ABORT_DESC', 'Setzt den Wert daf&uuml;r, ob der Abbruch einer Client-Verbindung die weitere Abarbeitung eines Skripts beenden soll. Standard: True');

mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TIME_LIMIT_TITLE', 'PHP Ausf&uuml;hrungszeit');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TIME_LIMIT_DESC', 'Legt die maximale Ausf&uuml;hrungszeit fest. Standard: 30sec');

mh_define('MH_SYSTEMCHECK_DB_DISABLED_TITLE', 'System DB Check deaktivieren');
mh_define('MH_SYSTEMCHECK_DB_DISABLED_DESC', 'Deaktivieren des Systemchecks auf besch&auml;digte Datenbank. Standard: False');

mh_define('MAILBEEZ_ANALYTICS_BEGIN_OF_TIME_TITLE', 'Anfangs-Datum f&uuml;r Analytics');
mh_define('MAILBEEZ_ANALYTICS_BEGIN_OF_TIME_DESC', 'Anfangs-Datum f&uuml;r MailBeez Analytics. Sollte das Datum sein, an dem das erweiterte Tracking aktiviert und installiert worden ist. Standard: 2000-01-01 00:00:00');

mh_define('MAILBEEZ_SYSTEM_CHECK_MSG', 'System-Pr&uuml;fung: Probleme gefunden!');
mh_define('MAILBEEZ_BUTTON_SYSTEMCHECK_REFRESH', 'klick: erneut pr&uuml;fen');

mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_CORRUPTED_DB', '<b>Die Bestell-Datenbank ist teilweise besch&auml;digt!</b><br>
Es wurden <b>%s besch&auml;digte Bestellung(en)</b> gefunden. MailBeez kann f&uuml;r diese Bestellungen keine bestell-bezogenen Kampagnen generieren!<br>
Weitere Informationen zur Ursache und Problembehebung auf: <a href="http://www.mailbeez.de/dokumentation/mailbeez/service_db_repair_order/' . MH_LINKID_1 . '" target="_blank">Service-Modul: Repariere Bestell-Datenbank</a>.');


mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PHP_MBSTRING', 'Die PHP Erweiterung mbstring ist nicht vorhanden - mehr Informationen auf http://www.php.net/manual/de/mbstring.installation.php');


mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PHP_SUHOSIN_EVAL', 'Suhosin ist installiert und die Funktion <b>eval()</b> deaktiviert - Bitte die Verwendung von eval() zulassen. Kontaktieren Sie hierzu Ihren Server-Admin.');
mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PHP_SUHOSIN_EVAL_GZINFLATE', 'Suhosin ist installiert und die Funktion <b>gzinflate()</b> deaktiviert - Bitte die Verwendung von gzinflate() zulassen. Kontaktieren Sie hierzu Ihren Server-Admin.');

mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PATH', '

<b>Stylesheets und Skripte werden nicht geladen.</b>
 <br>
Der Konfigurations-Wert von HTTP_CATALOG_SERVER ist fehlerhaft, bitte in "&lt;admin&gt;/includes/configure.php" pr&uuml;fen.
 <br>
 Die Konfiguration muss so gesetzt sein, dass HTTP_CATALOG_SERVER + DIR_WS_CATALOG die Shop-URL ergeben.');


mh_define('MAILBEEZ_DASHBOARD_ANALYTICS_MSG', 'Bitte das Empf&auml;nger Tracking installieren und aktivieren,<br>um MailBeez Analytics Daten sehen zu k&ouml;nnen');

mh_define('MAILBEEZ_STATSBAR_LABEL_EMAILS', 'Emails');
mh_define('MAILBEEZ_STATSBAR_LABEL_SENT', 'gesendet');
mh_define('MAILBEEZ_STATSBAR_LABEL_DELIVERED', 'zugestellt');
mh_define('MAILBEEZ_STATSBAR_LABEL_BEGIN_OF_TIME', 'seit');
mh_define('MAILBEEZ_STATSBAR_LABEL_OPENED', 'ge&ouml;ffnet');
mh_define('MAILBEEZ_STATSBAR_LABEL_CLICKED', 'geklickt');
mh_define('MAILBEEZ_STATSBAR_LABEL_ORDERED', 'bestellt');
mh_define('MAILBEEZ_STATSBAR_LABEL_REVENUE', 'Umsatz (Sub-Total)');
mh_define('MAILBEEZ_STATSBAR_LABEL_COUPON_VAL', 'Summe Gutscheine');
mh_define('MAILBEEZ_STATSBAR_LABEL_MOBILE', 'Mobil');

mh_define('MAILBEEZ_STATSBAR_RESPONSIVE_INFO', '<b>So geht\'s:</b> Jetzt auf responsive Emails umstellen... <a href="http://www.mailbeez.de/dokumentation/responsive-emails/' . MH_LINKID_1 . '" target="_blank">mehr lesen</a>');

?>