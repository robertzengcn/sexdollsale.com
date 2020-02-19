<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 - 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  
  V2.7.4
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config_email_engine extends configbeez
{

// class constructor
    function config_email_engine()
    {
        configbeez::configbeez();
        $this->code = 'config_email_engine';
        $this->module = 'config_email_engine'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_DESCRIPTION;
        $this->description_image = '../../common/images/icon_system_64.png';
        $this->icon = '../../common/images/icon_system.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 1.3;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->on_cfg_save_clear_template_c = true;

        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = (preg_match('/PHPMailer/', MAILBEEZ_CONFIG_EMAIL_ENGINE)) ? 'test.php' : '';

        $this->documentation_key = 'config_email_engine'; // leave empty if no documentation available
        $this->documentation_root = 'http://www.mailbeez.com/documentation/installation/config/';
    }

// class methods

    function getAudience()
    {
        return false;
    }

    function check()
    {
        return true;
    }

    function remove()
    {
        return false;
    }

    // installation methods

    function keys()
    {

        $keys_array = array('MAILBEEZ_CONFIG_EMAIL_ENGINE','MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT', 'MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING', 'MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP',  /* 'MAILBEEZ_EMAIL_TRANSPORT', */     'MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE', 'MAILBEEZ_SMTP_USERNAME', 'MAILBEEZ_SMTP_PASSWORD', 'MAILBEEZ_SMTP_MAIN_SERVER', 'MAILBEEZ_SMTP_BACKUP_SERVER', 'MAILBEEZ_SMTP_AUTH', 'MAILBEEZ_SMTP_SECURE', 'MAILBEEZ_SMTP_PORT','MAILBEEZ_CONFIG_EMAIL_BUGFIX_1');

        if (MH_PLATFORM == 'zencart') {
            $keys_array = array_merge(array('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE'), $keys_array);
        }

        $keys_array = array_merge($keys_array, array('MAILBEEZ_EMAIL_USE_TXT_ONLY' /*, 'MAILBEEZ_SENDMAIL_PATH' */));

        if (!version_compare(PHP_VERSION, '5.0.0', '<')) {
            // requires PHP 5
            $keys_array = array_merge($keys_array, array('MAILBEEZ_DKIM_SELECTOR', 'MAILBEEZ_DKIM_IDENTIY', 'MAILBEEZ_DKIM_PASSPHRASE', 'MAILBEEZ_DKIM_DOMAIN', 'MAILBEEZ_DKIM_PRIVATE'));
        }


        return $keys_array;
    }

    function install()
    {
        return false;
    }

}

?>