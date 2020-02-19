<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

mh_define('MH_INSTALL_INTRO', 'Please install MailHive by clicking the install-button');
mh_define('MH_INSTALL_SUPPORT', 'If you are having issues with the installation, please use the <a href="http://www.mailbeez.com/documentation/installation/" target="_blank"><b><u>installation tutorials</u></b></a><br />
									<br />
									After you have done this at least 3x you are allowed to use the <a href="http://www.mailbeez.com/support/" target="_blank"><b><u>MailBeez-Support</u></b></a> ;-)');

mh_define('MH_RATE_TRUSTPILOT_LINK', 'Please rate MailBeez on Trustpilot');

mh_define('MH_SECURE_URL', 'Secure Cronjob-URL (will immediately execute all active mailBeez - but respects Mode)');

mh_define('MH_BUTTON_VERSION_CHECK', 'Check for Updates...');
mh_define('MH_BUTTON_BACK_CONFIGURATION', 'Back to Configuration');
mh_define('MH_BUTTON_BACK_DASHBOARD', 'Back to Dashboard');
mh_define('MH_BUTTON_BACK_REPORTS', 'Back to Reports');

mh_define('MH_DASHBOARD_CONFIG', 'config');
mh_define('MH_DASHBOARD_REMOVE', 'x');

mh_define('MH_HEADING_TITLE', 'MailBeez - Easy Automated Email Marketing');
mh_define('TEXT_DOCUMENTATION', 'Documentation Available');
mh_define('TEXT_VIEW_ONLINE', 'View Online');
mh_define('TEXT_UPGRADE_MAILBEEZ', 'This Module requires MailBeez Version %s or higher. Please update MailBeez.');
mh_define('WARNING_SIMULATE', 'SIMULATION-MODE: no emails are sent');
mh_define('WARNING_OFFLINE', 'DISABLED: MailBeez are not processed');

mh_define('MH_NO_MODULE', 'No Modules.');

mh_define('MH_TAB_HOME', 'Home');
mh_define('MH_TAB_MAILBEEZ', 'MailBeez Modules');
mh_define('MH_TAB_CONFIGURATION', 'Configuration');
mh_define('MH_TAB_FILTER', 'Filter &amp; Helper');
mh_define('MH_TAB_REPORT', 'Reports');
mh_define('MH_TAB_ABOUT', 'About');
mh_define('MH_HEADER_DASHBOARD_MODULES', 'Dashboard Modules');
mh_define('MH_MSG_EMPTY_DASHBOARD_AREA', 'Empty Space - Add a Dashboard-Module');

mh_define('MH_HOME_ACTIONS', 'Actions');
mh_define('MH_HOME_RESOURCES', 'Resources');

mh_define('MH_DOWNLOAD_LINK_LIST', 'Find more MailBeez Modules...');
mh_define('MH_DASHBOARD_CONFIG_BUTTON', 'Config Dashboard');

// config
mh_define('MAILBEEZ_MAILHIVE_TEXT_TITLE', 'MailHive - Basic Configuration');
if (MAILBEEZ_CONFIG_INSTALLED == 'config_queen.php' && MAILBEEZ_INSTALLED == '') {
    mh_define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Basic Configuration for MailHive.');
} else {
    mh_define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Basic Configuration for MailHive. <br />
		<br />To uninstall MailBeez please uninstall all MailBeez modules first.');
}

mh_define('MAILBEEZ_MAILHIVE_STATUS_TITLE', 'Let MailBeez Do the Work For You');
mh_define('MAILBEEZ_MAILHIVE_STATUS_DESC', 'Activate MailHive and MailBeez');

mh_define('MAILBEEZ_MAILHIVE_COPY_TITLE', 'Send Copy');
mh_define('MAILBEEZ_MAILHIVE_COPY_DESC', 'Send a copy of each email to copy-address (configured below)');

mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_TITLE', 'Send Copy To');
mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_DESC', 'Send a copy of each email to this address<br>(be mindful of the Max Number configuration below)');

mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_TITLE', 'Max. Number of Copy-Emails Sent per MailBeez Module');
mh_define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_DESC', 'Control the number of copy-emails sent to the address configured above');

mh_define('MAILBEEZ_MAILHIVE_TOKEN_TITLE', 'Security Token - For Internal Use Only');
mh_define('MAILBEEZ_MAILHIVE_TOKEN_DESC', 'Security Token to protect public mailhive, leave the default value or set to what you like');

mh_define('MAILBEEZ_MAILHIVE_POPUP_MODE_TITLE', 'Popup Mode');
mh_define('MAILBEEZ_MAILHIVE_POPUP_MODE_DESC', 'Leave this setting as it is unless you are having compatibility issues with opening the nice CeeBox AJAX Popups.');

mh_define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TITLE', 'Remind to Run Update Check');
mh_define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_DESC', 'Do you want to be reminded to check for updates?');

mh_define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_TITLE', 'Enable "Early Check"');
mh_define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_DESC', 'Do you want to enable "Early Check"?<br />This will hide all already sent or filtered results - but might confuse by showing "0 recipients".<br /><br />"Early Check" adds an SQL query per item per module (slower)');


