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

  abstraction layer for email-engine

  v2.7.6

 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


if (MAILBEEZ_CONFIG_EMAIL_BUGFIX_1 == 'True') {
    $GLOBALS['mailbeez_bugfix_1_pattern'] = array();
    $GLOBALS['mailbeez_bugfix_1_replace'] = array();

    $GLOBALS['mailbeez_bugfix_1_pattern'][0] = '/\..php/';
    $GLOBALS['mailbeez_bugfix_1_replace'][0] = '.php';

    $GLOBALS['mailbeez_bugfix_1_pattern'][1] = '/\..png/';
    $GLOBALS['mailbeez_bugfix_1_replace'][1] = '.png';

    $GLOBALS['mailbeez_bugfix_1_pattern'][2] = '/\..jpeg/';
    $GLOBALS['mailbeez_bugfix_1_replace'][2] = '.jpeg';

    $GLOBALS['mailbeez_bugfix_1_pattern'][3] = '/\..jpg/';
    $GLOBALS['mailbeez_bugfix_1_replace'][3] = '.jpg';

    $GLOBALS['mailbeez_bugfix_1_pattern'][4] = '/\..gif/';
    $GLOBALS['mailbeez_bugfix_1_replace'][4] = '.gif';

}

switch (MH_PLATFORM) {
    case 'oscommerce':
    case 'creloaded':
    case 'digistore':
        require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/oscommerce/emailMB.php');
        break;
    case 'zencart':
        break;
    case 'xtc':
    case 'gambio':
    case 'mercari':
        break;
    default:
        echo 'platform not supported';
}

// switch between systems
function mh_sendEmail($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt)
{
    if (MAILBEEZ_MAILHIVE_STATUS == 'False') {
        return;
    }

    if (MAILBEEZ_CONFIG_EMAIL_BUGFIX_1 == 'True') {
        // try to fix that wired '..' issue
        // where e.g. file.php becomes file..php, image.png becomes image..png
        // could be already in the generated output (not very likely)

        $output_content_html = preg_replace($GLOBALS['mailbeez_bugfix_1_pattern'], $GLOBALS['mailbeez_bugfix_1_replace'], $output_content_html);
    }

    $uniq_id = uniqid();
    if (!empty($mail->Hostname)) {
        $serverhostname = $mail->Hostname;
    } elseif (isset($_SERVER['SERVER_NAME'])) {
        $serverhostname = $_SERVER['SERVER_NAME'];
    } else {
        $serverhostname = 'localhost.localdomain';
    }


    // message-id for tracking open / click
    $mail['message_id'] = (isset($mail['message_id'])) ? $mail['message_id'] : sprintf("%s", $uniq_id);
    // message id for PHPMailer bouncehandling
    $mail['email_message_id'] = (isset($mail['email_message_id'])) ? $mail['email_message_id'] : sprintf("<mb.%s@%s>", $uniq_id, $serverhostname);

    // parse message-id into email
    $output_content_html = str_replace("%message-id%", $mail['message_id'], $output_content_html);
    $output_content_txt = str_replace("%message-id%", $mail['message_id'], $output_content_txt);


    // allow to set from fields
    $from = (isset($mail['mailbeez_from_email']) && $mail['mailbeez_from_email'] != '') ? $mail['mailbeez_from_email'] : $from;
    $from_name = (isset($mail['mailbeez_from_name']) && $mail['mailbeez_from_name'] != '') ? $mail['mailbeez_from_name'] : $from_name;


    if (defined('MAILBEEZ_EMAIL_ARCHIVE_STATUS') && MAILBEEZ_EMAIL_ARCHIVE_STATUS == 'True') {
        require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_email_archive.php');
        config_email_archive::archive($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt);
    }


    if (isset($mail['mailengine'])) {
        // use specified email engine
        $args = func_get_args();
        $args[0]['message_id'] = $mail['message_id'];
        $args[0]['email_message_id'] = $mail['email_message_id'];

        if (preg_match('/->/', $mail['mailengine'])) {
            // e.g. 'configbeez->mymodule->myMailEngine'
            $class_method = explode('->', $mail['mailengine']);
            if (!is_object(${$class_method[1]})) {
                include_once(MH_DIR_FS_CATALOG . 'mailhive/' . $class_method[0] . '/' . $class_method[1] . '.php');
                ${$class_method[1]} = new $class_method[1]();
            }
            call_user_func(array(${$class_method[1]}, $class_method[2]), makeValuesReferenced($args));

            return array('message_id' => $mail['message_id']);
        } elseif (function_exists($mail['mailengine'])) {
            call_user_func_array($mail['mailengine'], makeValuesReferenced($args));
            return array('message_id' => $mail['message_id']);
        } else {
            unset($mail['mailengine']);
            return mh_sendEmail($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt);
        }
    } else {

        if (preg_match('/PHPMailer/', MAILBEEZ_CONFIG_EMAIL_ENGINE)) {

            require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/email_engine_php_mailer.php');

            $forwarding_to = '';
            $path_to_attachement = '';
            $path_to_more_attachements = '';

            if (MAILBEEZ_FROM_OVERRIDE == 'True') {
                $from = MAILBEEZ_FROM_ADDRESS;
                $from_name = MAILBEEZ_FROM_ADDRESS_NAME;
            }


            $reply_address = '';
            $reply_address_name = '';
            // $reply_address = (MAILBEEZ_REPLY_TO_ADDRESS == '') ? '' : MAILBEEZ_REPLY_TO_ADDRESS;
            // $reply_address_name = (MAILBEEZ_REPLY_TO_ADDRESS_NAME == '') ? '' : MAILBEEZ_REPLY_TO_ADDRESS_NAME;

            // set sender for bouncehandling
            // the email appears to be send from $from (also for reply), but bounces to to $sender
            $sender = $from;
            $sender_name = $from_name;
            if (defined('MAILBEEZ_BOUNCEHIVE_STATUS') && MAILBEEZ_BOUNCEHIVE_STATUS == 'True') {
                $sender = MAILBEEZ_SENDER_ADDRESS;
                $sender_name = MAILBEEZ_SENDER_ADDRESS_NAME;
            }

            return mh_php_mail($sender,
                $sender_name,
                $from,
                $from_name,
                $email_address,
                $mail['firstname'] . ' ' . $mail['lastname'],
                $forwarding_to,
                $reply_address,
                $reply_address_name,
                $path_to_attachement,
                $path_to_more_attachements,
                $output_subject,
                $output_content_html,
                $output_content_txt,
                $mail);
        } else {
            // default mailengine by platform
            switch (MH_PLATFORM) {
                case 'oscommerce':
                case 'creloaded':
                case 'digistore':
                    return osc_sendEmail($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt);
                    break;
                case 'mercari':
                    return mercari_sendEmail($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt);
                    break;
                case 'zencart':
                    return zencart_sendEmail($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt);
                    break;
                case 'xtc':
                case 'gambio':
                    return xtc_sendEmail($mail, $email_address, $from_name, $from, $output_subject, $output_content_html, $output_content_txt);
                    break;
                default:
                    echo 'platform not supported';
            }
        }
    }
}


