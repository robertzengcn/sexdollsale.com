EMAIL TEST TOOL
This simple tool emulates the email sending behavior used in Zen Cart v1.3.x, v1.5.x, v1.6.x
Its purpose is ONLY to aid in pointing out server configuration problems preventing emails from being sent properly.
Configure it as described below, and then run it in your browser, and give the output to your hosting company. If they're worth their salt they'll be able to fix your issues in a few minutes.

BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS


1. Use FTP to upload the files in this package to a temporary folder on your webserver. Suggestion: use /public_html/email_test as the location.
To be very clear so there's no misunderstanding, this means you copy these files from your PC to your webserver, by drag-and-drop from your PC directory to your webserver directory:
- t.class.base.php -> public_html/email_test/t.class.base.php
- t.class.notifier.php -> public_html/email_test/t.class.notifier.php
- t.class.phpmailer.php -> public_html/email_test/t.class.phpmailer.php
- t.class.smtp.php -> public_html/email_test/t.class.smtp.php
- t.functions_email.php -> public_html/t.functions_email.php
- email_test_tool.php -> public_html/email_test_tool.php

(naturally, if your web server uses something other than "public_html" (such as htdocs, httpdocs, www, etc), substitute that of course)

2. Edit the /public_html/email_test_tool.php file and configure the various things as needed:

a) Enter your test email addresses here:
$to_address = 'noreply@example.com';
$from_address = 'noreply@example.com';

b) This can be left as-is, unless you discover that it is actually tripping a spam filter, in which case you can change it. Should be fine as-is.
$email_subject = 'Email test';

c) As described ... same as you have in your Zen Cart admin->configuration->email options screen: 
define('EMAIL_TRANSPORT', 'smtp'); // can be 'PHP' or 'smtp' or 'smtpauth' or 'sendmail' or 'sendmail-f'
define('EMAIL_LINEFEED', 'LF');    // usually LF for linux hosts, CRLF for windows hosts or SMTP/SMTPAUTH mode

d) These are the SMTP settings, same as you have in your Zen Cart admin->configuration->email options screen:
define('EMAIL_SMTPAUTH_MAIL_SERVER', 'smtp.example.com');
define('EMAIL_SMTPAUTH_MAIL_SERVER_PORT', '25');

define('EMAIL_SMTPAUTH_MAILBOX', 'user@example.com');
define('EMAIL_SMTPAUTH_PASSWORD', 'examplepassword');

e) Debug-mode "2" should be sufficient, but if even more detail is needed, increase it to "5"
 * Set email system debugging off or on (only relevant in SMTP or SMTPAUTH modes)
 * 0=off
 * 1=show SMTP status errors
 * 2=show SMTP server responses
 * 4=show SMTP readlines if applicable
 * 5=maximum information

$debug_mode = 2; // can also set dynamically by adding ?debug=5 to URL

f) Most of the time this should be set to 'Yes'. Setting it to 'No' might make emails appear to be spoofed, and thus get rejected by spam filters.
define('EMAIL_SEND_MUST_BE_STORE', 'Yes');


3. ADVANCED USE ONLY:
a) Leave this alone unless your hosting company's senior technicians say 8bit is absolutely required instead of the default of 7bit
//define('EMAIL_ENCODING_METHOD', '8bit');

b) The system automatically uses SSL protocol when the SMTP port is set to an SSL port number. Setting a protocol here will override that. THIS SHOULD NEVER BE NECESSARY except in extremely customized scenarios where the server administrator has done some highly specialized tweaking. And in that case they'll know exactly what to do with this. Otherwise leave these as-is:
define('SMTPAUTH_EMAIL_PROTOCOL', 'none');
define('SMTPAUTH_EMAIL_CERTIFICATE_CONTEXT', '');

4. Use your browser and point it to: www.your_site.com/email_test/email_test_tool.php
This will cause it to send an email IMMEDIATELY and will show the output of the attempted sending of that email, directly on-screen.
If successful, an email will arrive at the expected destination and the messages on-screen will indicate success.
If there was a failure in delivering the email to the mailserver for sending, the error messages will be displayed on-screen for detailed debugging. This information should be given to your server administrator (hosting company) for assistance in fixing the problems and reasons why it was actively rejected.
If the messages on-screen indicate success but the email never arrives, then you probably have a blacklisting problem or similar issue where your server's outbound messages are being treated as spam and dumped into a black hole silently ... which is common when not using SMTP or SMTPAUTH for sending messages.

Further reading: 
http://www.zen-cart.com/wiki/index.php/Troubleshoot_-_Email_Problems
http://www.zen-cart.com/wiki/index.php/Troubleshoot_-_Understanding_Causes_Of_Lost_Emails
 
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
BE SURE TO REMOVE THESE FILES FROM YOUR SERVER SO THEY CAN'T BE ABUSED BY SPAMMERS OR HACKERS