// config_dashboard
mh_define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_TITLE', 'Dashboard Configuration');
mh_define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_DESCRIPTION', 'Configure the appearance of your MailBeez Startscreen');
;
mh_define('MAILBEEZ_CONFIG_DASHBOARD_START_TITLE', 'Start-Tab');
mh_define('MAILBEEZ_CONFIG_DASHBOARD_START_DESC', 'Choose which tab you would like your dashboard to open to (default: Home)');


// config_googleanalytics
mh_define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_TITLE', 'Google Analytics Automatic Campaign Integration');
mh_define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_DESCRIPTION', 'Configuration for Google Analytics URL Rewrite.<br /><br />
	<img src="' . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . "/mailhive/common/images/ga.png" . '" width="181" height="33" alt="" border="0" align="absmiddle" hspace="1">');

mh_define('MAILBEEZ_MAILHIVE_GA_ENABLED_TITLE', 'Google Analytics Integration');
mh_define('MAILBEEZ_MAILHIVE_GA_ENABLED_DESCRIPTION', 'Globally enable Google Analytics Integration');

mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_TITLE', 'Google Analytics URL Rewrite Mode');
mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_DESC', 'Globally set Google Analytics URL Rewrite Mode');

mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_FORMAT_TITLE', 'Google Analytics URL Rewrite in TXT Format');
mh_define('MAILBEEZ_MAILHIVE_GA_REWRITE_FORMAT_DESC', 'Rewrite URLs in txt format');

mh_define('MAILBEEZ_MAILHIVE_GA_MEDIUM_TITLE', 'Google Analytics "Medium"');
mh_define('MAILBEEZ_MAILHIVE_GA_MEDIUM_DESC', 'Choose what you would like to name the medium (default: email)');

mh_define('MAILBEEZ_MAILHIVE_GA_SOURCE_TITLE', 'Google Analytics "Source"');
mh_define('MAILBEEZ_MAILHIVE_GA_SOURCE_DESC', 'Choose the "Source" for Google Analytics (standard: MailBeez)');

// config_simulation
mh_define('MAILBEEZ_CONFIG_SIMULATION_TEXT_TITLE', 'Simulation');
mh_define('MAILBEEZ_CONFIG_SIMULATION_TEXT_DESCRIPTION', 'Configuration for MailBeez Advanced Simulations.<br />
	<br />Advanced simulation allows you to run complete, realistic simulations including tracking information.
	Emails are NOT sent out to customers - only to the configured email address');

mh_define('MAILBEEZ_MAILHIVE_MODE_TITLE', 'Mode');
mh_define('MAILBEEZ_MAILHIVE_MODE_DESC', 'Please test in simulation mode until you are happy &amp; ready to take MailBeez live with real customers.<br /><strong>Production mode is live and emails are sent to customers!</strong>');

mh_define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_TITLE', 'Send Simulation To');
mh_define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_DESC', 'Email Adress to send simulation emails to');

mh_define('MAILBEEZ_CONFIG_SIMULATION_COPY_TITLE', 'Send Copy in Simulation Mode');
mh_define('MAILBEEZ_CONFIG_SIMULATION_COPY_DESC', 'Send a copy of each email to the configured copy-address: ' . MAILBEEZ_MAILHIVE_EMAIL_COPY);

mh_define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_TITLE', 'Enable Tracking in Simulation Mode');
mh_define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_DESC', 'Do you want to enable Tracking in Simulation mode? You can delete the data collected during simulation tracking by clicking on "Restart Simulation"');


// config_template_engine
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_TITLE', 'Template Engine');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_DESCRIPTION', 'Configuration for Smarty Template Engine.<br />
	<br />	<a href="http://www.smarty.net" target="_blank"><img src="' . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . "/mailhive/common/images/smarty_icon.gif" . '" width="88" height="31" alt="" border="0" align="absmiddle" hspace="1"></a>');

mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_TITLE', 'Compatibility Mode');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_DESC', 'Choose True for compatibility with the MailBeez 1.x Template System.');

mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_TITLE', 'Path to Smarty');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_DESC', 'Path to Smarty Template system /Smarty.class.php<br>located in <br />mailhive/common/classes/');

// config_event_log
mh_define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_TITLE', 'Event Log');
mh_define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_DESCRIPTION', 'Settings for logging events while running MailBeez.');

// about
mh_define('MH_ABOUT', '<b style="font-size: 20px; font-weight: bold;">About MailBeez ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '') . '</b><br /><br />
	MailBeez Version ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '') . ',	detected platform: <b>' . MH_PLATFORM . '</b><br />
	
	Developed by: Cord F. Rosted <a href="mailto:' . MAILBEEZ_CONTACT_EMAIL . '">' . MAILBEEZ_CONTACT_EMAIL . '</a> <br />
	(contact in English, Deutsch, Dansk)');

mh_define('MH_ABOUT_BUTTONS_FEATURE', 'Request a Feature');
mh_define('MH_ABOUT_BUTTONS_RATE_READ', 'See User Ratings');
mh_define('MH_ABOUT_BUTTONS_RATE_RATE', 'Rate MailBeez');

$trustpilot_evaluate = 'http://www.trustpilot.com/evaluate/www.mailbeez.com';


