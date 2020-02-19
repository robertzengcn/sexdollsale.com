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

class dashboard_beez_o_graph extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_beez_o_graph()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_beez_o_graph';
        $this->module = 'dashboard_beez_o_graph';
        $this->version = '2.2'; // float value
        $this->required_mb_version = 2.7;
        $this->title = MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_DESC;
        $this->description_image = '../../common/images/icon_ma_64.png';
        $this->icon = '../../common/images/icon_ma.png';
        //$this->removable = false; // can't be removed
        $this->enabled = ((MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = $this->module;

        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = '';

        $this->sort_order = MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SORT_ORDER;
    }


    function generateData() {

        // todo
        // under construction
        // modules to exclude
        $exclude_modules = array('mb_newsletter');

        $exclude_modules_sql = " and module not in ('" . implode("','", $exclude_modules) . "') ";

        $exclude_modules_sql = '';

        $filter_module_param = ''; // build url param
        // modules_exclude
        // modules_include


        $days = array();
        for ($i = 0; $i < MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP; $i++) {
            $days[date('Y-m-d', strtotime('-' . $i . ' days'))] = 0;
        }

        $days_sent_array = $days;
        $days_bounce_array = $days;
        $days_opened_array = $days;
        $days_clicked_array = $days;
        $days_ordered_array = $days;

        $result = false;

        $mailbeez_sent_query_sql = "select date_format(t.date_sent, '%Y-%m-%d') as dateday,
                                        count(t.autoemail_id) as sent_count,
                                        SUM(IF(t.bounce_status = 'H' or t.bounce_status = 'S', 1,0)) AS cnt_bounces,
                                        SUM(IF(t.opened > '2000-01-01 00:00:00', 1,0)) AS cnt_opened,
                                        SUM(IF(t.clicked > '2000-01-01 00:00:00', 1,0)) AS cnt_clicked,
                                        SUM(IF(t.ordered > '2000-01-01 00:00:00', 1,0)) AS cnt_ordered
                                        from " . TABLE_MAILBEEZ_TRACKING . " t
                                    where date_sub(curdate(), interval " . MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP . " day) <= t.date_sent
                                        " . $exclude_modules_sql . "
                                        " . str_replace('simulation', 't.simulation', MAILBEEZ_SIMULATION_SQL) . "
                                        group by dateday";

        $mailbeez_sent_query = mh_db_query($mailbeez_sent_query_sql);
        while ($mailbeez_sent_cnt = mh_db_fetch_array($mailbeez_sent_query)) {
            $result = true;
            $days_sent_array[$mailbeez_sent_cnt['dateday']] = $mailbeez_sent_cnt['sent_count'];
            $days_bounce_array[$mailbeez_sent_cnt['dateday']] = $mailbeez_sent_cnt['cnt_bounces'];
            $days_opened_array[$mailbeez_sent_cnt['dateday']] = $mailbeez_sent_cnt['cnt_opened'];
            $days_clicked_array[$mailbeez_sent_cnt['dateday']] = $mailbeez_sent_cnt['cnt_clicked'];
            $days_ordered_array[$mailbeez_sent_cnt['dateday']] = $mailbeez_sent_cnt['cnt_ordered'];
        }

        if ($result) {

            $days_sent_array = array_reverse($days_sent_array, true);
            $days_bounce_array = array_reverse($days_bounce_array, true);
            $days_opened_array = array_reverse($days_opened_array, true);
            $days_clicked_array = array_reverse($days_clicked_array, true);
            $days_ordered_array = array_reverse($days_ordered_array, true);

            $js_array_sent = '';
            $js_array_bounce = '';
            $js_array_open = '';
            $js_array_clicked = '';
            $js_array_ordered = '';
            $js_array_block = '';

            foreach ($days_sent_array as $date => $sent) {
                $js_array_sent .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $sent . '],';
            }
            foreach ($days_bounce_array as $date => $bounce) {
                $js_array_bounce .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $bounce . '],';
            }
            foreach ($days_opened_array as $date => $opened) {
                $js_array_open .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $opened . '],';
            }
            foreach ($days_clicked_array as $date => $clicked) {
                $js_array_clicked .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $clicked . '],';
            }
            foreach ($days_ordered_array as $date => $ordered) {
                $js_array_ordered .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $ordered . '],';
            }

            if (!empty($js_array_sent)) {
                $js_array_sent = substr($js_array_sent, 0, -1);
            }
            if (!empty($js_array_bounce)) {
                $js_array_bounce = substr($js_array_bounce, 0, -1);
            }
            if (!empty($js_array_open)) {
                $js_array_open = substr($js_array_open, 0, -1);
            }
            if (!empty($js_array_clicked)) {
                $js_array_clicked = substr($js_array_clicked, 0, -1);
            }
            if (!empty($js_array_ordered)) {
                $js_array_ordered = substr($js_array_ordered, 0, -1);
            }


            $days_block_array = $days;

            $mailbeez_block_query_sql = "select date_format(date_block, '%Y-%m-%d') as dateday, count(autoemail_id) as block_count
                                        from " . TABLE_MAILBEEZ_BLOCK . "
                                    where date_sub(curdate(), interval " . MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP . " day) <= date_block
                                        " . $exclude_modules_sql . "
                                        " . MAILBEEZ_SIMULATION_SQL . "
                                        group by dateday";

            $mailbeez_block_query = mh_db_query($mailbeez_block_query_sql);
            while ($mailbeez_block_cnt = mh_db_fetch_array($mailbeez_block_query)) {
                $days_block_array[$mailbeez_block_cnt['dateday']] = $mailbeez_block_cnt['block_count'];
            }

            $days_block_array = array_reverse($days_block_array, true);

            $js_array_block = '';
            foreach ($days_block_array as $date => $blocked) {
                $js_array_block .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $blocked . '],';
            }

            if (!empty($js_array_block)) {
                $js_array_block = substr($js_array_block, 0, -1);
            }


        }
        return array($js_array_sent, $js_array_bounce, $js_array_open, $js_array_clicked, $js_array_ordered, $js_array_block);

    }

    function getOutput()
    {

        list($js_array_sent, $js_array_bounce, $js_array_open, $js_array_clicked, $js_array_ordered, $js_array_block) = $this->generateData();

        $label_icon = " <img src='" . MH_CATALOG_SERVER . MH_DIR_WS_CATALOG . "mailhive/common/images/details.png' width='9' height='9' align='absmiddle' hspace='3' border='0'>";

        $label_icon = addslashes($label_icon);

        $chart_label_block = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_BLOCK_LINK) . $label_icon;
        $chart_label_block_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_block&app=load_app&app_path=report_block/report_block.php&popup=true' . $filter_module_param);

        $chart_label_sent = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SENT_LINK) . $label_icon;
        $chart_label_sent_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_track&app=load_app&app_path=report_track/report_track.php&back=dashboard&popup=true' . $filter_module_param);

        $chart_label_bounce = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_BOUNCE_LINK) . $label_icon;
        $chart_label_bounce_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_track&app=load_app&app_path=report_track/report_track.php&back=dashboard&filter=bounce&popup=true' . $filter_module_param);

        $chart_label_opened = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_OPENED_LINK) . $label_icon;
        $chart_label_opened_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_track&app=load_app&app_path=report_track/report_track.php&back=dashboard&filter=opened&popup=true' . $filter_module_param);

        $chart_label_clicked = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_CLICKED_LINK) . $label_icon;
        $chart_label_clicked_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_track&app=load_app&app_path=report_track/report_track.php&back=dashboard&filter=clicked&popup=true' . $filter_module_param);

        $chart_label_ordered = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_ORDERED_LINK) . $label_icon;
        $chart_label_ordered_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_track&app=load_app&app_path=report_track/report_track.php&back=dashboard&filter=ordered&popup=true' . $filter_module_param);
        ob_start();

        ?>

    <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_TITLE?></div>
    <div
        id="WidgetSubTitle"><?php echo sprintf(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_DESC, MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP); ?>
        <?php if (MAILBEEZ_ANALYTICS_STATUS == 'False') { ?>
            |
            <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_analytics/admin_application_plugins/confirm.php&popup=true'), MAILBEEZ_ANALYTICS_STATUS_BUTTON, '', 'popup', 'link', '', 'document', 'iframe width:450 height:300'); ?>
            <?php } ?>
    </div>
    <div id="beez_o_meter" style="width: 100%; height: 150px; margin-top: 10px;"></div>
    <script type="text/javascript">

        jQuery(document).ready(function () {
            var plot_beez_o_meter_sent = [<?php echo $js_array_sent ?>];
            var plot_beez_o_meter_bounced = [<?php echo $js_array_bounce ?>];
            var plot_beez_o_meter_opened = [<?php echo $js_array_open ?>];
            var plot_beez_o_meter_clicked = [<?php echo $js_array_clicked ?>];
            var plot_beez_o_meter_ordered = [<?php echo $js_array_ordered ?>];
            var plot_beez_o_meter_block = [<?php echo $js_array_block ?>];

            jQuery.plot(jQuery('#beez_o_meter'), [
                {
                    label:'<?php echo $chart_label_sent ?>',
                    data:plot_beez_o_meter_sent,
                    lines:{ show:true, fill:true, lineWidth:2  },
                    points:{ show:true, radius:1 },
                    color:'<?php echo MH_COLOR_DARKYELLOW;?>',
                    shadowSize:0
                },
                <?php if (MH_OPENTRACKER_ENABLED) { ?>
                    {
                        label:'<?php echo $chart_label_opened ?>',
                        data:plot_beez_o_meter_opened,
                        lines:{ show:true, fill:false },
                        points:{ show:true, radius:1 },
                        color:'<?php echo MH_COLOR_BLUE;?>' /*'#9ac2df'*/,
                        shadowSize:0
                    },
                    <?php } ?>
                <?php if (MH_CLICKTRACKER_ENABLED) { ?>
                    {
                        label:'<?php echo $chart_label_clicked ?>',
                        data:plot_beez_o_meter_clicked,
                        lines:{ show:true, fill:false },
                        points:{ show:true, radius:1 },
                        color:'<?php echo MH_COLOR_ORANGE;?>' /*'#7e9662'*/,
                        shadowSize:0
                    },
                    <?php } ?>
                <?php if (MH_ORDERTRACKER_ENABLED) { ?>
                    {
                        label:'<?php echo $chart_label_ordered ?>',
                        data:plot_beez_o_meter_ordered,
                        lines:{ show:true, fill:false },
                        points:{ show:true, radius:1 },
                        color:'<?php echo MH_COLOR_GREEN;?>' /*'#abd37f'*/,
                        shadowSize:0
                    },
                    <?php } ?>
                <?php if (MH_BOUNCEHANDLING_ENABLED) { ?>
                    {
                        label:'<?php echo $chart_label_bounce ?>',
                        data:plot_beez_o_meter_bounced,
                        lines:{ show:true, fill:false, lineWidth:2  },
                        points:{ show:true, radius:1 },
                        color:'<?php echo MH_COLOR_PINK;?>' /*'#ece288'*/,
                        shadowSize:0
                    },
                    <?php } ?>
                {
                    label:'<?php echo $chart_label_block ?>',
                    data:plot_beez_o_meter_block,
                    lines:{ show:true, fill:false, lineWidth:1 },
                    points:{ show:true, radius:1 },
                    color:'#9e9e9e',
                    shadowSize:0
                }
            ], {
                xaxis:{
                    ticks:7,
                    mode:'time'
                },
                yaxis:{
                    ticks:3,
                    min:0
                },
                grid:{
                    backgroundColor:{ colors:['#fcfcfc', '#f0f0f0'] },
                    hoverable:true,
                    borderColor:'#888',
                    fontFamily:'Trebuchet MS, Arial'
                },
                legend:{
                    position:'nw',
                    labelFormatter:function (label, series) {
                        if (label == '<?php echo $chart_label_sent ?>') {
                            return '<a onclick="return openReportPopup(\'<?php echo $chart_label_sent_link; ?>\')" href="<?php echo $chart_label_sent_link; ?>">' + label + '</a>';
                        }
                        if (label == '<?php echo $chart_label_bounce ?>') {
                            return '<a onclick="return openReportPopup(\'<?php echo $chart_label_bounce_link; ?>\')" href="<?php echo $chart_label_bounce_link; ?>">' + label + '</a>';
                        }
                        if (label == '<?php echo $chart_label_opened ?>') {
                            return '<a onclick="return openReportPopup(\'<?php echo $chart_label_opened_link; ?>\')" href="<?php echo $chart_label_opened_link; ?>">' + label + '</a>';
                        }
                        if (label == '<?php echo $chart_label_clicked ?>') {
                            return '<a onclick="return openReportPopup(\'<?php echo $chart_label_clicked_link; ?>\')" href="<?php echo $chart_label_clicked_link; ?>">' + label + '</a>';
                        }
                        if (label == '<?php echo $chart_label_ordered ?>') {
                            return '<a onclick="return openReportPopup(\'<?php echo $chart_label_ordered_link; ?>\')" href="<?php echo $chart_label_ordered_link; ?>">' + label + '</a>';
                        }
                        if (label == '<?php echo $chart_label_block ?>') {
                            return '<a onclick="return openReportPopup(\'<?php echo $chart_label_block_link; ?>\')" href="<?php echo $chart_label_block_link; ?>">' + label + '</a>';
                        }
                    },
                    backgroundOpacity:0.5
                }
            });
        });


        function showTooltip(x, y, contents) {
            jQuery('<div id="tooltip">' + contents + '</div>').css({
                position:'absolute',
                display:'none',
                top:y + 5,
                left:x + 15,
                border:'1px solid #fdd',
                padding:'2px',
                backgroundColor:'#fee',
                fontFamily:'Arial',
                fontSize:'10px',
                opacity:0.80
            }).appendTo('body').fadeIn(200);
        }

        var monthNames = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

        var previousPoint = null;
        jQuery('#beez_o_meter').bind('plothover', function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;

                    jQuery('#tooltip').remove();
                    var x = item.datapoint[0],
                        y = item.datapoint[1],
                        xdate = new Date(x);

                    showTooltip(item.pageX, item.pageY, y + ' for ' + monthNames[xdate.getMonth()] + '-' + xdate.getDate());
                }
            } else {
                jQuery('#tooltip').remove();
                previousPoint = null;
            }
        });


    </script>
    <?php
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Beez-O-Graph',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to show the MailBeez Beez-O-Graph on the dashboard? (recommended)',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Count this number of past days:',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP',
            'configuration_value' => '30',
            'configuration_description' => 'number of past days to use for calculation',
            'set_function' => ''
        ));


        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SORT_ORDER',
            'configuration_value' => '40',
            'configuration_description' => 'Sort order of display. Lowest is displayed first.',
            'set_function' => ''
        ));
    }


    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS', 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP', 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SORT_ORDER');
    }

}

?>