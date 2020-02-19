<?php

/*
 MailBeez Automatic Trigger Email Campaigns
 http://www.mailbeez.com

 Copyright (c) 2010, 2011, 2012 MailBeez

 inspired and in parts based on
 Copyright (c) 2003 osCommerce

 Released under the GNU General Public License
*/

require_once(MH_DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

if (defined('MH_PLATFORM_OSC_WPOS')) {
    global $client;
}

// if (!version_compare(PHP_VERSION, '5.0.0', '<')) {

if (floatval(phpversion()) > 5 && (MAILBEEZ_DASHBOARD_GA_STATUS == 'True')) {
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

    $ga_login_failure = false;
    $ga_profile_id_set = false;
    $invalid_authentication = false;

    // login call from google
    if (isset($_GET['code'])) {
        try {
            $client->authenticate();
            $mh_gh_token = $client->getAccessToken();
            $_SESSION['mh_ga_token'] = $mh_gh_token;

            mh_insert_config_value(array('configuration_title' => 'GA Token',
                'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN',
                'configuration_value' => $mh_gh_token,
                'configuration_description' => 'This token gives access to GA',
                'set_function' => ''
            ), true);
            $back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');
            mh_redirect($back_url);
        } catch (Exception $e) {
//            die('An error occured: ' . $e->getMessage() . "\n");
            $invalid_authentication = true;
            $ga_login_failure = true;
        }
    }

    if (!isset($_SESSION['mh_ga_token']) && MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN != '') {
        $_SESSION['mh_ga_token'] = MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN;
    }
    if (isset($_SESSION['mh_ga_token'])) {
        try {
            $client->setAccessToken($_SESSION['mh_ga_token']);
        } catch (Exception $e) {
            $ga_login_failure = true;
        }
    }
}


class dashboard_ga extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_ga()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_ga';
        $this->module = 'dashboard_ga';
        $this->version = '2.3'; // float value
        $this->required_mb_version = 2.6;
        $this->title = MAILBEEZ_DASHBOARD_GA_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_GA_DESC;
        $this->description_image = '../../common/images/icon_ga_64.png';
        $this->icon = '../../common/images/icon_ga.png';
        $this->enabled = ((MAILBEEZ_DASHBOARD_GA_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_GA_STATUS';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = $this->module;

        $this->admin_action_plugins_path = MH_DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = '';

        $this->sort_order = MAILBEEZ_DASHBOARD_GA_SORT_ORDER;

        // update version if necessary
        $this->check_update($this->module);
    }

    function dbdate($day)
    {
        $rawtime = strtotime(-1 * (int)$day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }

    function getOutput()
    {
        global $client, $service;
        global $ga_login_failure, $ga_profile_id_set, $invalid_authentication;
        if (floatval(phpversion()) < 5) {
            return "The Google Analytics Dashboard Widget requires PHP 5.";
        }


        try {
            $_SESSION['mh_ga_token'] = $client->getAccessToken();
        } catch (Exception $e) {
            echo 'An error occured: ' . $e->getMessage() . "\n";
            $ga_login_failure = true;
        }

        if ($_SESSION['mh_ga_token']) {
            try {
                $profiles = $service->management_profiles->listManagementProfiles("~all", "~all");
                foreach ($profiles['items'] as $profile_item => $profile_data) {
                    if ($profile_data['id'] == MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID) {
                        $ga_profile_id_set = true;
                    }
                }
            } catch (Exception $e) {
                echo 'An error occured: ' . $e->getMessage() . "\n";
                $ga_login_failure = true;
                $invalid_authentication = true;
                unset($_SESSION['mh_ga_token']);
            }
        } else {
            $ga_login_failure = true;
        }


        ob_start();
        ?>
    <link rel="stylesheet" type="text/css" media="print, projection, screen"
          href="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG; ?>mailhive/dashboardbeez/dashboard_ga/dashboard_ga.css">
    <?php echo mh_image(DIR_WS_CATALOG . 'mailhive/dashboardbeez/dashboard_ga/ga.png', '', '120', '22', 'align="right" style="margin: 0px; padding:0px;padding-top:5px;margin-right: 0px;"') ?>
    <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_GA_TITLE?></div>
    <div
        id="WidgetSubTitle"><?php echo sprintf(MAILBEEZ_DASHBOARD_GA_DESC, MAILBEEZ_DASHBOARD_GA_PASSED_DAYS_SKIP); ?>
        <?php if (!$ga_login_failure) { ?>
            | <a
                href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../dashboardbeez/dashboard_ga/admin_application_plugins/logout.php&app_action=logout')?>">logout</a>
            <?php } ?>
    </div>
    <br clear="all">
    <hr noshade size="1"
        style="padding: 0px; margin: -10px; margin-top: -10px; margin-bottom: 0px; color: #c0c0c0">
    <?php
        if ($ga_login_failure) {
            ?>
        <?php echo mh_draw_form('ga', FILENAME_MAILBEEZ, '', defined('MH_FORM_METHOD') ? MH_FORM_METHOD : 'get', ''); ?>
        <?php
            echo mh_draw_hidden_field('app', 'load_app');
            echo mh_draw_hidden_field('app_path', '../dashboardbeez/dashboard_ga/admin_application_plugins/gaoauth.php');
            echo mh_draw_hidden_field('app_action', 'config');
            ?>
        <table border="0" cellspacing="0" cellpadding="2" width="100%">
            <tr valign="top">
                <td><?php echo mh_draw_separator('pixel_trans.gif', '30', '1'); ?></td>
                <td width="100%">
                    <table border="0" cellspacing="0" cellpadding="2" width="100%" style="margin-top: 3px;">
                        <tr>
                            <td colspan="3"
                                style="font-family: Trebuchet MS; font-size: 11px; font-weight: bold; color: #363636; padding-bottom: 7px;">
                                <?php if (MAILBEEZ_REPORT_GA_CLIENT_ID == '' || MAILBEEZ_REPORT_GA_CLIENT_SECRET == '' || $invalid_authentication) { ?>
                                <?php echo TABLE_HEADING_GA_INFO; ?>

                                <?php if ($invalid_authentication) { ?>
                                    <span style="color: #ff0000;">INVALID AUTHENTICATION</span>
                                    <?php } ?>
                                <br/>
                                <?php } else { ?>
                                <?php echo TABLE_HEADING_GA_CONNECT_INFO; ?> <br/>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <?php
                            if (MAILBEEZ_REPORT_GA_CLIENT_ID == '' || MAILBEEZ_REPORT_GA_CLIENT_SECRET == '' || $invalid_authentication) {
                                // show help how to register
                                //https://code.google.com/apis/console?api=plus
                                ?>
                                <td width="100%" align="right" style="font-family: Trebuchet MS; font-size: 12px; font-weight: bold; color: #363636;">
                                    Client
                                    ID: <?php echo mh_draw_input_field('clientid', MAILBEEZ_REPORT_GA_CLIENT_ID, 'size="30" style="width:60%; margin-bottom: 3px;"'); ?>
                                    <br/>

                                    Client
                                    Secret: <?php echo mh_draw_input_field('clientsecret', MAILBEEZ_REPORT_GA_CLIENT_SECRET, 'size="30" style="width:60%; margin-bottom: 3px;"'); ?>
                                    <br/>

                                    Host: <?php echo mh_draw_input_field('host', $_SERVER['SERVER_NAME'], 'size="30" readonly="readonly" style="width:60%; margin-bottom: 3px;"  onclick="this.select();"'); ?>
                                    <br/>

                                    Redirect
                                    URI: <?php echo mh_draw_input_field('rediret URI', MAILBEEZ_REPORT_GA_CLIENT_REDIRECT_URI, 'size="30" readonly="readonly" style="width:60%; margin-bottom: 3px;" onclick="this.select();"'); ?>
                                    <br/>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td width="100%" align="center">

                                    <br/>
                                    <br/>
                                    <br/>
                                    <?php
                                    echo mb_admin_button($client->createAuthUrl(), MAILBEEZ_BUTTON_GA_CONNECT, '', 'link');
                                    ?>

                                </td>
                                <?php
                            }
                            ?>
                            <td><?php echo mh_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                            <?php if (MAILBEEZ_REPORT_GA_CLIENT_ID == '' || MAILBEEZ_REPORT_GA_CLIENT_SECRET == '' || $invalid_authentication) { ?>
                            <td align="left">
                                <br/>
                                <br/>
                                <?php echo mh_image_submit('button_confirm.gif', MAILBEEZ_BUTTON_CONFIRM); ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td colspan="3"
                                style="font-family: Trebuchet MS; font-size: 11px; font-weight: normal; color: #898989; padding-top: 5px;">
                                <?php if (MAILBEEZ_REPORT_GA_CLIENT_ID == '' || MAILBEEZ_REPORT_GA_CLIENT_SECRET == '') { ?>
                                <?php echo TEXT_GA_HELP; ?>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    </form>
                </td>
                <td><?php echo mh_draw_separator('pixel_trans.gif', '30', '1'); ?></td>
            </tr>
        </table>

        <?php

        } else {
            if (!$ga_profile_id_set) {
                ?>
            <div style="overflow: auto; height: 182px; border:0" class="ga">
                <?php echo TEXT_GA_SELECT_PROFILE; ?>
                <br/>
                <blockquote>

                    <?php
                    foreach ($profiles['items'] as $profile_item => $profile_data) {
                        $action_link = mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=' . '../dashboardbeez/dashboard_ga/admin_application_plugins/set_profile_id.php&app_action=set&profile_id=' . $profile_data['id']);
                        ?>
                        <a href="<?php echo $action_link?>"><?php echo $profile_data['name']?>
                            (<?php echo $profile_data['id']; ?>
                            ) </a>
                        <br/>
                        <?php
                    }
                    ?>
                </blockquote>
            </div>
            <?php
            } else {

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


                } else {
//                    echo "No";
                }



                //$ga->requestReportData(ga_profile_id,array('browser','browserVersion'),array('pageviews','visits'));
//                $ga->requestReportData(MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID, array('source', 'medium', 'campaign'), array('visits', 'transactionRevenue', 'transactions', 'totalValue'), '-visits', 'source == MailBeez', $ga_start_date, $ga_end_date);
                ?>
            <div style="position: relative; margin-right:1px;overflow: hidden; height: 182px; border:0"
                 class="ga">
                <table border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr valign="top">
                        <td align="left" width="50%">
                            <div id="ga_pie"
                                 style="width:120px; height: 120px; margin-top: 10px; margin-left: 25px; margin-bottom: 11px;"></div>
                        </td>
                        <td align="left" valign="middle" width="50%"
                            style="font-family: Trebuchet MS; font-size: 12px; font-weight: bold; color: #363636;"
                            >
                            <b><?php echo MAILBEEZ_DASHBOARD_GA_TEXT_TOTAL_VISITS; ?>
                                :</b> <?php echo $ga_data['totalsForAllResults']['ga:visits'] ?>
                            <br/>
                            <b><?php echo MAILBEEZ_DASHBOARD_GA_TEXT_TOTAL_REVENUE; ?>
                                :</b> <?php echo mh_price($ga_data['totalsForAllResults']['ga:transactionRevenue']); ?>
                            <br/>
                            <br/>
                            <a href="<?php echo MAILBEEZ_DASHBOARD_GA_TEXT_DIFF_URL; ?>"
                               target="_blank"><?php echo MAILBEEZ_DASHBOARD_GA_TEXT_DIFF; ?> &gt;</a>
                        </td>
                    </tr>
                </table>
                <div class="ga_pie_legend_outer"
                     style="position: absolute; width: 98%; margin-top: -23px;z-index: 100; height: 38px;"><a
                    href="<?php echo mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../reportbeez/report_ga/report_ga.php&popup=true')?>"
                    class="ceebox" rel="iframe width:650">
                    <div id="ga_pie_legend" style="float: left;"></div>
                    <div
                        style="float: right; margin-right: 10px;margin-top: 20px;"><?php echo BUTTON_GA_VIEW; //echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../reportbeez/report_ga/report_ga.php&popup=true'), BUTTON_GA_VIEW, '', 'popup', 'link'); ?></div>
                </a>
                </div>

                <script type="text/javascript"
                        src="<?php echo MH_CATALOG_SERVER . MH_DIR_WS_CATALOG; ?>mailhive/common/js/flot/jquery.flot.pie.min.js"></script>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        var data = [];
                        <?php
                        $i = 0;
                      if (sizeof($ga_data['rows']) > 0) {

                        foreach ($ga_data['rows'] as $result) {
                            $campaign_name = $result['2'];
                            $transaction_revenue = $result['4'];
                            $visits = $result['3'];

                            ?>
                            data[<?php echo $i++; ?>] = { label:"<?php echo str_replace('"', '', $campaign_name) ?>", data: <?php echo ($transaction_revenue == 0)
                                ? $visits : $transaction_revenue; ?> }
                            <?php
                            }
                          ?>
                          <?php
                        }
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
                                        },
                                        offset:{
                                            top:0,
                                            left:'auto'
                                        }
                                    }
                                },
                                grid:{
                                    hoverable:true,
                                    clickable:true
                                },
                                legend:{
                                    noColumns:3,
                                    position:'nw',
                                    backgroundOpacity:0.5,
                                    container:'div#ga_pie_legend',
                                    margin:[140, 10]
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

            </div>

            <?php

            }
        }

        $output = ob_get_contents();
        ob_end_clean();


        return $output;
    }

    function timestamp($offset_day)
    {
        $rawtime = strtotime(-1 * (int)$offset_day . " days");
        return date("Y-m-d", $rawtime);
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_GA_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show Google Analytics Summary',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_GA_STATUS',
            'configuration_value' => 'True',
            'configuration_description' => 'Do you want to show the Google Analytics Summary on the dashboard? (recommended)',
            'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
        ));

        mh_insert_config_value(array('configuration_title' => 'Count this number of past days:',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_GA_PASSED_DAYS_SKIP',
            'configuration_value' => '30',
            'configuration_description' => 'number of past days to use for analytics',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
            'configuration_key' => 'MAILBEEZ_DASHBOARD_GA_SORT_ORDER',
            'configuration_value' => '60',
            'configuration_description' => 'Sort order of display. Lowest is displayed first.',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Profile Id',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_PROFILE_ID',
            'configuration_value' => '',
            'configuration_description' => '',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Token',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN',
            'configuration_value' => '',
            'configuration_description' => 'This token gives access to GA',
            'set_function' => ''
        ));
        $this->update(3);
    }

    function update($installed_version)
    {

        if ($installed_version < 2.2) {
            // reset token
            mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN',
                'configuration_value' => ''
            ), true);
        }

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Client ID',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_CLIENT_ID',
            'configuration_value' => '',
            'configuration_description' => 'client ID',
            'set_function' => ''
        ));

        mh_insert_config_value(array('configuration_title' => 'Google Analytics Client Secret',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_CLIENT_SECRET',
            'configuration_value' => '',
            'configuration_description' => 'client secret',
            'set_function' => ''
        ));


        // todo: make sure, the link is w/o session id
        mh_insert_config_value(array('configuration_title' => 'Google Analytics Client Redirect URI',
            'configuration_key' => 'MAILBEEZ_REPORT_GA_CLIENT_REDIRECT_URI',
            'configuration_value' => mh_href_link(FILENAME_MAILBEEZ, 'tab=home'),
            'configuration_description' => 'client redirect URI',
            'set_function' => ''
        ));

        dashboardbeez::update($this->module, $this->version);
    }

    function remove()
    {
        unset($_SESSION['mh_ga_token']);
        dashboardbeez::remove();
    }


    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_GA_STATUS', 'MAILBEEZ_DASHBOARD_GA_PASSED_DAYS_SKIP', 'MAILBEEZ_DASHBOARD_GA_SORT_ORDER', 'MAILBEEZ_REPORT_GA_ACCOUNT_TOKEN', 'MAILBEEZ_REPORT_GA_CLIENT_ID', 'MAILBEEZ_REPORT_GA_CLIENT_SECRET', 'MAILBEEZ_REPORT_GA_CLIENT_REDIRECT_URI');
    }

}

?>