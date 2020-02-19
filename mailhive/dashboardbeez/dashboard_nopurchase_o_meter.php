<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_nopurchase_o_meter extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_nopurchase_o_meter()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_nopurchase_o_meter';
        $this->module = 'dashboard_nopurchase_o_meter';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.5;
        $this->title = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_DESC;
        $this->description_image = '../../common/images/icon_ma_64.png';
        $this->icon = '../../common/images/icon_ma.png';
        //$this->removable = false; // can't be removed
        $this->enabled = ((MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_STATUS';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = $this->module;

        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = '';

        $this->sort_order = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_SORT_ORDER;
    }

    function dbdate($day)
    {
        $rawtime = strtotime(-1 * (int)$day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }

    function getOutput()
    {

        define('MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_1', (int) ( 3 * MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FACTOR));
        define('MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_2', (int) (7 * MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FACTOR));
        define('MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_3', (int) (10 * MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FACTOR));


        $nopurchase_delay_days = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_DELAY_DAYS;
        $nopurchase_last_days = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_PASSED_DAYS_SKIP;

        $nopurchase_rate_max = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_3;

        $date_email_passed = $this->dbdate(-1);
        $date_email_skip = $this->dbdate($nopurchase_last_days + $nopurchase_delay_days);

        // get a list of customers who got a nopurchase reactivation email in the last e.g. 10 days
        $nopurchase_email_query_sql = "select distinct customers_id
                                from " . TABLE_MAILBEEZ_TRACKING . "
                             where module like 'nopurchase%'
                                and date_sent <= '" . $date_email_passed . "'
                                and date_sent > '" . $date_email_skip . "' ";
        $nopurchase_email_query = mh_db_query($nopurchase_email_query_sql);


        // all customers who got a nopurchase email
        $nopurchase_email_customers_id_array = array();
        while ($nopurchase_email = mh_db_fetch_array($nopurchase_email_query)) {
            $nopurchase_email_customers_id_array[] = $nopurchase_email['customers_id'];
        }
        $nopurchase_email_customers_id_list = implode(',', $nopurchase_email_customers_id_array);

        $nopurchase_email_count = sizeof($nopurchase_email_customers_id_array);


        // see how many of these customers placed an order in the timeframe
        $date_skip_orders = $this->dbdate($nopurchase_last_days);
        $date_passed_orders = $this->dbdate(-1);

        $nopurchase_orders_cnt_query_raw = "select count(distinct customers_id) as customers_cnt
                  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS_HISTORY . " s
                  where o.customers_id in (" . $nopurchase_email_customers_id_list . ")
                    and o.date_purchased <= '" . $date_passed_orders . "'
                    and o.date_purchased > '" . $date_skip_orders . "'";

        $nopurchase_orders_count = 0;
        if ($nopurchase_email_count > 0) {
            $nopurchase_orders_cnt_query = mh_db_query($nopurchase_orders_cnt_query_raw);
            $nopurchase_orders_cnt = mh_db_fetch_array($nopurchase_orders_cnt_query);
            $nopurchase_orders_count = $nopurchase_orders_cnt['customers_cnt'];
        }

        // Demo Data
        /*
        $nopurchase_email_count = 142;
        $nopurchase_orders_count = 3;
        */
        

        if ($nopurchase_email_count > 0) {
            $nopurchase_rate = 100 * $nopurchase_orders_count / $nopurchase_email_count;
        } else {
            $nopurchase_rate = 0;
        }

        $nopurchase_show_fix_hint = (defined('MAILBEEZ_NOPURCHASE_ADVANCED_STATUS')) ? false : true;

        if ($nopurchase_rate == 0) {
            $nopurchase_rate_title = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_MSG_1;
            $rate_color = '#f8933c';
            $rate_color_text = '#b85c0c';
        }
        if ($nopurchase_rate > 0) {
            $nopurchase_rate_title = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_MSG_2;
            $rate_color = '#f8933c';
            $rate_color_text = '#b85c0c';
        }
        if ($nopurchase_rate > MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_1) {
            $nopurchase_rate_title = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_MSG_3;
            $rate_color = '#fff7a5';
            $rate_color_text = '#aba032';
        }
        if ($nopurchase_rate > MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_2) {
            $nopurchase_rate_title = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_MSG_4;
            $rate_color = '#abd37f';
            $rate_color_text = '#597c32';
            $nopurchase_show_fix_hint = false;
        }
        if ($nopurchase_rate >= MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_3) {
            $nopurchase_rate_title = MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_MSG_5;
            $rate_color = '#abd37f';
            $rate_color_text = '#597c32';
            $nopurchase_show_fix_hint = false;
        }
        ob_start();


        // http://code.google.com/p/jsgauge/wiki/API
        ?>

    <?php
 /*
        echo  $nopurchase_email_query_sql;
        echo "<hr>";
        echo  $nopurchase_orders_cnt_query_raw;
        echo "<hr>";
*/

        ?>

    <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_TITLE?></div>
    <div id="WidgetSubTitle"><?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_DESC; ?></div>

    <?php if (MAILBEEZ_VERSION < 2.3): ?>
    <!--[if IE]>
    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/js/excanvas.min.js"></script><![endif]-->

    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/js/gauge.js"></script>
    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/js/jquery.gauge.js"></script>
    <?php endif; ?>

    <div style="float: left; z-index: 5; position: relative; height: 70px; margin-top: 7px;">
        <canvas id="nopurchase" width="130" height="130" style="margin-left: 20px;"></canvas>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#nopurchase")
                    .gauge({
                        colorOfText: '#363636',
                        colorOfWarningText: '#597c32',
                        colorOfFill: [ '#888', '#333', '#ddd', '#fcfcfc' ],
                        colorOfPointerFill: '#909090',
                        colorOfPointerStroke: '#909090',
                        colorOfCenterCircleFill : '#909090',
                        colorOfCenterCircleStroke: '#909090',
                        majorTicks: 1,
                        minorTicks: 1,
                        min: 0,
                        max: <?php echo $nopurchase_rate_max; ?>,
                        label: 'RATE',
                        unitsLabel: '%',
                        bands: [
                            {color: "#f8933c", from: 0, to: <?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_1; ?>},
                            {color: "#fff7a5", from: <?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_1; ?>, to: <?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_2; ?>},
                            {color: "#abd37f", from: <?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_2; ?>, to: <?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_LIMIT_3; ?>}
                        ]
                    })
        });
        setTimeout('jQuery("#nopurchase").gauge("setValue", <?php echo $nopurchase_rate; ?>)', 300);
    </script>
    <div style="margin-left: 0px; border: 0px solid red; position: relative; z-index: 2;min-height: 130px;">

        <div style="font-family: Trebuchet MS; font-size: 11px; font-weight: bold; color: #363636; margin-left: 170px; margin-top: 27px;">
            <?php echo sprintf(MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_RATE_TEXT, $nopurchase_rate)?>
        </div>

        <div style=" margin-top: 10px;position:relative; background-color: <?php echo $rate_color; ?>; padding: 7px; text-align: center; color: <?php echo $rate_color_text; ?>; font-weight: bold; font-size: 14px;"><?php echo $nopurchase_rate_title; ?></div>

        <?php if ($nopurchase_show_fix_hint) { ?>
        <div style="margin-left: 170px; margin-top: 12px; font-family: Trebuchet MS; font-size: 11px; font-weight: bold; color: #363636;">
            <?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FIX_TEXT;?>
            <br/>
            <a href="http://www.mailbeez.com/documentation/mailbeez/nopurchase_advanced/<?php echo (defined('MH_LINKID_1')
                    ? MH_LINKID_1 : ''); ?>"
               target="_blank"><?php echo MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FIX_TEXT_LINK;?></a>

        </div>
        <?php }?>


    </div>
    <div style="position: relative; bottom: 3px; font-family: Trebuchet MS; font-size: 11px; font-weight: normal; color: #898989">
        <?php echo sprintf(MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_STATS_TEXT, $nopurchase_last_days, $nopurchase_orders_count, $nopurchase_email_count, $nopurchase_delay_days); ?>
    </div>

    <?php
        $output = ob_get_contents();
        ob_end_clean();
        /*
        *

           <link rel="stylesheet" type="text/css" href="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/css/css.css">
           <link rel="stylesheet" href="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/css/gauge_screen.css" type="text/css"/>
           <!--[if IE]><script type="text/javascript" language="javascript" src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/js/excanvas.min.js"></script><![endif]-->
           <script language="javascript" type="text/javascript" src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/js/jQueryRotateCompressed.2.1.js"></script>
           <script language="javascript" type="text/javascript" src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/dashboardbeez/dashboard_nopurchase_o_meter/js/jgauge-0.3.0.a3.js"></script>

           <div id="jGaugeDemo" class="jgauge"></div>

           <script type="text/javascript">
               var myGauge = new jGauge(); // Create a new jGauge.
               myGauge.id = 'jGaugeDemo'; // Link the new jGauge to the placeholder DIV.

               // This function is called by jQuery once the page has finished loading.
               $(document).ready(function() {
                   myGauge.init(); // Put the jGauge on the page by initialising it.
               });


               myGauge.setValue(7.35);
           </script>

        *
        *
        */


        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Nopurchase-O-Meter',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the MailBeez Nopurchase-O-Meter on the dashboard? (recommended)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Count this number of past days:',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_PASSED_DAYS_SKIP',
                                    'configuration_value' => '30',
                                    'configuration_description' => 'number of past days to use for calculation',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Delay between nopurchase and order:',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_DELAY_DAYS',
                                    'configuration_value' => '7',
                                    'configuration_description' => 'allow this delay between nopurchase email and order',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Industry Factor',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FACTOR',
                                    'configuration_value' => '0.5',
                                    'configuration_description' => 'Adjust the scale to your industry',
                                    'set_function' => ''
                               ));


        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_SORT_ORDER',
                                    'configuration_value' => '90',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }


    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_STATUS', 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_PASSED_DAYS_SKIP', 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_DELAY_DAYS', 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_FACTOR', 'MAILBEEZ_DASHBOARD_NOPURCHASE_O_METER_SORT_ORDER');
    }

}

?>
