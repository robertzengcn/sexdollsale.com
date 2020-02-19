<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// make path work from admin


require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/reportbeez.php');

class report_ga extends reportbeez
{

    // class constructor
    function report_ga()
    {
        // call constructor
        reportbeez::reportbeez();
        $this->code = 'report_ga';
        $this->module = 'report_ga';
        $this->version = '2.2'; // float value
        $this->required_mb_version = 2.6;
        $this->title = MAILBEEZ_REPORT_GA_TEXT_TITLE;
        $this->description = MAILBEEZ_REPORT_GA_TEXT_DESCRIPTION;
        $this->description_image = '../../common/images/icon_ga_64.png';
        $this->icon = '../../common/images/icon_ga.png';
        $this->sort_order = MAILBEEZ_REPORT_GA_SORT_ORDER;
        $this->enabled = ((MAILBEEZ_REPORT_GA_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_REPORT_GA_STATUS';
        $this->documentation_key = $this->module; // leave empty if no documentation available
        $this->admin_action_plugins = 'run_button.php';
    }

    function runReport()
    {
        return true;
    }

    function outputReport()
    {
        return 'report ga!';
    }


    function timestamp($offset_day)
    {
        $rawtime = strtotime(-1 * (int)$offset_day . " days");
        return date("Y-m-d", $rawtime);
    }


    function keys()
    {
        return array('MAILBEEZ_REPORT_GA_STATUS', 'MAILBEEZ_REPORT_GA_SORT_ORDER', 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN', 'MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Google Analytics Report',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to activate this report?',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_SORT_ORDER',
            'configuration_value' => '110',
            'configuration_description' => 'Sort order of display. Lowest is displayed first.',
            'set_function' => ''
        ));


        mh_insert_config_value(array('configuration_title' => 'GA Token',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN',
            'configuration_value' => '',
            'configuration_description' => 'This token gives access to GA',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Profile ID',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID',
            'configuration_value' => 'your Google Analytics Profile ID',
            'configuration_description' => 'This profile ID will only be stored in your Shop Database (not encrypted)',
            'set_function' => ''
        ));
    }
}
?>
