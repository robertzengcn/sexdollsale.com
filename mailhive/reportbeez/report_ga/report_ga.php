<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

if (!(floatval(phpversion()) > 4 && (MAILBEEZ_DASHBOARD_GA_STATUS == 'True'))) {
    return "The Google Analytics Dashboard Widget requires PHP 5.";
}

require_once(MH_DIR_FS_CATALOG . 'mailhive/reportbeez/report_ga/classes/gapic-0.5/apiClient.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/reportbeez/report_ga/classes/gapic-0.5/contrib/apiAnalyticsService.php');
require_once(MH_DIR_FS_CATALOG . 'mailhive/reportbeez/report_ga.php');
$client = new apiClient();
// Visit https://code.google.com/apis/console?api=plus to generate your
// client id, client secret, and to register your redirect uri.
$client->setApplicationName('MailBeez Google Analytics Integration');
$client->setClientId(MAILBEEZ_REPORT_GA_CLIENT_ID);
$client->setClientSecret(MAILBEEZ_REPORT_GA_CLIENT_SECRET);
$client->setRedirectUri(MAILBEEZ_REPORT_GA_CLIENT_REDIRECT_URI);
$service = new apiAnalyticsService($client);

if (!isset($_SESSION['mh_ga_token']) && MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN != '') {
    $_SESSION['mh_ga_token'] = MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN;
}

if (isset($_SESSION['mh_ga_token'])) {
    $client->setAccessToken($_SESSION['mh_ga_token']);
}


mh_define('MH_CATALOG_URL', MH_CATALOG_SERVER . DIR_WS_CATALOG);

$popup = false;
if (isset($_GET['popup']) && mh_not_null($_GET['popup'])) {
    mh_define('MH_BYPASS_TEMPLATE', true);
    $popup = true;
}

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=' . $_GET['module']);

?>
<?php
if ($popup) {

    mh_define('MH_POPUP', true);

    $app_module_id = 'report_ga';
    $app_path_module = '../reportbeez/' . $app_module_id;

    if (file_exists(MH_DIR_CONFIG . $app_path_module . '/languages/' . $_SESSION['language'] . MH_FILE_EXTENSION)) {
        // try to load language file
        include_once(MH_DIR_CONFIG . $app_path_module . '/languages/' . $_SESSION['language'] . MH_FILE_EXTENSION);
    } elseif (file_exists(MH_DIR_CONFIG . $app_path_module . '/languages/english' . MH_FILE_EXTENSION)) {
        // .. or english file as default if available
        include_once(MH_DIR_CONFIG . $app_path_module . '/languages/english' . MH_FILE_EXTENSION);
    } else {
        // no language file found!
    }

    ?>
<?php } ?>
<?php
if (!$popup) {
    echo mb_admin_button($back_url, MH_BUTTON_BACK_REPORTS, '', 'link') . '<br /><br />';
}
?>
<?php if ($msg != ''): ?>
<?php echo $msg; ?>
<?php endif; ?>


<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="pageHeading"><?php echo MAILBEEZ_REPORT_GA_TEXT_TITLE; ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
<?php

$ga_login_failure = false;
$ga_profile_id_set = false;

?>
<link rel="stylesheet" type="text/css" media="print, projection, screen"
      href="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG; ?>mailhive/dashboardbeez/dashboard_ga/admin.css">

<?php

if (!isset($_SESSION['mh_ga_token'])) {
    $ga_login_failure = true;
}


