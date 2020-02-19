<?php

/*
 *
 *
 *
 * V2.7.7
 */

$popup = false;
if (isset($_GET['popup']) && mh_not_null($_GET['popup'])) {
    mh_define('MH_POPUP', true);
    $popup = true;
}

if ($_GET['back'] == 'dashboard') {
    $back_url = mh_href_link(FILENAME_MAILBEEZ, '');
    $back_button = MH_BUTTON_BACK_DASHBOARD;
} else {
    $back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=' . $_GET['module']);
    $back_button = MH_BUTTON_BACK_REPORTS;
}

$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
$msg = '';

if (mh_not_null($app_action)) {
    switch ($app_action) {
        case 'save':
            break;
    }
}

$coupon_reporting = false;
if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_coupon_engine.php')) {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_coupon_engine.php');
    $cfg_ce = new config_coupon_engine();
    if ($cfg_ce->version > 2.2 && $cfg_ce->enabled) {
        $coupon_reporting = true;
    }
}
$email_archive = false;
if (defined('MAILBEEZ_EMAIL_ARCHIVE_STATUS') && MAILBEEZ_EMAIL_ARCHIVE_STATUS == 'True') {
    $email_archive = true;
}


// todo
// exclude_list as parameter (exclude specified modules)
// module_list as parameter (only show for specified modules)


?>

