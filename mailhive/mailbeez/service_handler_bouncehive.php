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
require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez.php');


class service_handler_bouncehive extends mailbeez
{
    // class constructor
    function service_handler_bouncehive()
    {
        // call constructor
        mailbeez::mailbeez();

        // set some stuff:
        $this->code = 'service_handler_bouncehive';
        $this->module = 'service_handler_bouncehive';
        $this->version = '2.0'; // float value
        $this->required_mb_version = 2.6; // required mailbeez version
        $this->iteration = 1;
        $this->title = MAILBEEZ_BOUNCEHIVE_HNDL_TEXT_TITLE;
        $this->description = MAILBEEZ_BOUNCEHIVE_HNDL_TEXT_DESCRIPTION;
        $this->sort_order = 20000;
        $this->enabled = true;
        $this->documentation_key = $this->module; // leave empty if no documentation available
        $this->admin_action_plugins = 'process.php';
        $this->common_admin_action_plugins = '';
        $this->do_process = (MAILBEEZ_BOUNCEHIVE_DO_RUN == 'True'); // a processable module
        $this->do_run = (MAILBEEZ_BOUNCEHIVE_DO_RUN == 'True');
        $this->is_editable = false; // allow editor
        $this->is_configurable = false;
        $this->removable = false;
        //&$this->hidden = ((MAILBEEZ_CONFIG_ADMIN_HIDE_HELPERS == 'True') ? true : false);
        $this->hidden = ((!MH_BOUNCEHANDLING_ENABLED) ? true : false);
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
        $this->external_bounce_process('False');
        return 0;
    }


    function external_bounce_process($external_call = 'True')
    {
        $bounce_mode = 'process';

        if ($external_call == '' || $external_call == 'True') {
            echo $this->html_header('configbeez/config_bouncehive_advanced/css/bouncehive_advanced.css');
            ?>
        <script type="text/javascript">
            function scrolldown() {
                var a = document.anchors.length;
                var b = document.anchors[a - 1];
                var y = b.offsetTop;
                window.scrollTo(0, y + 120);
            }
        </script>
        <?php

        }
        if (defined('MAILBEEZ_BOUNCEHIVE_STATUS') && MAILBEEZ_BOUNCEHIVE_STATUS == 'True') {
            require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_bouncehive_advanced/includes/service_handler_bouncehive.php');
        }
        if ($external_call == '' || $external_call == 'True') {
            echo $this->html_footer();
            mh_exit();
        }
    }

    function external_bounce_test()
    {
        $bounce_mode = 'test';

        echo $this->html_header('configbeez/config_bouncehive_advanced/css/bouncehive_advanced.css');
        ?>
    <script type="text/javascript">
        function scrolldown() {
            var a = document.anchors.length;
            var b = document.anchors[a - 1];
            var y = b.offsetTop;
            window.scrollTo(0, y + 120);
        }
    </script>
    <?php
        if (defined('MAILBEEZ_BOUNCEHIVE_STATUS') && MAILBEEZ_BOUNCEHIVE_STATUS == 'True') {
            require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_bouncehive_advanced/includes/service_handler_bouncehive.php');
        }
        echo $this->html_footer();
        mh_exit();
    }

    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

?>