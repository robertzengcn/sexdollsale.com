<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

/*

  v2.7

 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

/* -------------------------------------------------------------------------------------
* 	ID:						$Id: inc.php_mail.php 210 2011-08-27 12:09:38Z siekiera $
* 	Letzter Stand:			$Revision: 210 $
* 	zuletzt geaendert von:	$Author: siekiera $
* 	Datum:					$Date: 2011-08-27 14:09:38 +0200 (Sat, 27 Aug 2011) $
*
* 	SEO:mercari by Siekiera Media
* 	http://www.seo-mercari.de
*
* 	Copyright (c) since 2011 SEO:mercari
* --------------------------------------------------------------------------------------
* 	based on:
* 	(c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
* 	(c) 2002-2003 osCommerce - www.oscommerce.com
* 	(c) 2003     nextcommerce - www.nextcommerce.org
* 	(c) 2005     xt:Commerce - www.xt-commerce.com
*
* 	Released under the GNU General Public License
* ----------------------------------------------------------------------------------- */


function mh_php_mail($sender_email_address,
                     $sender_email_name,
                     $from_email_address,
                     $from_email_name,
                     $to_email_address,
                     $to_name,
                     $forwarding_to,
                     $reply_address,
                     $reply_address_name,
                     $path_to_attachement,
                     $path_to_more_attachements,
                     $email_subject,
                     $message_body_html,
                     $message_body_plain,
                     $mail)
{

    // echo 'to: ' . $to_email_address . '****<br /> ';

    switch (MAILBEEZ_CONFIG_EMAIL_ENGINE) {
        case 'PHPMailer built-in':
            $php_mailer = new PHPMailer();
            $php_mailer->PluginDir = MH_DIR_FS_CATALOG . 'includes/classes/';
            break;
        case 'PHPMailer 5.2.1':
            $phpmailer_path = 'mailhive/common/classes/PHPMailer_5.2.1/';
            require_once(MH_DIR_FS_CATALOG . $phpmailer_path . 'mailbeez.class.phpmailer.php');
            $php_mailer = new MH_PHPMailer();
            $php_mailer->PluginDir = MH_DIR_FS_CATALOG . $phpmailer_path;
            if ($_SESSION['language'] == 'german') {
                $php_mailer->SetLanguage("de", MH_DIR_FS_CATALOG . $phpmailer_path . 'language/');
            } else {
                $php_mailer->SetLanguage("en", MH_DIR_FS_CATALOG . $phpmailer_path . 'language/');
            }

            break;
        case 'PHPMailer 2.0.4':
            $phpmailer_path = 'mailhive/common/classes/PHPMailer_2.0.4/';
            require_once(MH_DIR_FS_CATALOG . $phpmailer_path . 'mailbeez.class.phpmailer.php');
            $php_mailer = new MH_PHPMailer();
            $php_mailer->PluginDir = MH_DIR_FS_CATALOG . $phpmailer_path;
            if ($_SESSION['language'] == 'german') {
                $php_mailer->SetLanguage("de", MH_DIR_FS_CATALOG . $phpmailer_path);
            } else {
                $php_mailer->SetLanguage("en", MH_DIR_FS_CATALOG . $phpmailer_path);
            }
            break;
    }

    if ($sender_email_address != '') {
        //$php_mailer->Sender = '"' . $sender_email_name . '" <' . $sender_email_address . '>';
        $php_mailer->Sender = $sender_email_address;
    }

    // V2.6.3
    // todo: make configurable
    // $php_mailer->CharSet = '';


    $php_mailer->XMailer = 'mailbeez.com Version ' . MAILBEEZ_VERSION;

    $php_mailer->SMTPDebug = (MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE == 'True') ? true : false;

    $php_mailer->MessageID = $mail['email_message_id'];

    $php_mailer->DKIM_selector = MAILBEEZ_DKIM_SELECTOR;

    /**
     * Used with DKIM DNS Resource Record
     * optional, in format of email address 'you@yourdomain.com'
     * @var string
     */
    $php_mailer->DKIM_identity = MAILBEEZ_DKIM_IDENTIY;

    /**
     * Used with DKIM DNS Resource Record
     * @var string
     */
    $php_mailer->DKIM_passphrase = MAILBEEZ_DKIM_PASSPHRASE;

    /**
     * Used with DKIM DNS Resource Record
     * optional, in format of email address 'you@yourdomain.com'
     * @var string
     */
    $php_mailer->DKIM_domain = MAILBEEZ_DKIM_DOMAIN;

    /**
     * Used with DKIM DNS Resource Record
     * optional, in format of email address 'you@yourdomain.com'
     * @var string
     */
    $php_mailer->DKIM_private = MAILBEEZ_DKIM_PRIVATE;


    $php_mailer->CharSet = MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING_SET;


    if (MAILBEEZ_EMAIL_TRANSPORT == 'smtp') {
        $php_mailer->IsSMTP();
        $php_mailer->SMTPKeepAlive = true; // set mailer to use SMTP
        $php_mailer->SMTPAuth = (MAILBEEZ_SMTP_AUTH == 'True') ? true : false; // turn on SMTP authentication true/false
        $php_mailer->Username = MAILBEEZ_SMTP_USERNAME; // SMTP username
        $php_mailer->Password = MAILBEEZ_SMTP_PASSWORD; // SMTP password
        $php_mailer->Host = MAILBEEZ_SMTP_MAIN_SERVER . ';' . MAILBEEZ_SMTP_BACKUP_SERVER; // specify main and backup server "smtp1.example.com;smtp2.example.com"

        $php_mailer->SMTPSecure = (MAILBEEZ_SMTP_SECURE == 'none') ? '' : MAILBEEZ_SMTP_SECURE; // sets the prefix to the servier
        $php_mailer->Port = MAILBEEZ_SMTP_PORT; // set the SMTP port for the server

    }

    if (MAILBEEZ_EMAIL_TRANSPORT == 'sendmail') { // set mailer to use SMTP
        $php_mailer->IsSendmail();
        $php_mailer->Sendmail = MAILBEEZ_SENDMAIL_PATH;
    }

    if (MAILBEEZ_EMAIL_TRANSPORT == 'mail') {
        $php_mailer->IsMail();
    }

    if (MH_PLATFORM == 'zencart') {
        // zencart does a & -> &amp; in zen_href_link for html & txt and for text only a &amp; -> & in zen_mail()
        while (strstr($message_body_plain, '&amp;')) $message_body_plain = str_replace('&amp;', '&', $message_body_plain);
    }
    $message_body_plain = str_replace('<br />', " \n", $message_body_plain);
    $message_body_plain = strip_tags($message_body_plain);

    if (MAILBEEZ_EMAIL_USE_TXT_ONLY != 'True' && $message_body_html != '') {
        $php_mailer->IsHTML(true);
        $php_mailer->Body = $message_body_html;
        $php_mailer->AltBody = $message_body_plain;
    } else {
        $php_mailer->IsHTML(false);
        $php_mailer->Body = $message_body_plain;
    }


    $php_mailer->From = $from_email_address;
    $php_mailer->FromName = $from_email_name;

    $php_mailer->AddAddress($to_email_address, $to_name);

    if ($forwarding_to != '')
        $php_mailer->AddBCC($forwarding_to);

    if ($reply_address != '') {
        $php_mailer->AddReplyTo($reply_address, $reply_address_name);
    }

    $php_mailer->WordWrap = MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP;

    if ($path_to_attachement != '') {
        $php_mailer->AddAttachment($path_to_attachement);
    }

    if ($path_to_more_attachements != '') {
        $php_mailer->AddAttachment($path_to_more_attachements);
    }

    $php_mailer->Subject = $email_subject;

    if (!$php_mailer->Send()) {
        echo "Email could not be sent<p>";
        echo "Mailer Error: " . htmlentities($php_mailer->ErrorInfo);
        return array('message_id' => -1);
    }

    return array('message_id' => $mail['message_id']);
}


?>