if ($ga_login_failure) {
    ?>
Please login on Dashboard
    <?php

} else {


    try {
        $profiles = $service->management_profiles->listManagementProfiles("~all", "~all");
        foreach ($profiles['items'] as $profile_item => $profile_data) {
            if ($profile_data['id'] == MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID) {
                $ga_profile_id_set = true;
            }
        }
    } catch (Exception $e) {
        die('An error occured: ' . $e->getMessage() . "\n");
        $ga_login_failure = true;
    }

    if ($ga_profile_id_set) {

        $projectId = MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID;
        $ga_start_date = report_ga::timestamp(MAILBEEZ_DASHBOARD_GA_PASSED_DAYS_SKIP);
        $ga_end_date = report_ga::timestamp(0);

        //        $definition = 'ga:medium==cpa,ga:medium==cpc,ga:medium==cpm,ga:medium==cpp';
        $metrics = 'ga:visits,ga:transactionRevenue,ga:transactions,ga:totalValue';
        //        $dimensions = 'ga:date,ga:year,ga:month,ga:day';
        $dimensions = 'ga:source,ga:medium,ga:campaign';
        $filters = 'ga:source==MailBeez';
        $sort = '-ga:visits';

        $ga_data = $service->data_ga->get('ga:' . $projectId, $ga_start_date, $ga_end_date, $metrics, array('dimensions' => $dimensions, 'filters' => $filters, 'sort' => $sort));

        ?>
    <div style="border: 1px solid #c0c0c0; padding: 10px; margin-top: 30px; background-color: #f9f9f9;">
        <div id="ga_pie"
             style="width:120px; height: 120px; margin-top: 10px; margin-left: 25px; margin-bottom: 11px;"></div>
    </div>
    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG; ?>mailhive/common/js/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript">
        jQuery(function () {
            var data = [];
            <?php
            $i = 0;
            foreach ($ga_data['rows'] as $result):
                $campaign_name = $result['2'];
                $transaction_revenue = $result['4'];
                $visits = $result['3'];
                ?>
                data[<?php echo $i++; ?>] = { label:"<?php echo str_replace('"', '', $campaign_name) ?>", data: <?php echo ($transaction_revenue == 0)
                    ? $visits : $transaction_revenue; ?> }
                <?php
            endforeach
            ?>
            jQuery.plot(jQuery('#ga_pie'), data,
                {
                    series:{
                        pie:{
                            show:true,
                            stroke:{color:'#f0f0f0', width:0.00001},
                            innerRadius:0.4,
                            radius:1,
                            label:{
                                show:true,
                                radius:3 / 4,
                                formatter:function (label, series) {
                                    //alert(series.data)
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + Math.round(series.percent) + '%</div>';
                                },
                                threshold:0.1
                            },
                            combine:{
                                color:'#999',
                                threshold:0.05
                            }
                        }
                    },
                    grid:{
                        hoverable:true,
                        clickable:true
                    },
                    legend:{
                        noColumns:2,
                        position:'nw',
                        backgroundOpacity:0,
                        margin:[150, 10]
                    }
                });
            jQuery("#ga_pie").bind("plothover", pieHover);
            jQuery("#ga_pie").bind("plotclick", pieClick);

        });

        function pieHover(event, pos, obj) {
            if (!obj)
                return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            $("#hover").html('<span style="font-weight: bold; color: ' + obj.series.color + '">' + obj.series.label + ' (' + percent + '%)</span>');
        }

        function pieClick(event, pos, obj) {
            if (!obj)
                return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            alert('' + obj.series.label + ': ' + percent + '%');
        }
    </script>
    <br/>
    <table border="0" width="100%" cellspacing="0" cellpadding="2"
           style="border-top: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0">
        <tr style="background-color: #fff;">
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; font-weight: bold;"
                align="left"><?php echo MAILBEEZ_REPORT_GA_TEXT_CAMPAIGN; ?></td>
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; font-weight: bold;"
                align="left"><?php echo MAILBEEZ_REPORT_GA_TEXT_VISITS;?></td>
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; font-weight: bold;"
                align="left"><?php echo MAILBEEZ_REPORT_GA_TEXT_TRANSACTIONS;?></td>
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; font-weight: bold;"
                align="left"><?php echo MAILBEEZ_REPORT_GA_TEXT_REVENUE;?></td>
        </tr>
        <?php
        foreach ($ga_data['rows'] as $result) {
            $campaign_name = $result['2'];
            $visits = $result['3'];
            $transaction_revenue = $result['4'];
            $transactions = $result['5'];
            ?>
            <tr style="background-color: #fff;">
                <td class="dataTableContent"
                    style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; "
                    align="left" nowrap="">
                    <?php echo $campaign_name; ?>
                </td>
                <td class="dataTableContent"
                    style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; "
                    align="left" nowrap="">
                    <?php echo $visits; ?>
                </td>
                <td class="dataTableContent"
                    style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; "
                    align="left" nowrap="">
                    <?php echo $transactions; ?>
                </td>
                <td class="dataTableContent"
                    style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; "
                    align="left" nowrap="">
                    <?php echo mh_price($transaction_revenue); ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr style="background-color: #fff;">
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;  border-top: 2px solid #c0c0c0; font-weight: bold;"
                align="left" nowrap="">
                &nbsp;
            </td>
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;  border-top: 2px solid #c0c0c0; font-weight: bold; "
                align="left" nowrap="">
                <?php echo $ga_data['totalsForAllResults']['ga:visits']; ?>
            </td>
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;  border-top: 2px solid #c0c0c0; font-weight: bold; "
                align="left" nowrap="">
                <?php echo $ga_data['totalsForAllResults']['ga:transactions']; ?>
            </td>
            <td class="dataTableContent"
                style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;  border-top: 2px solid #c0c0c0; font-weight: bold; "
                align="left" nowrap="">
                <?php echo mh_price($ga_data['totalsForAllResults']['ga:totalValue']); ?>
            </td>
        </tr>

    </table>

        <?php

    }
}

?>
</td>
</tr>
</table>
</td>
</tr>
</table>