<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_loyalty_o_graph extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_loyalty_o_graph()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_loyalty_o_graph';
        $this->module = 'dashboard_loyalty_o_graph';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.1;
        $this->title = MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_DESC;
        $this->description_image = '../../common/images/icon_ma_64.png';
        $this->icon = '../../common/images/icon_ma.png';
        //$this->removable = false; // can't be removed
        $this->enabled = ((MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_STATUS';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = $this->module;

        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = '';

        $this->sort_order = MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_SORT_ORDER;
    }

function getOutput()
{

    $timeframe_skip = $this->dbdate(MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_PASSED_DAYS_SKIP);

    // great to get help!
    // http://stackoverflow.com/questions/9376232/cumulative-count-over-time
    $raw_query = "
/* Top level will sanitise the output */
SELECT
    collatedData.orderYear,
    collatedData.orderMonth,
    collatedData.distinctOrders,
    collatedData.ordersInThisMonth AS count_orders,
    collatedData.distinctCustomers AS count_customers,
    collatedData.customerIDs AS customer_ids
FROM (
    /* This level up will iterate through calculating running totals */
    SELECT
        ordersPerDate.*,
        IF(
            (ordersPerDate.orderYear,ordersPerDate.orderMonth) = (@thisYear,@thisMonth),
            @runningTotal := @ordersPerDate.distinctOrders*ordersPerDate.distinctCustomers,
            @runningTotal := 0
        ) AS ordersInThisMonth,
        @thisMonth := ordersPerDate.orderMonth,
        @thisYear := ordersPerDate.orderYear
    FROM
    (
        SELECT
        @thisMonth := 0,
        @thisYear := 0,
        @runningTotal := 0
    ) AS variableInit,
    (
        /* Next level up will collate this to get per year, month, and per number of orders */
        SELECT
            ordersPerDatePerUser.orderYear,
            ordersPerDatePerUser.orderMonth,
            ordersPerDatePerUser.distinctOrders,
            COUNT(DISTINCT ordersPerDatePerUser.customers_id) AS distinctCustomers,
            GROUP_CONCAT(ordersPerDatePerUser.customers_id) AS customerIDs
        FROM (
            /* Inner query will get the number of orders for each year, month, and customer */
            SELECT
                YEAR(date_purchased) AS orderYear,
                MONTH(date_purchased) AS orderMonth,
                customers_id,
                COUNT(*) AS distinctOrders
            FROM " . TABLE_ORDERS . "
            where date_purchased >= '" . $timeframe_skip . "'
            GROUP BY orderYear ASC, orderMonth ASC, customers_id ASC
        ) AS ordersPerDatePerUser
        GROUP BY
            ordersPerDatePerUser.orderYear ASC,
            ordersPerDatePerUser.orderMonth ASC,
            ordersPerDatePerUser.distinctOrders DESC
    ) AS ordersPerDate
) AS collatedData";

    $query = mh_db_query($raw_query);

    $group_limit = 4;
    $data_processed = array();
    $js_array = array();

    while ($data_months = mh_db_fetch_array($query)) {
        $number_of_orders = $data_months['distinctOrders'];
        $amount = $data_months['count_customers'];

        $year = $data_months['orderYear'];
        $months = $data_months['orderMonth'];

        $number_of_orders_group = ($number_of_orders < $group_limit) ? $number_of_orders
            : $group_limit;

        $data_processed[$year . '-' . $months][$number_of_orders_group] = $amount;
        $data_processed[$year . '-' . $months]['sum'] += $amount;
    }

    foreach ($data_processed as $yearmonth => $data_array) {
        list($year, $months) = explode('-', $yearmonth);

        for ($i = 0; $i < $group_limit; $i++) {
            if (!isset($data_array[$i])) {
                $data_array[$i] = 0;
            }
        }

        $sum = $data_array['sum'];
        foreach ($data_array as $number_of_orders_group => $amount) {
            if ($number_of_orders_group == 'sum') {
                continue;
            } else {
                if (true) {
                    // percentage
                    $amount = $amount / $sum * 100;
                }
                if ($number_of_orders_group == 1) {
                    continue;
                }
                $js_array[$number_of_orders_group] .= '[' . (mktime(0, 0, 0, $months, 1, $year) * 1000) . ', ' . $amount . '],';
            }
        }

    }

    ksort($js_array);

    //$js_array = array_reverse($js_array, true);

    // ---


    /*
    $js_array = array();
    foreach ($data as $months => $data_months) {
        foreach ($data_months as $number_of_orders => $amount) {
            if (!true) {
                $amount = $amount / $ordercnt_sum[$months] * 100;

            }

            $js_array[$number_of_orders] .= '[' . (mktime(0, 0, 0, $months, 1, 2011) * 1000) . ', ' . $amount . '],';
        }
    }
    */


    ob_start();

    ?>
    <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_TITLE?></div>
    <div
        id="WidgetSubTitle"><?php echo sprintf(MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_DESC, MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_PASSED_DAYS_SKIP); ?></div>
    <div id="loyalty_o_graph" style="width: 100%; height: 150px; margin-top: 10px;"></div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            <?php
            foreach ($js_array as $dataline => $js_data_array) {
                ?>
            var plot_loyalty_o_graph_<?php echo $dataline ?> = [<?php echo $js_data_array ?>];
            <?php

        }
        ?>
            var plot_loyalty_o_graph_block = false;
            jQuery.plot(jQuery('#loyalty_o_graph'), [
                <?php
                //reset($js_array);
                // $js_array = array_reverse($js_array, true);
                $colors = array('1' => '#abd37f',
                    '2' => '#fff7a5',
                    '3' => '#f8933c'
                );
                foreach ($js_array as $dataline => $js_data_array) {
                    $color = $colors[$dataline];
                    ?>
                {
                    label: '<?php echo $dataline ?>',
                    data: plot_loyalty_o_graph_<?php echo $dataline ?>,
                    lines: { show: true, lineWidth: 1 },

                    points: { show: true, radius: 0 },
                    /* color: '<?php echo $color ?>', */
                    shadowSize: 1
                },
                <?php

            }
            ?>
                {
                    label: ''
                }
            ], {
                xaxis: {
                    ticks: 7,
                    mode: 'time'
                },
                yaxis: {
                    ticks: 3,
                    min: 0,
                    tickFormatter: percentageFormatter
                },
                grid: {
                    backgroundColor: { colors: ['#fcfcfc', '#f0f0f0'] },
                    hoverable: true,
                    borderColor: '#888',
                    fontFamily: 'Trebuchet MS, Arial'
                },
                legend: {
                    position: 'nw',
                    labelFormatter: function (label, series) {
                        if (label == '<?php echo $group_limit ?>') {
                            return '' + label + '<?php echo MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_ORDERS_GROUP_TEXT; ?>';
                        } else {
                            return '' + label + '<?php echo MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_ORDERS_TEXT; ?>';
                        }
                    }

                },
                series: {
                    stack: true,
                    lines: { show: true, fill: true, steps: false },
                    bars: { show: false, barWidth: 0.6 }
                }
            });
        });

        function percentageFormatter(v, axis) {
            return v.toFixed(axis.tickDecimals) + "%";
        }

        function showTooltipLoyalty(x, y, contents) {
            jQuery('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y + 15,
                left: x - 45,
                border: '1px solid #fdd',
                padding: '2px',
                backgroundColor: '#fee',
                fontFamily: 'Arial',
                fontSize: '10px',
                opacity: 0.80,
                'z-index': 1000
            }).appendTo('body').fadeIn(200);
        }

        var monthNames = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

        var previousPoint = null;
        jQuery('#loyalty_o_graph').bind('plothover', function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;

                    jQuery('#tooltip').remove();
                    var x = item.datapoint[0],
                        y = item.datapoint[1],
                        xdate = new Date(x);

                    showTooltipLoyalty(item.pageX, item.pageY, y.toFixed(2) + '% for ' + monthNames[xdate.getMonth()] + '-' + xdate.getDate());
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
        return defined('MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Loyalty-O-Graph',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to show the MailBeez Loyalty-O-Graph on the dashboard? (recommended)',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Count this number of past days:',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_PASSED_DAYS_SKIP',
            'configuration_value' => '365',
            'configuration_description' => 'number of past days to use for calculation',
            'set_function' => ''
        ));


        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_SORT_ORDER',
            'configuration_value' => '80',
            'configuration_description' => 'Sort order of display. Lowest is displayed first.',
            'set_function' => ''
        ));
    }


    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_STATUS', 'MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_PASSED_DAYS_SKIP', 'MAILBEEZ_DASHBOARD_LOYALTY_O_GRAPH_SORT_ORDER');
    }

}

?>