mh_define('MH_MAILBEEZ_LOVE', 'Do you like MailBeez?');
mh_define('MH_MAILBEEZ_LOVE_TEXT', 'Has MailBeez helped you to get in touch with old customers
	or increase your revenue through product ratings?
	<br /><br />
	Feel free to say thank you to MailBeez with a donation - and look forward further developments.');

mh_define('MH_MAILBEEZ_LOVE_BTN', 'btn_donate_EN.gif');


// new with MailBeez V2.1

mh_define('MH_VERSIONCHECK_INFO_DASHBOARD', 'There are updates and/or new dashboard modules. Please check the dashboard configuration');
mh_define('MH_VERSIONCHECK_INFO_NEW', 'These %s Modules are not yet installed:');
mh_define('MH_VERSIONCHECK_INFO_NEW_MORE', 'click for more information');
mh_define('MH_VERSIONCHECK_INFO_NEWVERSION', 'New Version');


// new with MailBeez V2.2
// config_process_control
mh_define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_TITLE', 'MailHive Process Control');
mh_define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_DESCRIPTION', 'Process Control settings - for nerds only.');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TITLE', 'Activate MailHive Process Control');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_DESC', 'Choose True to activate MailHive Process Control (recommended).');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_TITLE', 'Lock Period');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_DESC', 'Lock Period in seconds.');


// action plugin view templates
mh_define('MAILBEEZ_ACTION_VIEW_TEMPLATE_HEADLINE', 'Email Templates');
mh_define('MAILBEEZ_ACTION_VIEW_TEMPLATE_TEXT', 'Preview the templates of this module:');
mh_define('MAILBEEZ_BUTTON_VIEW_HTML', 'HTML');
mh_define('MAILBEEZ_BUTTON_VIEW_TXT', 'TXT');
mh_define('MAILBEEZ_BUTTON_VIEW_HTML_RESPONSIVE', 'Mobile');


// action plugin list recipients
mh_define('MAILBEEZ_ACTION_LIST_RECIPIENTS_HEADLINE', 'Recipients');
mh_define('MAILBEEZ_ACTION_LIST_RECIPIENTS_TEXT', 'View a list of current recipients:');
mh_define('MAILBEEZ_BUTTON_LIST_RECIPIENTS', 'Show');

// action plugin send testmail
mh_define('MAILBEEZ_ACTION_SEND_TESTMAIL_HEADLINE', 'Send Test Email');
mh_define('MAILBEEZ_ACTION_SEND_TESTMAIL_TEXT', 'Send a test email with test data:');
mh_define('MAILBEEZ_BUTTON_SEND_TESTMAIL', 'Send...');

// action plugin run module
mh_define('MAILBEEZ_ACTION_RUN_MODULE_HEADLINE', 'Run This Module');
mh_define('MAILBEEZ_ACTION_RUN_MODULE_TEXT', 'Run this module in mode: ' . MAILBEEZ_MAILHIVE_MODE);
mh_define('MAILBEEZ_BUTTON_RUN_MODULE', 'Run...');


// action plugin edit dashboard
mh_define('MAILBEEZ_ACTION_EDIT_DASHBOARD_HEADLINE', 'Dashboard Module');
mh_define('MAILBEEZ_ACTION_EDIT_DASHBOARD_TEXT', 'Add, Remove and Edit Dashboard Modules');
mh_define('MAILBEEZ_BUTTON_EDIT_DASHBOARD', 'Edit...');


// action plugin control simulation
mh_define('MAILBEEZ_ACTION_SIMULATION_RESTART_HEADLINE', 'Simulation');
mh_define('MAILBEEZ_ACTION_SIMULATION_RESTART_TEXT', 'Restart the simulation - this deletes all recorded simulation data.');
mh_define('MAILBEEZ_ACTION_SIMULATION_RESTART_OK', 'Simulation restarted.');
mh_define('MAILBEEZ_BUTTON_SIMULATION_RESTART', 'Restart');

// action plugin template engine
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_HEADLINE', 'Template System');
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_TEXT', 'Clear compiled template files');
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_OK', 'Template compilation files cleared');
mh_define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_INFO', 'Number of template compilation files');
mh_define('MAILBEEZ_BUTTON_TEMPLATEENGINE_CLEAR', 'Clear');


mh_define('MAILBEEZ_VERSION_CHECK_MSG_INTRO', 'MailBeez says:');

mh_define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_TITLE', 'Show Email While Sending');
mh_define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_DESC', 'Choose True to see the generated email while sending them.');

mh_define('MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT', (MAILBEEZ_MAILHIVE_MODE == 'simulate') ? 'switch to "production"' : 'switch to "simulation"');


// new in MailBeez V2.5 - kill process
// config_process_control
mh_define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_HEADLINE', 'Kill Process');
mh_define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_TEXT', 'Once triggered the MailHive Process can run several hours (e.g. with throttling active). <br />Click the "Kill" Button to stop the process as soon as possible after sending the next email.');
mh_define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_OK', 'Process Kill Initiated');
mh_define('MAILBEEZ_BUTTON_PROCESS_CONTROL_KILL', 'Kill');


// new in MailBeez V2.5 - configure email engine
// config_email_engine
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_TITLE', 'Email Engine');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_DESCRIPTION', 'Configure the email engine - if everything works nothing needs to be changed here.');


mh_define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_TITLE', 'Override Zencart Email Template System');
mh_define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_DESC', 'Do you want to use MailBeez email templates instead of Zen Cart email templates? (Recommended)<br /><br />If set to "False" the generated content is merged into the template "emails/email_template_default.html" or  "emails/email_template_mailbeez.html" if available ');

