<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6
 */

///////////////////////////////////////////////////////////////////////////////
///																		     //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez.php');

class service_handler_analytics extends mailbeez
{
    // class constructor
    function service_handler_analytics()
    {
        // call constructor
        mailbeez::mailbeez();

        // set some stuff:
        $this->code = 'service_handler_analytics';
        $this->module = 'service_handler_analytics';
        $this->version = '2.0'; // float value
        $this->required_mb_version = 2.6; // required mailbeez version
        $this->iteration = 1;
        $this->title = MAILBEEZ_ANALYTICS_HNDL_TEXT_TITLE;
        $this->description = MAILBEEZ_ANALYTICS_HNDL_TEXT_DESCRIPTION;
        $this->sort_order = 15000;
        $this->enabled = true;
        $this->documentation_key = $this->module; // leave empty if no documentation available
        $this->admin_action_plugins = 'process.php';
        $this->common_admin_action_plugins = '';
        $this->do_process = (MAILBEEZ_ANALYTICS_DO_RUN == 'True'); // a processable module
        $this->do_run = (MAILBEEZ_ANALYTICS_DO_RUN == 'True');
        $this->is_editable = false; // allow editor
        $this->is_configurable = false;
        $this->removable = false;
        $this->hidden = ((MAILBEEZ_CONFIG_ADMIN_HIDE_HELPERS == 'True' || MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO == 'True') ? true : false);
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

    function install()
    {
        return false;
    }

    function process()
    {
        $this->external_tracking_process();
        return 0;
    }

    function external_tracking_process()
    {
        require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/clicktracker.php');
        echo '<h2>' . MAILBEEZ_ANALYTICS_HNDL_TEXT_TITLE . '</h2>';
        $mailbeez_clicktracker = new mb_clicktracker();
        $mailbeez_clicktracker->import_log_file();
        $mailbeez_clicktracker->consolidate_coupon_data();
    }
}

?>