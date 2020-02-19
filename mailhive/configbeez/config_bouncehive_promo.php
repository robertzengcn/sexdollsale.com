<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

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


class config_bouncehive_promo extends configbeez
{
// class constructor
    function config_bouncehive_promo()
    {
        configbeez::configbeez();
        $this->code = 'config_bouncehive_promo';
        $this->module = 'config_bouncehive_promo'; // same as folder name
        $this->version = '2.0'; // float value
        $this->title = MAILBEEZ_CONFIG_BOUNCEHIVE_PROMO_TEXT_TITLE;
        $this->description = MAILBEEZ_CONFIG_BOUNCEHIVE_PROMO_TEXT_DESCRIPTION;

        if (defined('MAILBEEZ_BOUNCEHIVE_STATUS') && MAILBEEZ_BOUNCEHIVE_STATUS == 'True') {
            $this->hidden = true;
        }
        else {
            $this->icon = '../../common/images/lock.png';
        }

        $this->removable = false; // can't be removed
        $this->stealth = true; // don't list as an installed module
        $this->display_as_submodule_of = 'config';
        $this->sort_order = 5.9;
        //$this->enabled = false;
        $this->is_configurable = false;

        $this->documentation_key = 'config_bouncehive_advanced'; // leave empty if no documentation available
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
        return false;
    }

    function install()
    {
        return false;
    }

}

?>