mh_define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_TITLE', 'Double Dot Bugfix');
mh_define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_DESC', 'On rare occasions a dot in filenames is doubled, i.e. file.php becomes file..php, image.png becomes image..png. Try to fix this bug?');


mh_define('MAILBEEZ_MODE_SET_SIMULATE_TEXT', 'Simulation');
mh_define('MAILBEEZ_MODE_SET_PRODUCTION_TEXT', 'Production');


// new in MailBeez V2.6
// support for WP Online Store
mh_define('MAILBEEZ_CONFIG_WPOLS_TEXT_TITLE', 'WP Online Store Integration');
mh_define('MAILBEEZ_CONFIG_WPOLS_TEXT_DESC', 'Settings for MailBeez on WP Online Store');

mh_define('MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID_TITLE', 'Wordpress Page ID of WP Online Store');
mh_define('MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID_DESC', 'Please enter the page ID of the page where you inserted [WP_online_store]<br />You find this ID when you edit the page/post you created for showing WP Online Store within Wordpress. The URL shows e.g. "post.php?post=<b>794</b>&action=edit", note this ID (in this example 794) and enter it here.');


// Email Engine
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TITLE', 'Select the Email Engine');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_DESC', 'Select which email engine to use.');

mh_define('MAILBEEZ_REPLY_TO_ADDRESS_TITLE', 'PHPMailer: Reply-to Address');
mh_define('MAILBEEZ_REPLY_TO_ADDRESS_DESC', 'Reply-to address, important for bounce handling: Bounce-Emails will go to the sender email, while replies of customers will go to the Reply-To Address - only for PHPMailer');

mh_define('MAILBEEZ_REPLY_TO_ADDRESS_NAME_TITLE', 'PHPMailer: Reply-to Name');
mh_define('MAILBEEZ_REPLY_TO_ADDRESS_NAME_DESC', 'Reply-to name, important for bounce handling - only for PHPMailer');

mh_define('MAILBEEZ_FROM_OVERRIDE_TITLE', 'PHPMailer: Override Sender Name and Address');
mh_define('MAILBEEZ_FROM_OVERRIDE_DESC', 'Select "True" to override the "Sender Name" and "Sender Email" settings of the individual modules and instead use the "From Address" and "From Name" configured below.<br /><br />If you leave it set to "False", you will need to replace each module\'s Sender Address with the email address you set up especially for bounce handling.');


mh_define('MAILBEEZ_FROM_ADDRESS_TITLE', 'PHPMailer: From Address');
mh_define('MAILBEEZ_FROM_ADDRESS_DESC', 'Only for PHPMailer - What email address do you want to appear as the From Address in outgoing emails? (Customer will see)<br /><br /><strong>Important:</strong> Customer replies will go to this email address');

mh_define('MAILBEEZ_FROM_ADDRESS_NAME_TITLE', 'PHPMailer: From Name');
mh_define('MAILBEEZ_FROM_ADDRESS_NAME_DESC', 'Only for PHPMailer - What name do you want to appear as the From Name in outgoing emails? (Customer will see)');

mh_define('MAILBEEZ_SENDER_ADDRESS_TITLE', 'PHPMailer: Sender Address (Return-Path)');
mh_define('MAILBEEZ_SENDER_ADDRESS_DESC', 'Enter the email address you have designated as the BounceHive Mailbox (i.e. the email address you want your bounced emails to go to)<br /><br /><strong>Important:</strong> Bounce emails will go to this email address, whereas customer replies will go to the From Address.');

mh_define('MAILBEEZ_SENDER_ADDRESS_NAME_TITLE', 'PHPMailer: Sender Name');
mh_define('MAILBEEZ_SENDER_ADDRESS_NAME_DESC', 'What do you want the Sender Name to be for bounced emails delivered to the BounceHive Mailbox?');

mh_define('MAILBEEZ_EMAIL_TRANSPORT_TITLE', 'PHPMailer: Select Transport Method');
mh_define('MAILBEEZ_EMAIL_TRANSPORT_DESC', 'Only for PHPMailer - Select Transport Method');

mh_define('MAILBEEZ_SENDMAIL_PATH_TITLE', 'PHPMailer: Sendmail Path');
mh_define('MAILBEEZ_SENDMAIL_PATH_DESC', 'Only for PHPMailer - Sendmail Path');

mh_define('MAILBEEZ_SMTP_AUTH_TITLE', 'PHPMailer: SMTP Authentication');
mh_define('MAILBEEZ_SMTP_AUTH_DESC', 'Only for PHPMailer - Does the outgoing server require authentication?');

mh_define('MAILBEEZ_SMTP_USERNAME_TITLE', 'PHPMailer: SMTP Username');
mh_define('MAILBEEZ_SMTP_USERNAME_DESC', 'Only for PHPMailer - Enter the username for the email account you set up especially for bounce handling.');

mh_define('MAILBEEZ_SMTP_PASSWORD_TITLE', 'PHPMailer: SMTP Password');
mh_define('MAILBEEZ_SMTP_PASSWORD_DESC', 'Only for PHPMailer - Enter the password for the email account you set up especially for bounce handling.');

