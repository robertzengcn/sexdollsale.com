<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// make path work from admin
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez.php');

class mailbeez_phpmailer_test extends mailbeez
{

    // class constructor
    function mailbeez_phpmailer_test()
    {
        // call constructor
        mailbeez::mailbeez();

        // set some stuff:
        $this->code = 'mailbeez_phpmailer_test';
        $this->module = 'mailbeez_phpmailer_test';
        $this->version = '2.2'; // float value
        $this->required_mb_version = 2.5; // required mailbeez version
        $this->iteration = 1;
        $this->title = 'PHPMailer Test';
        $this->description = 'PHPMailer Test';
        $this->sort_order = 1100;
        $this->enabled = true;
        $this->sender = MAILBEEZ_SMTP_USERNAME; // todo: only works, when email address!
        $this->sender_name = MAILBEEZ_SMTP_USERNAME;

        $this->documentation_key = $this->module; // leave empty if no documentation available

        $this->htmlBodyTemplateResource = 'body_html.tpl'; // located in folder of this module
        $this->txtBodyTemplateResource = 'body_txt.tpl'; // located in folder of this module
        $this->subjectTemplateResource = 'subject.tpl'; // located in folder of this module

        $this->do_run = false;
        $this->is_editable = false;
        $this->is_configurable = false;
        $this->is_removable = false;
        $this->hidden = true;

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

    // installation methods

    function keys()
    {
        return false;
    }

    function install()
    {
    }


    function get_do_run()
    {
        //run only if called explicit
        if ($_REQUEST['module'] == $this->module) {
            return true;
        }
        return false;
    }


}

?>
