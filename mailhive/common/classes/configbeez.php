<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.5
 */


///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_mailer.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/googleanalytics.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/functions/compatibility.php');


class configbeez
{

    var $pathToCommonTemplates;
    var $pathToMailbeez;

// class constructor
    function configbeez()
    {
        $this->code = ''; // unique id for report-module
        $this->module = ''; //
        $this->required_mb_version = 2.0;
        $this->title = '';
        $this->description = '';
        $this->sort_order = '';
        $this->enabled = '';
        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/filterbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = ''; // list of admin frontend action plugins ("file1;file2")
        $this->common_admin_action_plugins = ''; // list of common gui plugins ("file1;file2")
        $this->icon = '../../common/images/icon_module.png';
        $this->description_image = '../../common/images/icon_module_64.png';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/configbeez/';
        $this->documentation_key = '';
        $this->hidden = false; // hide submodule / module
        $this->is_configurable = true;
        $this->removable = true; // can't be removed
    }

// class methods

    function keys()
    {
        // to be done by instance
    }

    function install()
    {
        // to be done by instance
    }

    function onSave()
    {
        // to be done by instance
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // common methods


    function check()
    {
        if (!isset($this->_check)) {
            $check_query = mh_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = '" . $this->status_key . "'");
            $this->_check = mh_db_num_rows($check_query);
        }
        return $this->_check;
    }

    function remove()
    {
        $remove_keys = $this->keys();
        if (MH_PLATFORM == 'xtc') {
            // remove additional fields
            $xtc_text_keys = array();
            $keys = $this->keys();
            while (list(, $key_name) = each($keys)) {
                $xtc_text_keys[] = $key_name . '_TITLE';
                $xtc_text_keys[] = $key_name . '_DESC';
            }
            $remove_keys = array_merge($xtc_text_keys, $remove_keys);
        }

        return mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $remove_keys) . "')");
    }

    function get_module_id()
    {
        return $this->code;
    }

    function get_installed_version($module)
    {
        $installed_modules_list = explode(';', MAILBEEZ_CONFIG_INSTALLED_VERSIONS);
        foreach ($installed_modules_list as $inst_modules) {
            list($installed_module, $installed_module_version, ,) = explode('|', $inst_modules);
            if ($installed_module == $module) {
                return str_replace(',', '.', $installed_module_version);
            }
        }
    }

    function check_update()
    {
        $installed_version = $this->get_installed_version($this->module);
        if ($installed_version < $this->version) {
            $action = (isset($_GET['action']) ? $_GET['action'] : '');
            if ($action != 'config_update_ok') {
                // avoid loop with config cache installed
                $this->update($installed_version);
            }
        }
    }

    function update($installed_version = null)
    {
        $old_version = $this->get_installed_version($this->module);

        $updated = preg_replace('/' . $this->module . '\|' . $old_version . '/', $this->module . '|' . $this->version, MAILBEEZ_CONFIG_INSTALLED_VERSIONS);

        mh_insert_config_value(array('configuration_title' => 'Installed Modules',
            'configuration_key' => 'MAILBEEZ_CONFIG_INSTALLED_VERSIONS',
            'configuration_value' => $updated,
            'configuration_description' => 'This is automatically updated. No need to edit.',
            'set_function' => ''
        ), true);

        mh_reset_config_cache();
    }

        // added mailbeez V2.803

    function mhcc($a)
    {
        return mhcc($a);
    }
}

// end of class
?>