mh_define('MAILBEEZ_SMTP_MAIN_SERVER_TITLE', 'PHPMailer: SMTP Server');
mh_define('MAILBEEZ_SMTP_MAIN_SERVER_DESC', 'Only for PHPMailer - Enter the outgoing mailserver for the email account you set up especially for bounce handling.');

mh_define('MAILBEEZ_SMTP_BACKUP_SERVER_TITLE', 'PHPMailer: SMTP Backup Server');
mh_define('MAILBEEZ_SMTP_BACKUP_SERVER_DESC', 'Only for PHPMailer - Enter the backup outgoing mailserver, if available, for the email account you set up especially for bounce handling.');

mh_define('MAILBEEZ_SMTP_SECURE_TITLE', 'PHPMailer: SMTP Security');
mh_define('MAILBEEZ_SMTP_SECURE_DESC', 'Only for PHPMailer - What kind of security you wish to use for the outgoing mailserver?');

mh_define('MAILBEEZ_SMTP_PORT_TITLE', 'PHPMailer: SMTP port');
mh_define('MAILBEEZ_SMTP_PORT_DESC', 'Only for PHPMailer - Enter the port for the outgoing mailserver, default is 25');


mh_define('MAILBEEZ_DKIM_SELECTOR_TITLE', 'PHPMailer 5.2.1: DKIM Selector');
mh_define('MAILBEEZ_DKIM_SELECTOR_DESC', 'Only for PHPMailer 5.2.1 - Used with DKIM DNS Resource Record');

mh_define('MAILBEEZ_DKIM_IDENTIY_TITLE', 'PHPMailer 5.2.1: DKIM Identity');
mh_define('MAILBEEZ_DKIM_IDENTIY_DESC', 'Only for PHPMailer 5.2.1 - Used with DKIM DNS Resource Record
                                             <br />optional, in format of email address "you@yourdomain.com"');
mh_define('MAILBEEZ_DKIM_PASSPHRASE_TITLE', 'PHPMailer 5.2.1: DKIM Passphrase');
mh_define('MAILBEEZ_DKIM_PASSPHRASE_DESC', 'Only for PHPMailer 5.2.1 - Used with DKIM DNS Resource Record');

mh_define('MAILBEEZ_DKIM_DOMAIN_TITLE', 'PHPMailer 5.2.1: DKIM Domain');
mh_define('MAILBEEZ_DKIM_DOMAIN_DESC', 'Only for PHPMailer 5.2.1 - Used with DKIM DNS Resource Record
                                         <br />Optional: in format of email address "you@yourdomain.com"');

mh_define('MAILBEEZ_DKIM_PRIVATE_TITLE', 'PHPMailer 5.2.1: DKIM Private');
mh_define('MAILBEEZ_DKIM_PRIVATE_DESC', 'Only for PHPMailer 5.2.1 - Used with DKIM DNS Resource Record
                                         <br />Optional: in format of email address "you@yourdomain.com"');

mh_define('MAILBEEZ_EMAIL_USE_TXT_ONLY_TITLE', 'PHPMailer: TXT Format only');
mh_define('MAILBEEZ_EMAIL_USE_TXT_ONLY_DESC', 'Send emails in txt only');


// Bounce handling
mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_PROMO_TEXT_TITLE', 'Bounce Handling');
mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_PROMO_TEXT_DESCRIPTION', 'Outdated or unknown email addresses will result in bounced emails. Beside being annoying bounces can <b>increase the risk of being flagged as a spam sender</b>.<br /><br />The Bounce Handling module allows you to process these emails automatically and flags customers with bounces to avoid further emails.
 <br/>
 <div class="pro">To use the Bounce Handling please install the modules <a href="http://www.mailbeez.com/documentation/configbeez/config_bouncehive_advanced' . MH_LINKID_1 . '" target="_blank">BounceHive Bounce Handling</a>.</div>');

mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_TEXT_TITLE', 'Bounce Handling');
mh_define('MAILBEEZ_CONFIG_BOUNCEHIVE_TEXT_DESCRIPTION', 'This service flags customers with bounced emails to avoid sending further emails.' . ((stristr(MAILBEEZ_CONFIG_EMAIL_ENGINE, 'PHPMailer')) ? '' : '<br /><br /><b>Requires Use of the MailBeez PHPMailer: You must configure the PHPMailer before you click the "Install" button above.</b><br /><br />Please see the <a href="http://www.mailbeez.com/documentation/tutorials/bouncehive-bounce-handling-configuration-tutorial/" target="_blank">BounceHive Bounce Handling Tutorial</a> for assistance'));

mh_define('MAILBEEZ_BOUNCEHIVE_STATUS_TITLE', 'Activate Module');
mh_define('MAILBEEZ_BOUNCEHIVE_STATUS_DESC', 'Do you want to activate this module?');

mh_define('MAILBEEZ_BOUNCEHIVE_MSG_LOG_TITLE', 'Log Bounce Messages');
mh_define('MAILBEEZ_BOUNCEHIVE_MSG_LOG_DESC', 'Log Bounce Messages - they can fill more than 1kb per bounce. The messages are logged in table "' . TABLE_MAILBEEZ_BOUNCE_MSG_LOG . '" - you can always manually empty this table.');

mh_define('MAILBEEZ_BOUNCEHIVE_DO_RUN_TITLE', 'Process Bounce Handling Automatically When MailHive is Run');
mh_define('MAILBEEZ_BOUNCEHIVE_DO_RUN_DESC', 'If you have a slow email server or want to run Bounce Handling more often, set to False and create a dedicated cronjob calling the process url:<br/> ' . MAILBEEZ_MAILHIVE_URL_DIRECT . '?m=service_handler_bouncehive&ma=bounce_process');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_TITLE', 'BounceHive IMAP MailServer');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_DESC', 'The incoming MailServer for the email account you set up especially for bounce handling');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_USERNAME_TITLE', 'BounceHive MailServer Username');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_USERNAME_DESC', 'The username for the email account you set up especially for bounce handling');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PASSWORD_TITLE', 'BounceHive MailServer Pasword');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PASSWORD_DESC', 'The password for the email account you set up especially for bounce handling');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PORT_TITLE', 'BounceHive MailServer Port');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PORT_DESC', 'Just keep 143 unless you use a different port');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SERVICE_OPTION_TITLE', 'BounceHive MailServer Security Option');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SERVICE_OPTION_DESC', 'The incoming MailServer security options (none, tls, notls, ssl, etc.), default is "notls"');

