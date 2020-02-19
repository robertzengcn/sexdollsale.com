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

class config_spamcompliance extends configbeez
{
// class constructor
    function config_spamcompliance()
    {
        configbeez::configbeez();
        $this->code = 'config_spamcompliance';
        $this->module = 'config_spamcompliance'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_SPAMCOMPLIANCE_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_SPAMCOMPLIANCE_TEXT_DESCRIPTION;
        $this->description_image = 'icon_64.png';
        $this->icon = 'icon.png';
        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 0.5;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->on_cfg_save_clear_template_c = true;

        $this->hidden = (defined('MAILBEEZ_CONFIG_SPAMCOMPLIANCE_ADVANCED_STATUS') && MAILBEEZ_CONFIG_SPAMCOMPLIANCE_ADVANCED_STATUS == 'True') ? true : false;
        $this->documentation_key = 'config_spamcompliance'; // leave empty if no documentation available
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
        $keys_array = array('MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION', 'MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR');
        return $keys_array;
    }

    function install()
    {
        return false;
    }

}

?>
