<?php
/**
 * This is a test script to test sending out emails using the same code base that Zen Cart uses.
 */
// Enter your test email addresses here
$to_address = 'lauritczz@163.com';
$from_address = 'noreply@sales.toy-stores-online.com';
$email_subject = 'Email test';

define('EMAIL_TRANSPORT', 'smtpauth'); // can be 'PHP' or 'smtp' or 'smtpauth' or 'sendmail' or 'sendmail-f'
define('EMAIL_LINEFEED', 'LF');    // usually LF for linux hosts, CRLF for windows hosts or SMTP/SMTPAUTH mode

define('EMAIL_SMTPAUTH_MAIL_SERVER', 'rsb28.rhostbh.com');
define('EMAIL_SMTPAUTH_MAIL_SERVER_PORT', '25');

define('EMAIL_SMTPAUTH_MAILBOX', 'noreply@sales.toy-stores-online.com');
define('EMAIL_SMTPAUTH_PASSWORD', 'devW{D^__-.V');
/**
 * Set email system debugging off or on (only relevant in SMTP or SMTPAUTH modes)
 * 0=off
 * 1=show SMTP status errors
 * 2=show SMTP server responses
 * 4=show SMTP readlines if applicable
 * 5=maximum information
 */
$debug_mode = 5; // can also set dynamically by adding ?debug=5 to URL


define('EMAIL_SEND_MUST_BE_STORE', 'Yes');
//define('EMAIL_ENCODING_METHOD', '8bit');

define('SMTPAUTH_EMAIL_PROTOCOL', '');
define('SMTPAUTH_EMAIL_CERTIFICATE_CONTEXT', '');


/**************************************************
 * NO NEED TO MAKE CHANGES TO ANYTHING BELOW HERE!
 **************************************************/
if (isset($_GET['debug']) && $_GET['debug'] != '') $debug_mode = $_GET['debug'];
define('EMAIL_SYSTEM_DEBUG', (int)$debug_mode);
define('IS_ADMIN_FLAG', FALSE);
define('DIR_FS_CATALOG', '');
define('DIR_WS_CLASSES', '');
define('STORE_NAME', '');
define('HTTP_SERVER', '');
error_reporting(E_ALL);ini_set('display_errors', 1);
define('DEVELOPER_OVERRIDE_EMAIL_ADDRESS', $to_address);
$from_email_address = $from_address;
define('STORE_OWNER_EMAIL_ADDRESS', $from_email_address);
define('EMAIL_FROM', $from_email_address);

