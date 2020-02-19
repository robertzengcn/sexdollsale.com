<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////
// make path work from admin
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/configbeez.php');

class config_queen extends configbeez
{

// class constructor
    function config_queen()
    {
        configbeez::configbeez();
        $this->code = 'config_queen';
        $this->module = 'config_queen';
        $this->version = 2.92; // float value
        $this->version_display = '2.9.2'; // string
        $this->title = MAILBEEZ_MAILHIVE_TEXT_TITLE;
        $this->description = MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION;
        $this->description_image = 'icon_big.png';
        $this->icon = 'icon.png';
        $this->sort_order = 0;
        $this->enabled = ((MAILBEEZ_MAILHIVE_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_MAILHIVE_STATUS';
        $this->has_submodules = true;
        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/configbeez/'; // default-path to include admin action plugins from
        //$this->admin_action_plugins = 'uninstall.php';

        $this->documentation_key = 'config'; // leave empty if no documentation available
        $this->documentation_root = 'http://www.mailbeez.com/documentation/installation/';

        // update version if necessary
        if (defined('MAILBEEZ_VERSION') && (str_replace(',', '.', MAILBEEZ_VERSION) < $this->version)) {
            $action = (isset($_GET['action']) ? $_GET['action'] : '');
            if ($action != 'config_update_ok') {
                // avoid loop with config cache installed
                $this->update($this->version);
            }
        }
    }

// class methods

    function onSave()
    {
        // reset system check in case the setting was changed
        mh_system_check_refresh();
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
            $remove_keys = array_merge($xtc_text_keys, $this->keys());
        }

        $remove_keys[] = 'MAILBEEZ_VERSION';
        $remove_keys[] = 'MAILBEEZ_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_CONFIG_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_CONFIG_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_FILTER_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_FILTER_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_REPORT_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_REPORT_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_DASHBOARD_INSTALLED';
        $remove_keys[] = 'MAILBEEZ_DASHBOARD_INSTALLED_VERSIONS';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_ENABLED';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_REWRITE_MODE';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_REWRITE_FORMAT';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_MEDIUM';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_GA_SOURCE';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TIMESTAMP';

        $remove_keys[] = 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_STATUS';
        $remove_keys[] = 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE';
        $remove_keys[] = 'MAILBEEZ_CONFIG_DASHBOARD_START';
        $remove_keys[] = 'MAILBEEZ_CONFIG_SIMULATION_EMAIL';
        $remove_keys[] = 'MAILBEEZ_CONFIG_SIMULATION_TRACKING';
        $remove_keys[] = 'MAILBEEZ_MAILHIVE_MODE';
        $remove_keys[] = 'MAILBEEZ_CONFIG_SIMULATION_COPY';

        mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $remove_keys) . "')");
        return mh_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MAILBEEZ_%'");
    }

    // installation methods

    function keys()
    {
        return array('MAILBEEZ_MAILHIVE_STATUS', 'MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL', 'MAILBEEZ_MAILHIVE_COPY', 'MAILBEEZ_MAILHIVE_EMAIL_COPY', 'MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT', 'MAILBEEZ_INSTALLED', 'MAILBEEZ_INSTALLED_VERSIONS', 'MAILBEEZ_MAILHIVE_TOKEN', 'MAILBEEZ_MAILHIVE_POPUP_MODE', 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER', 'MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED', 'MH_SYSTEMCHECK_DB_DISABLED');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Let the MailBeez work for you',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Choose False to deactivated MailHive and MailBeez',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Mode',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_MODE',
            'configuration_value' => 'simulate',
            'configuration_description' => 'production: emails are send out, tracking active<br>simulate: emails to copy-address only, tracking configurable',
            'set_function' => 'mh_cfg_select_option(array(\'production\', \'simulate\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Send copy',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_COPY',
            'configuration_value' => 'True',
            'configuration_description' => 'send a copy of each email to copy-address',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Sent copy to',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_EMAIL_COPY',
            'configuration_value' => 'copy@localhost',
            'configuration_description' => 'Send a copy of each email to this address<br>(be careful - configure number below)',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Max. number of copy-emails sent per MailBeez Module',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT',
            'configuration_value' => '10',
            'configuration_description' => 'controll the number of copy-emails',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Security Token - for internal use only',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_TOKEN',
            'configuration_value' => md5(time()),
            'configuration_description' => 'Security Token to protect public mailhive, leave default value or set to what you like',
            'set_function' => ''
        ));


        mh_insert_config_value(array('configuration_title' => 'Popup mode',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_POPUP_MODE',
            'configuration_value' => 'CeeBox',
            'configuration_description' => 'Popup-Mode, please change if you are having compatibility issues with opening the nice CeeBox AJAX Popups.',
            'set_function' => 'mh_cfg_select_option(array(\'off\', \'CeeBox\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Remind to run update check',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_UPDATE_REMINDER',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to get reminder to check for updates?',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'Google Analytics Integration',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_ENABLED',
            'configuration_value' => 'True',
            'configuration_description' => 'Globally enable Google Analytics Integration',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics URL Rewrite Mode',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_REWRITE_MODE',
            'configuration_value' => 'all',
            'configuration_description' => 'Globally set Google Analytics URL Rewrite Mode',
            'set_function' => 'mh_cfg_select_option(array(\'all\', \'shop\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'MailBeez Version',
            'configuration_key' => 'MAILBEEZ_VERSION',
            'configuration_value' => $this->version,
            'configuration_description' => 'This is automatically updated. No need to edit.',
            'set_function' => ''
        ));


        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_tracking (
			    autoemail_id int NOT NULL auto_increment,
			    module varchar(255) NOT NULL,
				iteration INT( 11 ) NOT NULL ,
				customers_id INT( 11 ) NOT NULL ,		
				customers_email VARCHAR( 96 ) NOT NULL,
				orders_id INT NOT NULL,	
				date_sent DATETIME NOT NULL ,
				PRIMARY KEY ( autoemail_id ),
				INDEX ( customers_id, iteration, module ) );");

        $this->update($this->version);
    }

    function update($installed_version)
    {
        // MailBeez V1.5
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_block (
			  autoemail_id int NOT NULL auto_increment,
			  module varchar(255) NOT NULL,
				customers_id INT( 11 ) NOT NULL ,	
				date_block DATETIME NOT NULL,
				PRIMARY KEY ( autoemail_id ),
				INDEX ( customers_id, module ) );");


        // MailBeez V2
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_event_log (
			  log_id int NOT NULL auto_increment,
				event_type varchar(255) NOT NULL,
				log_entry text,
				batch_id INT( 11 ) NOT NULL ,
				module varchar(255) NOT NULL,
				class varchar(255) NOT NULL,
				result varchar(255) NOT NULL,
				parameters text,
                log_date DATETIME NOT NULL,
				query_string text,
				simulation INT( 11 ) NOT NULL,
				PRIMARY KEY ( log_id ),
				INDEX ( module ) );");


        // MailBeez V2.5
        // introducing: process locking
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_process (
                        lock_id int(11) NOT NULL auto_increment,
                        lock_key varchar(255) default NULL,
                        lock_value text default NULL,
                        batch_id INT( 11 ) NOT NULL ,
                        date_added datetime NOT NULL default '0000-00-00 00:00:00',
                        PRIMARY KEY  (lock_id),
                        INDEX ( lock_key ) );"
        );


        // MailBeez V2.6
        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_bounces (
			    id int NOT NULL auto_increment,
			    autoemail_id int NOT NULL,
				customers_email VARCHAR( 96 ) NOT NULL,
				customers_id INT( 11 ) NOT NULL ,
				module varchar(255) NOT NULL,
				date_bounce DATETIME NOT NULL,
				bounce_type varchar(1),
				PRIMARY KEY ( id ),
				KEY autoemail_id (autoemail_id),
				KEY customers_id (customers_id),
				KEY customers_email (customers_email) );");

        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_bounces_msg_log (
			    id int NOT NULL auto_increment,
			    autoemail_id int NOT NULL,
				bounce_msg text,
				PRIMARY KEY ( id ),
				KEY autoemail_id (autoemail_id) );");

        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_opens_log (
			    id int NOT NULL auto_increment,
			    message_id varchar( 255 ) NOT NULL,
			    date DATETIME NOT NULL,
			    user_agent varchar( 255 ) NOT NULL,
				PRIMARY KEY ( id ),
				KEY message_id (message_id) );");

        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_tracking_clicks (
			    id int NOT NULL auto_increment,
                message_id varchar( 255 ) NOT NULL,
                date_record DATETIME NOT NULL,
				PRIMARY KEY ( id ),
				KEY message_id (message_id) );");


        mh_db_query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mailbeez_tracking_orders (
			    id int NOT NULL auto_increment,
                message_id varchar( 255 ) NOT NULL,
                orders_id INT( 11 ) NOT NULL,
                customers_id INT( 11 ) NOT NULL,
                date_record DATETIME NOT NULL,
				PRIMARY KEY ( id ),
				KEY message_id (message_id) );");


        mh_insert_config_value(array('configuration_title' => 'Google Analytics Medium',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_MEDIUM',
            'configuration_value' => 'email',
            'configuration_description' => 'Choose how you would like to name the medium (default: email)',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Source',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_SOURCE',
            'configuration_value' => 'MailBeez',
            'configuration_description' => 'Choose how you would like to name the source (default: MailBeez)',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Compatibility Mode',
            'configuration_key' => 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE',
            'configuration_value' => 'True',
            'configuration_description' => 'Choose True for compatibility with the MailBeez 1.x Template System.',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Startpage',
            'configuration_key' => 'MAILBEEZ_CONFIG_DASHBOARD_START',
            'configuration_value' => 'home',
            'configuration_description' => 'Choose which tab you would like to see when you open MailBeez',
            'set_function' => 'mh_cfg_select_option(array(\'home\', \'mailbeez\', \'config\', \'filter\', \'report\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Sent simulation to',
            'configuration_key' => 'MAILBEEZ_CONFIG_SIMULATION_EMAIL',
            'configuration_value' => 'copy@localhost',
            'configuration_description' => 'Email Adress to send simulation emails to - no limitations',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Send copy in Simulation mode',
            'configuration_key' => 'MAILBEEZ_CONFIG_SIMULATION_COPY',
            'configuration_value' => 'True',
            'configuration_description' => 'send a copy of each email to the configured copy-address',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Enable Tracking in Simulation Mode',
            'configuration_key' => 'MAILBEEZ_CONFIG_SIMULATION_TRACKING',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to enable Tracking in Simulation mode? You can delete the Simulation Tracking with click on "Restart Simulation"',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'Enable Early Check',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED',
            'configuration_value' => 'False',
            'configuration_description' => 'Do you want to enable "Early Check"? This will hide all already sent or filtered results - but might confuse by showing "0 recipients".<br />
"Early Check" adds a SQL query per item per module (slower)',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Optional',
            'configuration_key' => 'MAILBEEZ_CONFIG_EVENT_LOG_LEVEL',
            'configuration_value' => '',
            'configuration_description' => 'Settings',
            'set_function' => 'mh_cfg_select_multioption(array(\'MODULE_INIT\', \'MODULE_SQL\'), '
        ));


        if (MH_PLATFORM != 'xtc' && MH_PLATFORM != 'gambio' && MH_PLATFORM != 'mercari') {
            // only for platforms without smarty by default
            mh_insert_config_value(array('configuration_title' => 'Path to Smarty',
                'configuration_key' => 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH',
                'configuration_value' => 'Smarty_2.6.26',
                'configuration_description' => 'Path to Smarty Template system<br>located in <br />
																		 mailhive/common/classes/',
                'set_function' => ''
            ));
        }

        // convert field type
        $field_info = mh_db_check_field_exists(TABLE_MAILBEEZ_TRACKING, 'customers_id');
        if ($field_info['Type'] == 'int(11)') {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " CHANGE customers_id customers_id BIGINT( 20 ) NOT NULL DEFAULT '0'");
        }
        $field_info = mh_db_check_field_exists(TABLE_MAILBEEZ_BLOCK, 'customers_id');
        if ($field_info['Type'] == 'int(11)') {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " CHANGE customers_id customers_id BIGINT( 20 ) NOT NULL DEFAULT '0'");
        }

        // advanced simulation
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD simulation INT( 11 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( simulation ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'simulation', $sql);

        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " ADD simulation INT( 11 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " ADD INDEX ( simulation ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_BLOCK, 'simulation', $sql);


        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_BLOCK . " ADD source INT( 11 ) default 0;";
        mh_db_add_field(TABLE_MAILBEEZ_BLOCK, 'source', $sql);


        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD channel varchar( 10 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( channel ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'channel', $sql);

        // event log
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD batch_id INT( 11 ) NOT NULL ;";
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( batch_id ) ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'batch_id', $sql);

        // message tracking
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD message_id varchar( 255 ) NOT NULL ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'message_id', $sql);
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD opened DATETIME ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'opened', $sql);
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD clicked DATETIME ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'clicked', $sql);
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD ordered DATETIME ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'ordered', $sql);


        // click tracking
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING_CLICKS . " ADD url text ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING_CLICKS, 'url', $sql);

        // bounce handling
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD bounce_status varchar( 1 ) NOT NULL ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'bounce_status', $sql);


        // install dashboard default modules, also update
        $default_dashboardbeez = array('dashboard_substat', 'dashboard_intro', 'dashboard_latest_news', 'dashboard_actions', 'dashboard_versioncheck', 'dashboard_review_o_meter', 'dashboard_winback_o_meter', 'dashboard_nopurchase_o_meter', 'dashboard_beez_o_graph', 'dashboard_ga', 'dashboard_loyalty_o_graph');

        if (defined('MAILBEEZ_DASHBOARD_INSTALLED') && MAILBEEZ_DASHBOARD_INSTALLED != '') {
            $installed_modules_str = str_replace(MH_FILE_EXTENSION, '', MAILBEEZ_DASHBOARD_INSTALLED);
            $installed_modules = explode(';', $installed_modules_str);
            $default_dashboardbeez_all = array_merge($installed_modules, $default_dashboardbeez);

        } else {
            $default_dashboardbeez_all = $default_dashboardbeez;
        }

        $install_modules = array();
        $cnt = 0;
        while (list(, $class) = each($default_dashboardbeez_all)) {
            if (array_search($class . MH_FILE_EXTENSION, $install_modules) === false) {
                if (file_exists(MH_DIR_DASHBOARD . $class . MH_FILE_EXTENSION)) {
                    $cnt++;
                    include_once(MH_DIR_DASHBOARD . $class . MH_FILE_EXTENSION);
                    if (!class_exists($class)) {
                        continue;
                    } else {
                        $module = new $class;
                        $module->install();
                        $install_modules['' . ($module->sort_order + 0.001 * $cnt)] = $class . MH_FILE_EXTENSION;
                    }
                }
            }
        }
        ksort($install_modules);

        mh_insert_config_value(array('configuration_title' => 'Installed Modules',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_INSTALLED',
            'configuration_value' => implode(';', $install_modules),
            'configuration_description' => 'This is automatically updated. No need to edit.',
            'set_function' => ''
        ), true);


        // install default reports, also update
        $default_reportbeez = array('report_event_log');
        $installed_modules = array();
        while (list(, $class) = each($default_reportbeez)) {
            if (file_exists(MH_DIR_REPORT . $class . MH_FILE_EXTENSION)) {
                include_once(MH_DIR_REPORT . $class . MH_FILE_EXTENSION);
                $module = new $class;
                $module->install();
                $installed_modules[] = $class . MH_FILE_EXTENSION;
            }
        }

        mh_insert_config_value(array('configuration_title' => 'Installed Modules',
            'configuration_key' => 'MAILBEEZ_REPORT_INSTALLED',
            'configuration_value' => implode(';', $installed_modules),
            'configuration_description' => 'This is automatically updated. No need to edit.',
            'set_function' => ''
        ), true);

        /*
        if (MH_PLATFORM == 'xtc' || MH_PLATFORM == 'gambio') {
            $query_raw = "select * from " . TABLE_CONFIGURATION . " where configuration_key like 'MAILBEEZ_%_TITLE' or configuration_key like 'MAILBEEZ_%_DESC'";
            $query = mh_db_query($query_raw);
            while ($item = mh_db_fetch_array($query)) {
                $data = array('configuration_key' => $item['configuration_key'] . '_DEPR');
                mh_db_perform(TABLE_CONFIGURATION, $data, 'update', "configuration_key='" . $item['configuration_key'] . "'");
            }
        }
        */

        // update fieldtype of configuration_value to text like in zencart and oscommerce 2.3
        $field_info = mh_db_check_field_exists(TABLE_CONFIGURATION, 'configuration_value');
        if ($field_info['Type'] != 'text') {
            mh_db_query("ALTER TABLE " . TABLE_CONFIGURATION . " CHANGE configuration_value configuration_value text NOT NULL");
        }

        // update fieldtype of set_function to text
        $field_info = mh_db_check_field_exists(TABLE_CONFIGURATION, 'set_function');
        if ($field_info['Type'] != 'text') {
            mh_db_query("ALTER TABLE " . TABLE_CONFIGURATION . " CHANGE set_function set_function text NOT NULL");
        }

        // make platform info available for mailhive.php
        mh_insert_config_value(array('configuration_title' => 'Detected Platform',
            'configuration_key' => 'MH_PLATFORM_STATIC',
            'configuration_value' => MH_PLATFORM,
            'configuration_description' => 'This is automatically updated. No need to edit.',
            'set_function' => ''
        ));

        if (MH_ID != MH_PLATFORM) {
            mh_insert_config_value(array('configuration_title' => 'Platform ID',
                'configuration_key' => 'MH_ID',
                'configuration_value' => MH_ID,
                'configuration_description' => 'This is automatically updated. No need to edit.',
                'set_function' => ''
            ));
        }


        mh_insert_config_value(array('configuration_title' => 'Activate Process Control',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL',
            'configuration_value' => 'True',
            'configuration_description' => 'Choose False to deactivated Process Control',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Lock-Period',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD',
            'configuration_value' => '600',
            'configuration_description' => 'Lock-Period (sec)',
            'set_function' => ''
        ));


        mh_insert_config_value(array('configuration_title' => 'Show Emails when Sending',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL',
            'configuration_value' => 'True',
            'configuration_description' => 'Define if you would like to see the generated emails while sending',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Double Dot Bugfix',
            'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_BUGFIX_1',
            'configuration_value' => 'False',
            'configuration_description' => 'In rare occasions a Dot in filenames is doubled, e.g. file.php becomes file..php, image.png becomes image..png. Try to fix this Bug?',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        if (MH_PLATFORM == 'zencart') {
            mh_insert_config_value(array('configuration_title' => 'Override Zencart Email Template System',
                'configuration_key' => 'MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE',
                'configuration_value' => 'True',
                'configuration_description' => 'Do you want to override Zencarts Email Template System?',
                'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
            ));
        }


        // MailBeez V2.6

        mh_insert_config_value(array('configuration_title' => 'Opt-Out link behaviour',
            'configuration_key' => 'MAILBEEZ_CONFIG_OPTOUT_BEHAVIOUR',
            'configuration_value' => 'Module',
            'configuration_description' => 'Choose how the opt-out link should behave. A click blocks ',
            'set_function' => 'mh_cfg_select_option(array(\'Module\', \'Global\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'Newsletter Subscription Check',
            'configuration_key' => 'MAILBEEZ_CONFIG_CHECK_SUBSCRIPTION',
            'configuration_value' => 'False',
            'configuration_description' => 'Activate Newsletter Check for all Modules?',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        // MailBeez V2.6
        if (defined('WPOLS_PLUGINS_DIR')) {
            mh_insert_config_value(array('configuration_title' => 'Wordpress Page ID of WP Online Store',
                'configuration_key' => 'MAILBEEZ_MAILHIVE_WPOLS_PAGE_ID',
                'configuration_value' => '',
                'configuration_description' => 'Please enter the page ID of the page where you inserted [WP_online_store]',
                'set_function' => ''
            ));
        }

        // MailBeez V2.6


        if (version_compare(PHP_VERSION, '5.0.0', '<')) {
            mh_insert_config_value(array('configuration_title' => 'Select the Email Engine',
                'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_ENGINE',
                'configuration_value' => 'Shop',
                'configuration_description' => 'Select which Email Engine to use.',
                'set_function' => 'mh_cfg_select_option(array(\'PHPMailer 2.0.4\', \'Shop\'), '
            ));
        } else {
            mh_insert_config_value(array('configuration_title' => 'Select the Email Engine',
                'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_ENGINE',
                'configuration_value' => 'Shop',
                'configuration_description' => 'Select which Email Engine to use.',
                'set_function' => 'mh_cfg_select_option(array(\'PHPMailer 5.2.1\', \'Shop\'), '
            ));
        }


        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Select Transport Method',
            'configuration_key' => 'MAILBEEZ_EMAIL_TRANSPORT',
            'configuration_value' => 'smtp',
            'configuration_description' => 'Select Transport Method - only for PHPMailer',
            'set_function' => 'mh_cfg_select_option(array(\'smtp\', \'sendmail\', \'mail\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Sendmail Path',
            'configuration_key' => 'MAILBEEZ_SENDMAIL_PATH',
            'configuration_value' => '/usr/sbin/sendmail',
            'configuration_description' => 'Sendmail Path - only for PHPMailer',
            'set_function' => ''
        ));

        // SMTP

        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Smtp Authentication',
            'configuration_key' => 'MAILBEEZ_SMTP_AUTH',
            'configuration_value' => 'False',
            'configuration_description' => 'Use Smtp Authentication',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Smtp UserName',
            'configuration_key' => 'MAILBEEZ_SMTP_USERNAME',
            'configuration_value' => '',
            'configuration_description' => 'Smtp User name',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Smtp Password',
            'configuration_key' => 'MAILBEEZ_SMTP_PASSWORD',
            'configuration_value' => '',
            'configuration_description' => 'Smtp password',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Smtp Server',
            'configuration_key' => 'MAILBEEZ_SMTP_MAIN_SERVER',
            'configuration_value' => '',
            'configuration_description' => 'Smtp Server',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'PHPMailer: Smtp Backup Server',
            'configuration_key' => 'MAILBEEZ_SMTP_BACKUP_SERVER',
            'configuration_value' => '',
            'configuration_description' => 'Smtp Backup Server',
            'set_function' => ''
        ));

        mh_insert_config_value(array('PHPMailer: Smtp Security',
            'configuration_key' => 'MAILBEEZ_SMTP_SECURE',
            'configuration_value' => 'none',
            'configuration_description' => 'Select the smtp security',
            'set_function' => 'mh_cfg_select_option(array(\'none\',\'ssl\', \'tls\'), '
        ));

        mh_insert_config_value(array('PHPMailer: Smtp port',
            'configuration_key' => 'MAILBEEZ_SMTP_PORT',
            'configuration_value' => '25',
            'configuration_description' => 'Select the smtp port',
            'set_function' => ''
        ));

        if (!version_compare(PHP_VERSION, '5.0.0', '<')) {
            mh_insert_config_value(array('configuration_title' => 'PHPMailer 5.2.x: DKIM Selector',
                'configuration_key' => 'MAILBEEZ_DKIM_SELECTOR',
                'configuration_value' => '',
                'configuration_description' => 'Used with DKIM DNS Resource Record',
                'set_function' => ''
            ));

            mh_insert_config_value(array('configuration_title' => 'PHPMailer 5.2.x: DKIM Identity',
                'configuration_key' => 'MAILBEEZ_DKIM_IDENTIY',
                'configuration_value' => '',
                'configuration_description' => 'Used with DKIM DNS Resource Record
                                             <br />optional, in format of email address "you@yourdomain.com"',
                'set_function' => ''
            ));

            mh_insert_config_value(array('configuration_title' => 'PHPMailer 5.2.x: DKIM Passphrase',
                'configuration_key' => 'MAILBEEZ_DKIM_PASSPHRASE',
                'configuration_value' => '',
                'configuration_description' => 'Used with DKIM DNS Resource Record',
                'set_function' => ''
            ));

            mh_insert_config_value(array('configuration_title' => 'PHPMailer 5.2.x: DKIM Domain',
                'configuration_key' => 'MAILBEEZ_DKIM_DOMAIN',
                'configuration_value' => '',
                'configuration_description' => 'Used with DKIM DNS Resource Record
                                         <br />optional, in format of email address "you@yourdomain.com"',
                'set_function' => ''
            ));

            mh_insert_config_value(array('configuration_title' => 'PHPMailer 5.2.x: DKIM Private',
                'configuration_key' => 'MAILBEEZ_DKIM_PRIVATE',
                'configuration_value' => '',
                'configuration_description' => 'Used with DKIM DNS Resource Record
                                         <br />optional, in format of email address "you@yourdomain.com"',
                'set_function' => ''
            ));
        }

        mh_insert_config_value(array('PHPMailer: TXT format only',
            'configuration_key' => 'MAILBEEZ_EMAIL_USE_TXT_ONLY',
            'configuration_value' => 'False',
            'configuration_description' => 'send emails only in txt',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        // MailBeez Analytics


        mh_insert_config_value(array('configuration_title' => 'Enable MailBeez Analytics',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_STATUS',
            'configuration_value' => 'False',
            'configuration_description' => 'Do you want to enable MailBeez Analytics for Tracking of e.g. open rates, click rates',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Process MailBeez Analytics Information with every run of MailHive',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_DO_RUN',
            'configuration_value' => 'True',
            'configuration_description' => 'If set to False you need to set up a dedicated cronjob or run the Module "Service Handler for MailBeez Analytics" manually',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Insert Tracking Pix automatically',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_AUTOINSERT_PIX',
            'configuration_value' => 'True',
            'configuration_description' => 'If set to False you need to insert the Tracking Tag into your Main Template: <b>{$MAILBEEZ_TRACKER_PIX}</b>',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Real-Time open rates',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_OPEN_RATES_AUTO',
            'configuration_value' => 'True',
            'configuration_description' => 'Update Openrates every 30sec. For high-load sites deactivate and use cronjob instead',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Add Click Tracking in txt Emails',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_REWRITE_FORMAT',
            'configuration_value' => 'True',
            'configuration_description' => 'Rewrite Urls in txt format?',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'Google Analytics URL Rewrite Format',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_GA_REWRITE_FORMAT',
            'configuration_value' => 'True',
            'configuration_description' => 'Rewrite Urls in txt format?',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'Rows per Page',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_SPLITPAGE_NUM',
            'configuration_value' => '50',
            'configuration_description' => 'Number of Datarows per Page',
            'set_function' => ''
        ));


        // bugfix
        mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_CONFIG_DASHBOARD_START',
            'configuration_value' => 'home',
            'set_function' => 'mh_cfg_select_option(array(\'home\', \'mailbeez\', \'configbeez\', \'filterbeez\', \'reportbeez\'), '
        ), true);


        // coupon tracking
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD coupon_id INT(11) ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'coupon_id', $sql);
        $sql = array();
        $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD coupon_redeemed INT(5) NOT NULL DEFAULT '0' ;";
        mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'coupon_redeemed', $sql);

        mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_VERSION',
            'configuration_value' => $this->version
        ), true);

        mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_VERSION_DISPLAY',
            'configuration_value' => $this->version_display
        ), true);

        // add index, check if index is already there
        if (!mh_db_check_index_on_column_exists(TABLE_MAILBEEZ_TRACKING, 'message_id')) {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( message_id )");
        }


        // MailBeez V2.7
        mh_insert_config_value(array('configuration_title' => 'Encode Email Subject',
            'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_ENCODE_SUBJECT',
            'configuration_value' => 'False',
            'configuration_description' => 'Avoid strange characters showing up in subject lines',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        // MailBeez V2.7.4
        mh_insert_config_value(array('configuration_title' => 'Convert CSS into inline style',
            'configuration_key' => 'MAILBEEZ_CONFIG_TEMPLATE_ENGINE_EMOGRIFY',
            'configuration_value' => 'True',
            'configuration_description' => 'For best layouts across different email clients set to True. Requires PHP5.',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Set PHPMailer Debug Mode',
            'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_ENGINE_DEBUG_MODE',
            'configuration_value' => 'False',
            'configuration_description' => 'Activate Debug Mode for PHPMailer',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'TXT Wordwrap',
            'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_ENGINE_WORDWRAP',
            'configuration_value' => '80',
            'configuration_description' => 'Sets word wrapping on the body of the message to a given number of characters. Set to 0 for disabling wordwrapping.',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Email Encoding',
            'configuration_key' => 'MAILBEEZ_CONFIG_EMAIL_ENGINE_ENCODING',
            'configuration_value' => '',
            'configuration_description' => 'Set the encoding for emails. Leave empty for automatic encoding detection. Values are e.g. "UTF-8", "ISO-8859-1"',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'PHP Memory Limit',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL_MEMORY_LIMIT',
            'configuration_value' => '512M',
            'configuration_description' => 'Set the PHP memory limit for the sending process, default 512M',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Set Ignore User Abort',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL_INGORE_USER_ABORT',
            'configuration_value' => 'True',
            'configuration_description' => 'Sets whether a client disconnect should cause a script to be aborted. Default: True',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'PHP Execution Time Limit',
            'configuration_key' => 'MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TIME_LIMIT',
            'configuration_value' => '600',
            'configuration_description' => 'Set the PHP executing time limit. Default: 600sec',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Disable DB Systemcheck',
            'configuration_key' => 'MH_SYSTEMCHECK_DB_DISABLED',
            'configuration_value' => 'False',
            'configuration_description' => 'Disable check for DB Issues. Default: False',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));


        mh_insert_config_value(array('configuration_title' => 'Beginning of Time',
            'configuration_key' => 'MAILBEEZ_ANALYTICS_BEGIN_OF_TIME',
            'configuration_value' => '2000-01-01 00:00:00',
            'configuration_description' => 'Set the Beginning of Time for MailBeez Analytics Summary. Should be the date you started tracking orders. Default: 2000-01-01 00:00:00'));


        // add index, check if index is already there
        if (!mh_db_check_index_on_column_exists(TABLE_ORDERS, 'orders_status')) {
            mh_db_query("ALTER TABLE " . TABLE_ORDERS . " ADD INDEX ( orders_status )");
        }
        // add index, check if index is already there
        if (!mh_db_check_index_on_column_exists(TABLE_ORDERS_STATUS_HISTORY, 'orders_status_id')) {
            mh_db_query("ALTER TABLE " . TABLE_ORDERS_STATUS_HISTORY . " ADD INDEX ( orders_status_id )");
        }
        // add index, check if index is already there
        if (!mh_db_check_index_on_column_exists(TABLE_ORDERS_STATUS_HISTORY, 'orders_id')) {
            mh_db_query("ALTER TABLE " . TABLE_ORDERS_STATUS_HISTORY . " ADD INDEX ( orders_id )");
        }
        // add index, check if index is already there
        if (!mh_db_check_index_on_column_exists(TABLE_MAILBEEZ_EVENT_LOG, 'batch_id')) {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_EVENT_LOG . " ADD INDEX ( batch_id )");
        }


        // V2.81
        // add index, check if index is already there
        if (!mh_db_check_index_on_column_exists(TABLE_MAILBEEZ_TRACKING, 'module')) {
            mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( module )");
        }

        if (MAILBEEZ_VERSION < 2.86) {

            // V2.8.6
            // mobile user agent analytics
            $sql = array();
            $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD mobile INT( 1 ) NOT NULL ;";
            $sql[] = "ALTER TABLE " . TABLE_MAILBEEZ_TRACKING . " ADD INDEX ( mobile ) ;";
            mh_db_add_field(TABLE_MAILBEEZ_TRACKING, 'mobile', $sql);


            // add index, check if index is already there
            if (!mh_db_check_index_on_column_exists(TABLE_MAILBEEZ_OPENS_LOG, 'user_agent')) {
                mh_db_query("ALTER TABLE " . TABLE_MAILBEEZ_OPENS_LOG . " ADD INDEX ( user_agent )");
            }


            require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_analytics.php');

            // update historic data
            // from now the mobile information will be updated when user opens an email
            // see clicktracker -> import_log_file()

            $mobile_sql = "update " . TABLE_MAILBEEZ_TRACKING . " t
                            inner join " . TABLE_MAILBEEZ_OPENS_LOG . " o on (t.message_id = o.message_id)
                        set t.mobile = 1
                        where o.user_agent REGEXP '" . mailbeez_analytics::MOBILE_REGEX1 . "' or
                            SUBSTRING(o.user_agent,0,4)  REGEXP '" . mailbeez_analytics::MOBILE_REGEX1 . "'";

            mh_db_query($mobile_sql);
        }


        // update version - last thing after everything was updated
        mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $this->version . "', last_modified = now() where configuration_key = 'MAILBEEZ_VERSION'");


        // redirect
        if (defined('MAILBEEZ_VERSION') && (str_replace(',', '.', MAILBEEZ_VERSION) < $this->version)) {
            // updated
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'action=config_update_ok&tab=home'));
        } else {
            mh_redirect(mh_href_link(FILENAME_MAILBEEZ, 'tab=home'));
        }
    }

}

?>