//// only used in case MAILBEEZ_CONFIG_EMAIL_ENGINE is set to "Shop"


// function for osCommerce
if (!function_exists('osc_sendEmail')) {
    function osc_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
    {
        $mimemessage = new emailMailBeez(array('X-Mailer: mailbeez.com'));
        // add html and alternative text version
        $mimemessage->add_html($output_content_html, $output_content_txt);
        $mimemessage->build_message(); // encoding -> 76 character linebreak, replacements must be done before

        if (MAILBEEZ_CONFIG_EMAIL_BUGFIX_1 == 'True') {
            // try to fix that wired '..' issue
            // where e.g. file.php becomes file..php, image.png becomes image..png
            // ..or in the mimeclass (more likely)
            $mimemessage->output = preg_replace($GLOBALS['mailbeez_bugfix_1_pattern'], $GLOBALS['mailbeez_bugfix_1_replace'], $mimemessage->output);
        }
        $mimemessage->send($mail['firstname'] . ' ' . $mail['lastname'], $email_address, $sender_name, $sender, $output_subject, '');

        return array('message_id' => $mail['message_id']);
    }
}

// function for ZenCart
if (!function_exists('zencart_sendEmail')) {
    function zencart_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
    {
        if (defined('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE') && MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE == 'False') {
            $html_msg = array('EMAIL_SUBJECT' => $output_subject,
                'EMAIL_MESSAGE_HTML' => $output_content_html); // currently complete HTML mail
        } else {
            $html_msg = $output_content_html;
        }

        zen_mail($mail['firstname'] . ' ' . $mail['lastname'], $email_address, $output_subject, $output_content_txt, $sender_name, $sender, $html_msg, 'mailbeez');
        return array('message_id' => $mail['message_id']);
        // email-format is determined by look-up of email-adress in customer base
    }
}

// function for SEO:mercari
if (!function_exists('mercari_sendEmail')) {
    function mercari_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
    {
        php_mail($sender, $sender_name, $email_address, $mail['firstname'] . ' ' . $mail['lastname'], '', $sender, $sender_name, '', '', $output_subject, $output_content_html, $output_content_txt);
        return array('message_id' => $mail['message_id']);
    }
}


// function for xtCommerce
if (!function_exists('xtc_sendEmail')) {
    function xtc_sendEmail($mail, $email_address, $sender_name, $sender, $output_subject, $output_content_html, $output_content_txt)
    {
        xtc_php_mail($sender, $sender_name, $email_address, $mail['firstname'] . ' ' . $mail['lastname'], '', $sender, $sender_name, '', '', $output_subject, $output_content_html, $output_content_txt);
        return array('message_id' => $mail['message_id']);
    }
}
?>