require ('t.class.base.php');
require ('t.class.notifier.php');
require ('t.class.phpmailer.php');
require ('t.class.smtp.php');
$_SESSION['languages_code'] = 'en';
global $zco_notifier;
$zco_notifier = new notifier();
class db extends base {
  function __construct(){}
  function Execute($query) {
    //echo $query;
    $result = array('customers_email_format'=>'TEXT');
    return new db_result($result);
  }
  function bindVars($string) {return $string;}
}
class db_result {
  function __construct($val) {
    $this->fields = $val;
    return $this->fields;
  }
  function RecordCount() {return 1;}
}
$db = new db;
class messageStack extends base {
  function add() {}
  function add_session(){}
}
$messageStack = new messageStack;
function zen_not_null($value) {
  if (is_array($value)) {
    if (sizeof($value) > 0) {
      return true;
    } else {
      return false;
    }
  } elseif( is_a( $value, 'queryFactoryResult' ) ) {
    if (sizeof($value->result) > 0) {
      return true;
    } else {
      return false;
    }
  } else {
    if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
      return true;
    } else {
      return false;
    }
  }
}
define('EMAIL_USE_HTML', 'false');
define('ENTRY_EMAIL_ADDRESS_CHECK', 'false');
define('SEND_EMAILS', 'true');
define('EMAIL_ARCHIVE', 'false');
define('EMAIL_FRIENDLY_ERRORS', 'false');
define('ADMIN_EXTRA_EMAIL_FORMAT', 'TEXT');
define('DIR_FS_EMAIL_TEMPLATES', '');
define('CURRENCIES_TRANSLATIONS', '£:&eur;');
define('TABLE_CUSTOMERS', '');
define('EMAIL_SEND_FAILED','ERROR: Failed sending email to: "%s" <%s> with subject: "%s"');
define('EMAIL_ATTACHMENTS_ENABLED', false);
define('EMAIL_ATTACH_EMBEDDED_IMAGES', 'Yes');
$PHPMAILER_LANG = array();
$PHPMAILER_LANG['provide_address']      = 'You must provide at least one recipient email address.';
$PHPMAILER_LANG['mailer_not_supported'] = ' mailer is not supported.';
$PHPMAILER_LANG['execute']              = 'Could not execute: ';
$PHPMAILER_LANG['instantiate']          = 'Could not instantiate mail function.';
$PHPMAILER_LANG['authenticate']         = 'SMTP Error: Could not authenticate.';
$PHPMAILER_LANG['from_failed']          = 'The following From address failed: ';
$PHPMAILER_LANG['recipients_failed']    = 'SMTP Error: The following recipients failed: ';
$PHPMAILER_LANG['data_not_accepted']    = 'SMTP Error: Data not accepted.';
$PHPMAILER_LANG['connect_host']         = 'SMTP Error: Could not connect to SMTP host.';
$PHPMAILER_LANG['file_access']          = 'Could not access file: ';
$PHPMAILER_LANG['file_open']            = 'File Error: Could not open file: ';
$PHPMAILER_LANG['encoding']             = 'Unknown encoding: ';
$PHPMAILER_LANG['signing']              = 'Signing Error: ';
$PHPMAILER_LANG['smtp_error']           = 'SMTP server error: ';
require ('t.functions_email.php');
?>

<span style="color: red; font-weight: bold;">ALERT: For prevent misuse, please BE SURE TO DELETE THIS email_test_tool.php FILE FROM YOUR SERVER!</span>
<br>
Sending the following by email:<br>
TO: <?php echo $to_address; ?><br>
FROM: <?php echo $from_email_address; ?><br>
SUBJECT: <?php echo $email_subject; ?><br>
<br>
EMAIL METHOD: <?php echo EMAIL_TRANSPORT; ?><br>
EMAIL_LINEFEED: <?php echo EMAIL_LINEFEED; ?><br>
<?php if (EMAIL_TRANSPORT == 'PHP' || EMAIL_TRANSPORT == 'sendmail') echo '----<br>' . ini_get('SMTP') . '<br>' . ini_get('sendmail_from') . '<br>' . ini_get('smtp_port') . '<br>' . ini_get('sendmail_path') . '<br>----<br>'; ?>
<?php if (EMAIL_TRANSPORT == 'smtp' || EMAIL_TRANSPORT == 'smtpauth') {?>
----<br>
EMAIL_SMTPAUTH_MAIL_SERVER: <?php echo EMAIL_SMTPAUTH_MAIL_SERVER; ?><br>
EMAIL_SMTPAUTH_MAIL_SERVER_PORT: <?php echo EMAIL_SMTPAUTH_MAIL_SERVER_PORT; ?><br>
<?php if (EMAIL_TRANSPORT == 'smtpauth') {?>
EMAIL_SMTPAUTH_MAILBOX: <?php echo EMAIL_SMTPAUTH_MAILBOX; ?><br>
EMAIL_SMTPAUTH_PASSWORD: <?php echo str_repeat('*', strlen(EMAIL_SMTPAUTH_PASSWORD)); ?><br>
<?php } ?>
----<br>
<?php } ?>
DEBUG MODE: <?php echo EMAIL_SYSTEM_DEBUG; ?><br>
<br><br>
Results:<br>
<pre>
<?php
$result = zen_mail('Test Address', $to_address, $email_subject, 'This is just a test message. Please delete.', 'Email Tester', $from_email_address, array(), 'no_archive', '', '','');
?>
</pre>
<?php echo $result;

