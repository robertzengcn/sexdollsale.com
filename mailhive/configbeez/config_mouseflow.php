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

// make path work from admin
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config_mouseflow extends configbeez
{
    // class constructor
    function config_mouseflow()
    {
        // call constructor
        configbeez::configbeez();

        // set some stuff:
        $this->code = 'config_mouseflow';
        $this->module = 'config_mouseflow'; // same as folder name
        $this->version = '2.1'; // float value
        $this->required_mb_version = 2.5; // required mailbeez version
        $this->title = MAILBEEZ_MOUSEFLOW_TEXT_TITLE;
        $this->description = MAILBEEZ_MOUSEFLOW_TEXT_DESCRIPTION;
        $this->description_image = 'icon_64.png';
        $this->icon = 'icon.png';
        $this->sort_order = 50;
        $this->enabled = ((MAILBEEZ_MOUSEFLOW_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_MOUSEFLOW_STATUS';
        $this->common_admin_action_plugins = '';

        $this->documentation_key = $this->module; // leave empty if no documentation available
        // $this->documentation_root = 'http:://yoursite.com/' // modify documentation root if necessary

        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
    }

// class methods

    // installation methods

    function keys()
    {
        if (isset($this->keys)) return $this->keys;
        return array('MAILBEEZ_MOUSEFLOW_STATUS', 'MAILBEEZ_MOUSEFLOW_CONFIG', 'MAILBEEZ_MOUSEFLOW_IP_EXCLUDE', 'MAILBEEZ_MOUSEFLOW_CODE');
    }


    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Activate Mouseflow integration?',
                                    'configuration_key' => 'MAILBEEZ_MOUSEFLOW_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to activate the Mouseflow Integration?',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));


        mh_insert_config_value(array('configuration_title' => 'Mouseflow Recording is active for',
                                    'configuration_key' => 'MAILBEEZ_MOUSEFLOW_CONFIG',
                                    'configuration_value' => 'MailBeez',
                                    'configuration_description' => 'choose the channel you would like to record for',
                                    'set_function' => 'mh_cfg_select_multioption(array(\'MailBeez\', \'Other\'), '
                               ));


        mh_insert_config_value(array('configuration_title' => 'Your Mouseflow Code',
                                    'configuration_key' => 'MAILBEEZ_MOUSEFLOW_CODE',
                                    'configuration_value' => 'Copy your Mouseflow Code',
                                    'configuration_description' => 'The complete Mouseflow code',
                                    'set_function' => 'configbeez->' . $this->module . '->cfg_code_box',
                                    'use_function' => 'configbeez->' . $this->module . '->show_code_info'
                               ));

        mh_insert_config_value(array('configuration_title' => 'Exclude IPs',
                                    'configuration_key' => 'MAILBEEZ_MOUSEFLOW_IP_EXCLUDE',
                                    'configuration_value' => '127.0.0.1' . "\n" . '192.168.0.1',
                                    'configuration_description' => 'exclude these IPs',
                                    'set_function' => 'configbeez->' . $this->module . '->cfg_ip_box',
                                    'use_function' => 'configbeez->' . $this->module . '->show_ip_info'
                               ));


        mh_insert_config_value(array('configuration_title' => 'mouseflow url identifier',
                                    'configuration_key' => 'MAILBEEZ_MOUSEFLOW_MAILBEEZ_URL_ID',
                                    'configuration_value' => 'mfxti',
                                    'configuration_description' => 'no need to change this',
                                    'set_function' => ''
                               ));

    }


    function remove()
    {
        $default_keys = $this->keys();
        $this->keys = array_merge($default_keys, array('MAILBEEZ_MOUSEFLOW_MAILBEEZ_URL_ID'));
        configbeez::remove();
    }

    function cfg_code_box($params)
    {
        $box_string = $params[0];
        $key = $params[1];
        return mh_draw_textarea_field('configuration[' . $key . ']', 'off', '40', '10', $box_string);
    }

    function show_code_info($params)
    {
        return '<i>JS CODE</i>';
    }

    function cfg_ip_box($params)
    {
        $box_string = $params[0];
        $key = $params[1];
        $ipbox = mh_draw_textarea_field('configuration[' . $key . ']', 'off', '40', '10', $box_string);
        $ipbox .= '<br />IP: ' . $_SERVER['REMOTE_ADDR'];
        return $ipbox;
    }

    function show_ip_info($params)
    {
        return nl2br($params);
    }


}

?>