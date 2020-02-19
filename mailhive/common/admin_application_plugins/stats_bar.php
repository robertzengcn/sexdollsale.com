<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.7
 */

///////////////////////////////////////////////////////////////////////////////
///																			 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/mailbeez_analytics.php');


class mailbeez_stats_bar extends mailbeez_analytics
{

// class constructor
    function get_stats_bar()
    {

        list($cnt_sent, $cnt_opened, $cnt_clicked, $cnt_delivered) = $this->get_engagement_data();
        list($revenue_value, $cnt_orders, $coupon_value) = $this->get_orders_data();
        list($cnt_mobile) = $this->get_mobile_useragent_data();

        $pct_delivered = ($cnt_sent > 0) ? $cnt_delivered / $cnt_sent * 100 : 0;
        $pct_opened = ($cnt_sent > 0) ? $cnt_opened / $cnt_delivered * 100 : 0;
        $pct_clicked = ($cnt_opened > 0) ? $cnt_clicked / $cnt_opened * 100 : 0;
        $pct_ordered = ($cnt_clicked > 0) ? $cnt_orders / $cnt_clicked * 100 : 0;
        $pct_mobile = ($cnt_sent > 0) ? $cnt_mobile / $cnt_delivered * 100 : 0;

        $revenue = mh_price((int)$revenue_value);
        $coupon_val = mh_price((int)$coupon_value);
        //$revenue = (int) $revenue_value;
        $smarty = new Smarty;
        $smarty->caching = false;
        $smarty->template_dir = MH_DIR_FS_CATALOG . 'mailhive/common/admin_application_plugins/templates/';
        $smarty->compile_dir = MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMPILE_DIR;
        $smarty->compile_check = true;
        $smarty->compile_id = 'statsbar';
        //$smarty->config_dir = $this->view_module_directory_path . $this->module . '/languages/';

        if (file_exists($smarty->config_dir . $_SESSION['language'] . '.conf')) {
            $smarty->assign('language', $_SESSION['language']);
        } else {
            $smarty->assign('language', 'english');
        }


        $smarty->assign(array('cnt_sent' => $cnt_sent,
                'cnt_delivered' => $cnt_delivered,
                'cnt_opened' => $cnt_opened,
                'cnt_clicked' => $cnt_clicked,
                'cnt_orders' => $cnt_orders,
                'cnt_mobile' => $cnt_mobile,
                'pct_delivered' => $pct_delivered,
                'pct_opened' => $pct_opened,
                'pct_clicked' => $pct_clicked,
                'pct_ordered' => $pct_ordered,
                'pct_mobile' => $pct_mobile,
                'revenue' => $revenue,
                'coupon_val' => $coupon_val,
                'beginning_of_time' => $this->timeframe_start,
                'MH_CATALOG_URL' => MH_CATALOG_SERVER . MH_DIR_WS_CATALOG)
        );


        $content = $smarty->fetch('stats_bar.tpl');


        return $content;

    }
}

?>

<script language="javascript" src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG ?>mailhive/common/js/easy-pie/jquery.easy-pie-chart.js"></script>

<script type="text/javascript">

    var color_delivered = '#a0a0a0';
    var color_open = '#9ac2df';
    var color_click = '#f8933c';
    var color_order = '#abd37f';


    var initPieChartDashboard = function () {

        jQuery('.sb_percentage-delivered').easyPieChart({
            barColor: color_delivered,
            size: 50,
            trackColor: 'rgba(214, 176, 252, .2)',
            scaleColor: false,
            lineCap: 'round',
            lineWidth: 5,
            animate: 500
        });

        jQuery('.sb_percentage-open').easyPieChart({
            barColor: color_open,
            size: 50,
            trackColor: 'rgba(154, 194, 223, .2)',
            scaleColor: false,
            lineCap: 'round',
            lineWidth: 5,
            animate: 500
        });

        jQuery('.sb_percentage-click').easyPieChart({
            barColor: color_click,
            size: 50,
            trackColor: 'rgba(248, 147, 60, .2)',
            scaleColor: false,
            lineCap: 'round',
            lineWidth: 5,
            animate: 500
        });
        jQuery('.sb_percentage-order').easyPieChart({
            barColor: color_order,
            size: 50,
            trackColor: 'rgba(171, 211, 127, .2)',
            scaleColor: false,
            lineCap: 'round',
            lineWidth: 5,
            animate: 500
        });


    };

</script>