mh_define('MAILBEEZ_BOUNCEHIVE_MAXMESSAGES_TITLE', 'BounceHive Max Messages');
mh_define('MAILBEEZ_BOUNCEHIVE_MAXMESSAGES_DESC', 'Maximum number of messages to process per run');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_HARDMAILBOX_TITLE', 'Folder for Hard Bounced');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_HARDMAILBOX_DESC', 'Which folder should MailBeez move hard bounce emails into? (GMAIL requires manual set up of folders)');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SOFTMAILBOX_TITLE', 'Folder for Soft Bounced');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_SOFTMAILBOX_DESC', 'Which folder should MailBeez move soft bounce emails into? (GMAIL requires manual set up of folders)');

mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PROCESSEDMAILBOX_TITLE', 'Folder for Processed Emails');
mh_define('MAILBEEZ_BOUNCEHIVE_MAILSERVER_PROCESSEDMAILBOX_DESC', 'Which folder should MailBeez move non-bounce emails into? (GMAIL requires manual set up of folders)');

mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_DAYS_TITLE', 'Softbounce: Delay');
mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_DAYS_DESC', 'How many days should MailBeez wait before trying to re-send an email to a softbounced email-address?<br /><br />MailBeez will not send any emails during this delay period - eventually blocked emails are cancelled.');

mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_TITLE', 'Softbounce: Convert Into Hardbounce Count');
mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_DESC', 'After this number a softbounces, an email becomes a hardbounce');

mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_DAYS_TITLE', 'Time Frame For Converting Softbounces Into Hardbounces');
mh_define('MAILBEEZ_BOUNCEHIVE_SOFT_HARD_COUNT_DAYS_DESC', 'If an email softbounces the configured number of times within this many days, it will be converted into a hardbounce');


// MailBeez Analytics

mh_define('MAILBEEZ_CONFIG_ANALYTICS_TEXT_TITLE', 'MailBeez Analytics');
mh_define('MAILBEEZ_CONFIG_ANALYTICS_TEXT_DESCRIPTION', 'Configure MailBeez Analytics for tracking of open rates and click rates');