<?php
// back
if (!$popup) {
    echo mb_admin_button($back_url, $back_button, '', 'link') . '<br /><br />';
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
          <td class="pageHeading"><?php echo MAILBEEZ_REPORT_TRACK_TEXT_TITLE; ?></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODULE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ITERATION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_EMAIL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_LNG; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MODE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CHANNEL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BATCH_ID; ?></td>
                  <?php if (MH_OPENTRACKER_ENABLED) { ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPENED; ?></td>
                  <?php } ?>
                  <?php if (MH_CLICKTRACKER_ENABLED) { ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CLICKED; ?></td>
                  <?php } ?>
                  <?php if (MH_ORDERTRACKER_ENABLED) { ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDERED; ?></td>
                  <?php } ?>
                  <?php if ($coupon_reporting) { ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUPON; ?></td>
                  <?php } ?>
                  <?php if (MH_BOUNCEHANDLING_ENABLED) { ?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BOUNCED; ?>
                    <?php if (MAILBEEZ_BOUNCEHIVE_MSG_LOG == 'True') { ?>
                        <?php } ?>
                </td>
                  <?php } ?>
              </tr>
                <?php

//$report_query_numrows = 25;

                if ($_GET[MH_PAGE_NAME] > 1) $rows = $_GET[MH_PAGE_NAME] * MH_SPLITPAGE_NUM - MH_SPLITPAGE_NUM;

                $filter_sql = '';

                if (isset($_GET['filter'])) {
                    switch ($_GET['filter']) {
                        case 'bounce':
                            $filter_sql = 'where bounce_status = "H" or  bounce_status = "S"';
                            break;
                        case 'opened':
                            $filter_sql = 'where opened > "2000-01-01 00:00:00" ';
                            break;
                        case 'clicked':
                            $filter_sql = 'where clicked > "2000-01-01 00:00:00" ';
                            break;
                        case 'ordered':
                            $filter_sql = 'where ordered > "2000-01-01 00:00:00" ';
                            break;
                    }
                }


                if (isset($_GET['modules_exclude'])) {
                    if ($filter_sql == '') {
                        $filter_sql = "where module not in () ";
                    }
                } elseif (isset($_GET['modules_include'])) {
                    if ($filter_sql == '') {
                        $filter_sql = "where module in () ";
                    }
                }



                $report_query_raw = "select * from " . TABLE_MAILBEEZ_TRACKING . " " . $filter_sql . " order by autoemail_id DESC";


                $report_split = new splitPageResults($_GET[MH_PAGE_NAME], MH_SPLITPAGE_NUM, $report_query_raw, $report_query_numrows);
                $report_query = mh_db_query($report_query_raw);
                while ($report = mh_db_fetch_array($report_query)) {
                    $rows++;

                    if (strlen($rows) < 2) {
                        $rows = '0' . $rows;
                    }
                    $click_action_archive = '';
                    $click_action_cis = '';

                    if ($email_archive) {
                        $click_url = mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_email_archive/admin_application_plugins/view_email_archive.php&popup=true&message_id=' . $report['message_id']);
                        $click_action_archive = 'onclick="openReportPopup(\'' . $click_url . '\')"';
                    }

                    if (defined('MAILBEEZ_INSIGHT_VIEW_STATUS') && MAILBEEZ_INSIGHT_VIEW_STATUS == 'True') {
                        $click_action_cis = 'onclick="return openCISPopup(\'' . $report['customers_id'] . '\', \'view_overview\');"';
                    }


                    ?>
                  <tr class="dataTableRow"
                      onmouseover="this.className='dataTableRowOver';this.style.cursor='pointer'"
                      onmouseout="this.className='dataTableRow'"
                      >
                    <td class="dataTableContent" <?php echo $click_action_archive; ?> ><?php echo $report['autoemail_id']; ?></td>
                    <td class="dataTableContent" <?php echo $click_action_archive; ?> ><?php echo $report['date_sent']; ?>&nbsp;</td>
                    <td class="dataTableContent" <?php echo $click_action_archive; ?> ><?php echo $report['module']; ?></td>
                    <td class="dataTableContent" <?php echo $click_action_archive; ?> ><?php echo $report['iteration']; ?></td>
                    <td class="dataTableContent" <?php echo $click_action_cis; ?> ><?php echo $report['customers_id']; ?>&nbsp;</td>
                    <td class="dataTableContent" <?php echo $click_action_cis; ?> >
                        <?php
                        if ($report['mobile']) {
                            ?>
                            <img src="<?php echo MH_CATALOG_SERVER . MH_ADMIN_DIR_WS_IMAGES ?>icon_mobile_32.png" width="12"
                                 height="12" align="absmiddle" hspace="0" border="0" alt="mobile"><?php
                        }

                        ?><?php echo $report['customers_email']; ?>&nbsp;</td>
                    <td class="dataTableContent" <?php echo '' ?> ><?php echo $report['language_id']; ?>&nbsp;</td>
                    <td class="dataTableContent" <?php echo '' ?> ><?php echo ($report['simulation'] > 0)
                        ? MAILBEEZ_SIMULATION_TAG : 'PROD'; ?>&nbsp;</td>
                    <td class="dataTableContent"><?php echo $report['channel']; ?></td>
                    <td class="dataTableContent"><?php echo $report['batch_id']; ?></td>

                      <?php if (MH_OPENTRACKER_ENABLED) { ?>
                    <td class="dataTableContent"
                        bgcolor="<?php echo ($report['opened'] != '0000-00-00 00:00:00' && $report['opened'] != '') ? MH_COLOR_BLUE : ''; ?>"><?php if ($report['opened'] != '0000-00-00 00:00:00' && $report['opened'] != '') { ?>
                        <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../reportbeez/report_track/admin_application_plugins/view_opens.php&popup=true&message_id=' . $report['message_id']), MAILBEEZ_REPORT_DETAILS, '', 'popup', 'link') ?>
                        <?php } ?>
                    </td>
                      <?php } ?>

                      <?php if (MH_CLICKTRACKER_ENABLED) { ?>
                    <td class="dataTableContent"
                        bgcolor="<?php echo ($report['clicked'] != '0000-00-00 00:00:00' && $report['clicked'] != '') ? MH_COLOR_ORANGE : ''; ?>"><?php if ($report['clicked'] != '0000-00-00 00:00:00' && $report['clicked'] != '') { ?>

                        <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../reportbeez/report_track/admin_application_plugins/view_clicks.php&popup=true&message_id=' . $report['message_id']), MAILBEEZ_REPORT_DETAILS, '', 'popup', 'link') ?>
                        <?php } ?>
                    </td>
                      <?php } ?>

                      <?php if (MH_ORDERTRACKER_ENABLED) { ?>
                    <td class="dataTableContent"
                        bgcolor="<?php echo (($report['ordered'] != '0000-00-00 00:00:00' && $report['ordered'] != '') ||
                            ($coupon_reporting && $report['cpn_orders_id'] > 0)) ? MH_COLOR_GREEN : ''; ?>">
                        <?php if (($report['ordered'] != '0000-00-00 00:00:00' && $report['ordered'] != '') ||
                        ($coupon_reporting && $report['cpn_orders_id'] > 0)
                    ) {
                        ?>
                        <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../reportbeez/report_track/admin_application_plugins/view_orders.php&popup=true&message_id=' . $report['message_id']), MAILBEEZ_REPORT_DETAILS, '', 'popup', 'link') ?>
                        <?php } ?>
                    </td>
                      <?php } ?>

                      <?php if ($coupon_reporting) { ?>
                    <td class="dataTableContent"
                        bgcolor="<?php echo ($report['coupon_redeemed'] > 0) ? MH_COLOR_GREEN : ''; ?>">
                        <?php if ($report['coupon_id'] > 0 || $report['coupon_id'] != '') { ?>
                        <?php
                        // show coupon code
                        // echo couponbeez::get_coupon_code_by_id($report['coupon_id']);
                        ?>
                        <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_coupon_engine/admin_application_plugins/view_coupon.php&popup=true&coupon_id=' . $report['coupon_id']), MAILBEEZ_REPORT_DETAILS, '', 'popup', 'link') ?>
                        <?php } ?>

                    </td>
                      <?php } ?>

                      <?php if (MH_BOUNCEHANDLING_ENABLED) { ?>
                    <td class="dataTableContent"
                        bgcolor="<?php echo ($report['bounce_status'] != '') ? MH_COLOR_PINK : ''; ?>"><?php echo $report['bounce_status']; ?>
                        <?php if (MAILBEEZ_BOUNCEHIVE_MSG_LOG == 'True' && $report['bounce_status'] != '') { ?>
                            <?php echo mb_admin_button(mh_href_link(FILENAME_MAILBEEZ, 'app=load_app&app_path=../configbeez/config_bouncehive_advanced/admin_application_plugins/view_msg.php&popup=true&autoemail_id=' . $report['autoemail_id']), MAILBEEZ_REPORT_DETAILS, '', 'popup', 'link') ?>
                            <?php } ?>
                    </td>
                      <?php } ?>
                  </tr>
                    <?php
                }
                ?>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"
                    valign="top"><?php echo $report_split->display_count($report_query_numrows, MH_SPLITPAGE_NUM, $_GET[MH_PAGE_NAME], MAILBEEZ_TEXT_DISPLAY_NUMBER_OF_ITEMS); ?></td>
                <td class="smallText"
                    align="right"><?php echo $report_split->display_links($report_query_numrows, MH_SPLITPAGE_NUM, MAX_DISPLAY_PAGE_LINKS, $_GET[MH_PAGE_NAME], mh_get_all_get_params(array(MH_PAGE_NAME))); ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
			