<style type="text/css">
    .dashboard_stats_bar_container {
        width: auto;
        /*width: 650px;*/
        margin: auto;
        text-align: center;
        display: inline-block;
        /*position: fixed;*/
        /*bottom: 0px;*/
        /*top: 0px;*/
        /*background-color: rgba(32, 32, 32, .75);*/
    }

    .sb_chart, .sb_chart_number, .sb_chart_arrow, .sb_chart_divider {
        float: left;
        margin: 10px;
        margin-bottom: 1px;
        color: #363636 !important;
        font: "Trebuchet MS, Arial" !important;
    }

    .sb_chart_arrow {
        font-size: 20px;
        color: rgba(0, 0, 0, .25) !important;
        margin: 2px;
        padding-top: 20px;
        font: "Trebuchet MS, Arial" !important;

    }

    .sb_chart_number {
        margin: 0px;
        margin-top: 20px;
        padding-left: 5px;
        padding-right: 5px;
        min-width: 160px;
        border: 0px solid #808080;
        font: "Trebuchet MS, Arial" !important;
    }

    .sb_chart_divider {
        border-left: 1px solid rgba(128, 128, 128, .15);
        height: 80px;
    }

    .sb_chart_number > .number {
        color: #363636 !important;
        font-size: 32px;
    }

    .sb_chart_number > .label {
        font-size: 12px;
        color: #808080;
        margin-top: 7px;
    }

    .percentage,
    .label {
        text-align: center;
        color: #808080 !important;
        font-weight: 100;
        font-size: 1.2em;
        margin-bottom: 0.1em;
        font-size: 12px;
    }

    .dashboard_stats_bar_container .easyPieChart {
        position: relative;
        text-align: center;
        color: #363636 !important;
        font-size: 14px;
        font: "Trebuchet MS, Arial" !important;
    }

    .dashboard_stats_bar_container .easyPieChart canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .sb_disabled {
        opacity: 0.25;
    }

    .sb_disabled_message {
        font-size: 12px;
        font: "Trebuchet MS, Arial";
        color: #363636;
        background-color: rgba(255, 255, 255, 1);
        box-shadow: 0 0 4px 1px rgba(0, 0, 0, 0.25);

        padding: 10px;
        border: 1px solid #c0c0c0;
        border-radius: 4px;
        display: inline-block;
    }

    .sb_disabled_message > a {
        font-size: 12px;
        font: "Trebuchet MS, Arial";
    }

    .sb_disabled_message_container {
        position: absolute;
        z-index: 100;
        margin-left: 30px;
        /*width: 100%;*/
        text-align: center;
        padding-top: 10px;
    }

    /*   http://cssarrowplease.com/ */
    .arrow_box {
        border-radius: 5px;
        /*height: 60px;*/
        padding: 20px;
        /*width: 280px;*/
        color: #195380;
        /*font-weight: bold;*/

        font-size: 14px;
    }
    .arrow_box {
    	position: relative;
    	background: #9ac1e0;
    	border: 1px solid #68A2CF;
    }
    .arrow_box:after, .arrow_box:before {
    	top: 100%;
    	border: solid transparent;
    	content: " ";
    	height: 0;
    	width: 0;
    	position: absolute;
    	pointer-events: none;
    }

    .arrow_box:after {
    	border-color: rgba(154, 193, 224, 0);
    	border-top-color: #9ac1e0;
    	border-width: 10px;
    	left: 50%;
    	margin-left: -10px;
    }
    .arrow_box:before {
    	border-color: rgba(104, 162, 207, 0);
    	border-top-color: #68A2CF;
    	border-width: 11px;
    	left: 50%;
    	margin-left: -11px;
    }

</style>


<div style="position: relative">

    <?php if (MAILBEEZ_ANALYTICS_STATUS == 'False') { ?>

        <div class="sb_disabled_message_container">
        <span class="sb_disabled_message">
            <?php echo MAILBEEZ_DASHBOARD_ANALYTICS_MSG; ?>
            <br>
            <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_analytics/admin_application_plugins/confirm.php&popup=true'), MAILBEEZ_ANALYTICS_STATUS_BUTTON, '', 'popup', 'link', '', 'document', 'iframe width:450 height:300'); ?>
        </span>
        </div>
    <?php } ?>
    <div class="<?php if (MAILBEEZ_ANALYTICS_STATUS == 'False') { ?>sb_disabled<?php } ?>">
        <?php
        $stats_bar = new mailbeez_stats_bar();
        echo $stats_bar->get_stats_bar();
        ?>
    </div>
</div>
<script type="text/javascript">
    initPieChartDashboard();
</script>