mh_define('MAILBEEZ_ANALYTICS_STATUS_TITLE', 'Enable MailBeez Analytics');
mh_define('MAILBEEZ_ANALYTICS_STATUS_DESC', "Do you want to enable MailBeez Analytics?<br />
Insert following code at the end of 'includes/application_top.php':
<pre>
// MailBeez Click and Order tracker
require(DIR_FS_CATALOG . 'mailhive/includes/clicktracker.php');
</pre>
");

mh_define('MAILBEEZ_ANALYTICS_DO_RUN_TITLE', 'Process MailBeez Analytics Information With Every Run of MailHive');
mh_define('MAILBEEZ_ANALYTICS_DO_RUN_DESC', 'If set to False you need to run the Module "Service Handler for MailBeez Analytics" manually or set up a dedicated cronjob: ' . MAILBEEZ_MAILHIVE_URL_DIRECT . '?m=service_handler_analytics&ma=tracking_process');

mh_define('MAILBEEZ_ANALYTICS_AUTOINSERT_PIX_TITLE', 'Insert Tracking Pix Automatically');
mh_define('MAILBEEZ_ANALYTICS_AUTOINSERT_PIX_DESC', 'Inserts the Tracking Pix at the end of the module body content. If that causes layout issues please set to false and insert this Tracking Tag into your main template before closing the body-tag: <b>{$MAILBEEZ_TRACKER_PIX}</b>');

mh_define('MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO_TITLE', 'Real-Time Open Rates');
mh_define('MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO_DESC', 'Update Open rates every 30sec. For high-load sites set this to False and set up a cronjob instead');

mh_define('MAILBEEZ_ANALYTICS_STATUS_BUTTON', 'Activate User Tracking');

mh_define('MAILBEEZ_ANALYTICS_REWRITE_FORMAT_TITLE', 'Add Click-Tracking to URLs in TXT format?');
mh_define('MAILBEEZ_ANALYTICS_REWRITE_FORMAT_DESC', 'Do you want to add Click-Tracking to URLs in TXT format?');

mh_define('MAILBEEZ_MAILHIVE_MODE_SIM_RESTART_BUTTON', 'Restart');
mh_define('MAILBEEZ_SIMULATION_RESTARTED_MSG', 'Simulation Restarted');

mh_define('MAILBEEZ_CONFIG_SPAMCOMPLIANCE_TEXT_TITLE', 'Spam Compliance');
mh_define('MAILBEEZ_CONFIG_SPAMCOMPLIANCE_TEXT_DESCRIPTION', 'Control the level of Spam Compliance. The more restrictive your settings, the higher the level of spam compliance. Depending on your local law you might be able to loosen the settings. Your are responsible for using MailBeez in compliance with the law.
<br>
<br>
If you require more control please install these MailBeez Modules:
<br>
"<a href="http://www.mailbeez.com/documentation/configbeez/config_spamcompliance_advanced/' . MH_LINKID_1 . '" target="_blank">Advanced Spam Compliance Framework</a>" and
<br>
"<a href="http://www.mailbeez.com/documentation/configbeez/config_block_admin/' . MH_LINKID_1 . '" target="_blank">Advanced Opt-Out with Admin</a>"');


mh_define('MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION_TITLE', 'Newsletter Subscriber Check');
mh_define('MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION_DESC', 'Only customers with a valid newsletter subscription will receive emails generated by MailBeez. Certain modules, i.e. the Payment Dunning Reminders, will ignore the Newsletter Subscription Check. (restrictive: "True")
<br />
<br />
If you require more control please install the MailBeez Module
"<a href="http://www.mailbeez.com/documentation/configbeez/config_spamcompliance_advanced/' . MH_LINKID_1 . '" target="_blank">Advanced Spam Compliance Framework</a>"');


mh_define('MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR_TITLE', 'Opt-Out Link Behaviour');
mh_define('MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR_DESC', 'Choose how the opt-out link behaves.<br /><br />
<b>Module</b>: Only the current module is blocked<br />
<b>Global</b>: All module are blocked<br />
Certain modules e.g. Payment Dunning Reminder will ignore the "Global Setting".  (restrictive: "Global")<br /><br />
If you require more control please install the MailBeez Module
"<a href="http://www.mailbeez.com/documentation/configbeez/config_block_admin/' . MH_LINKID_1 . '" target="_blank">Advanced Opt-Out with Admin</a>"');


mh_define('MAILBEEZ_REPORT_DETAILS', '<img src="' . MH_CATALOG_SERVER . MH_ADMIN_DIR_WS_IMAGES .'details.png" width="12"
                                                     height="12" align="absmiddle" hspace="3" border="0">Details');

mh_define('MAILBEEZ_REPORT_DATE', 'Date');
mh_define('MAILBEEZ_REPORT_LINK_URL', 'Link-URL');
mh_define('MAILBEEZ_REPORT_USER_AGENT', 'Email Software');
mh_define('MAILBEEZ_REPORT_ORDER', 'Order#');

mh_define('MAILBEEZ_REPORT_NO_OPEN_LOG', 'No open log found.<br/>Open log might have been deleted or indirect opening (clicking)');
mh_define('MAILBEEZ_REPORT_NO_CLICK_TRACKING', 'No Click-Data available.');
mh_define('MAILBEEZ_REPORT_NO_ORDER', 'No order-data found.');

mh_define('MAILBEEZ_TEXT_DISPLAY_NUMBER_OF_ITEMS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> items)');

mh_define('MAILBEEZ_ANALYTICS_SPLITPAGE_NUM_TITLE', 'Rows per Page');
mh_define('MAILBEEZ_ANALYTICS_SPLITPAGE_NUM_DESC', 'Number of data rows per page');

mh_define('MAILBEEZ_CERTIFICATE_BUTTON', 'Edit');
mh_define('TABLE_HEADING_CERTIFICATE_INFO', 'Certificate');
mh_define('TABLE_HEADING_CERTIFICATE_HELP_TOP', 'Enter your certificate key. If you have not received your personal certificate key together with the module delivery email from avangate please <a href="http://www.mailbeez.com/about/contact/" target="_blank">contact MailBeez</a>');

mh_define('MAILBEEZ_CERTIFICATE_STATUS_VALID', 'Valid');
mh_define('MAILBEEZ_CERTIFICATE_STATUS_INVALID', 'Invalid');
mh_define('MAILBEEZ_CERTIFICATE_STATUS_ENTER', 'Please Enter');


// MailBeez V2.7
mh_define('MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT_TITLE', 'Encode Email Subject');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT_DESC', 'Avoid strange characters showing up in subject lines');


mh_define('MAILBEEZ_INSTALL_WARNING_TEMPLATE_C', "<h1>Please update your configuration:</h1>The Folder <blockquote><b>" . MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR . "</b></blockquote> must be writeable - but it is not. <br><br><font color='red'>Make sure this folder is writeable by giving the right permissions with your FTP tool.<br>
If the folder is writeable please empty the folder.</font> <br><br>Then reload this page to install MailBeez.");

mh_define('MAILBEEZ_VERSION_CHECK_MSG_UPDATE_MODULES', 'Following Modules are not compatible with MailBeez V. ' . MAILBEEZ_VERSION_DISPLAY .  ' need to be updated immediately:');

mh_define('MAILBEEZ_VERSION_CHECK_MSG_UPDATE_MODULES_TEXT', 'Please download the latest Version of the modules using the Download-link you received with the delivery email from avangate after purchase. <br>Then please follow the update/installation instructions of the module.');


// MailBeez V2.7.4
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_EMOGRIFY_TITLE', 'Convert CSS into inline style');
mh_define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_EMOGRIFY_DESC', 'For best layouts across different email clients set to True. Requires PHP5.');

mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE_TITLE', 'PHPMailer: Set PHPMailer Debug Mode');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE_DESC', 'Activate Debug Mode for PHPMailer');

mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP_TITLE', 'PHPMailer: TXT Wordwrap');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP_DESC', 'Sets word wrapping on the body of the message to a given number of characters. Set to 0 for disabling wordwrapping.');

mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_TITLE', 'Email Encoding');
mh_define('MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_DESC', 'Set the encoding for emails. Leave empty for automatic encoding detection. Values are e.g. "UTF-8", "ISO-8859-1"');

mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_MEMORY_LIMIT_TITLE', 'PHP Memory Limit');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_MEMORY_LIMIT_DESC', 'Set the PHP memory limit for the sending process, default 512M');

mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INGORE_USER_ABORT_TITLE', 'Set Ignore User Abort');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INGORE_USER_ABORT_DESC', 'Sets whether a client disconnect should cause a script to be aborted. Default: True');

mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TIME_LIMIT_TITLE', 'PHP Execution Time Limit');
mh_define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TIME_LIMIT_DESC', 'Set the PHP executing time limit. Default: 30sec');

mh_define('MH_SYSTEMCHECK_DB_DISABLED_TITLE', 'Disable System DB Check');
mh_define('MH_SYSTEMCHECK_DB_DISABLED_DESC', 'Disable the Systemcheck for corruptive Orders Database. Default: False');

mh_define('MAILBEEZ_ANALYTICS_BEGIN_OF_TIME_TITLE', 'Beginning of Time');
mh_define('MAILBEEZ_ANALYTICS_BEGIN_OF_TIME_DESC', 'Set the Beginning of Time for MailBeez Analytics Summary. Should be the date you started tracking orders. Default: 2000-01-01 00:00:00');


mh_define('MAILBEEZ_SYSTEM_CHECK_MSG', 'System-Check: Found Issues!');
mh_define('MAILBEEZ_BUTTON_SYSTEMCHECK_REFRESH', 'click to check again');

mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_CORRUPTED_DB', '<b>Your Order Database partly is corrupted</b><br>
There are <b>%s damaged orders</b>. MailBeez won\'t be able to generate order-related Campaigns for the corrupted entries!<br>
Find more information about this issue and how to solve it on <a href="http://www.mailbeez.com/documentation/mailbeez/service_db_repair_order/' . MH_LINKID_1 . '" target="_blank">Service-Module: "Repair Orders DB"</a>');


mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PHP_MBSTRING', 'PHP extension mbstring not found - read more on http://www.php.net/manual/en/mbstring.installation.php');

mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PHP_SUHOSIN_EVAL', 'Suhosin is installed and the <b>eval()</b> function is disabled - Please allow the usage of eval(). Contact your Server administrator.');
mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PHP_SUHOSIN_EVAL_GZINFLATE', 'Suhosin is installed and the <b>gzinflate()</b> function is blacklisted - Please allow the usage of gzinflate(). Contact your Server administrator.');

mh_define('MAILBEEZ_SYSTEM_CHECK_MSG_PATH', '
<b>Stylesheet and Scripts are not loaded.</b>
 <br>
The configuration value of HTTP_CATALOG_SERVER is incorrect, please check in "&lt;admin&gt;/includes/configure.php".
 <br>
 The configuration of HTTP_CATALOG_SERVER + DIR_WS_CATALOG must sum up to your Store URL.');

mh_define('MAILBEEZ_DASHBOARD_ANALYTICS_MSG', 'Please install and activate user tracking,<br>to see MailBeez Analytics data');

mh_define('MAILBEEZ_STATSBAR_LABEL_EMAILS', 'Emails');
mh_define('MAILBEEZ_STATSBAR_LABEL_SENT', 'sent');
mh_define('MAILBEEZ_STATSBAR_LABEL_DELIVERED', 'delivered');
mh_define('MAILBEEZ_STATSBAR_LABEL_BEGIN_OF_TIME', 'since');
mh_define('MAILBEEZ_STATSBAR_LABEL_OPENED', 'opened');
mh_define('MAILBEEZ_STATSBAR_LABEL_CLICKED', 'clicked');
mh_define('MAILBEEZ_STATSBAR_LABEL_ORDERED', 'ordered');
mh_define('MAILBEEZ_STATSBAR_LABEL_REVENUE', 'Revenue (Sub-Total)');
mh_define('MAILBEEZ_STATSBAR_LABEL_COUPON_VAL', 'Coupons total');
mh_define('MAILBEEZ_STATSBAR_LABEL_MOBILE', 'mobile');

mh_define('MAILBEEZ_STATSBAR_RESPONSIVE_INFO', '<b>How-To:</b> switch to responsive emails... <a href="http://www.mailbeez.de/dokumentation/responsive-emails/' . MH_LINKID_1 . '" target="_blank">read more</a>');

?>