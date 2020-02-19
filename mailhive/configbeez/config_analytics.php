<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011, 2012 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config_analytics extends configbeez
{
// class constructor
    function config_analytics()
    {
        configbeez::configbeez();
        $this->code = 'config_analytics';
        $this->module = 'config_analytics'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_ANALYTICS_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_ANALYTICS_TEXT_DESCRIPTION;
        $this->description_image = '../../common/images/icon_ma_64.png';
        $this->icon = '../../common/images/icon_ma.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 4;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->on_cfg_save_clear_template_c = true;

        if (MAILBEEZ_ANALYTICS_STATUS == 'False') {
            $this->admin_action_plugins = 'confirm.php';
            $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/configbeez/';
        }


        $this->documentation_key = 'config_analytics'; // leave empty if no documentation available
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
        if (MAILBEEZ_ANALYTICS_STATUS == 'True') {
            $keys_array = array('MAILBEEZ_ANALYTICS_STATUS', 'MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO', 'MAILBEEZ_ANALYTICS_DO_RUN', 'MAILBEEZ_ANALYTICS_AUTOINSERT_PIX', 'MAILBEEZ_ANALYTICS_REWRITE_FORMAT', 'MAILBEEZ_ANALYTICS_SPLITPAGE_NUM', 'MAILBEEZ_ANALYTICS_BEGIN_OF_TIME');
        }
        return $keys_array;
    }

    function install()
    {
        return false;
    }

}

?>
