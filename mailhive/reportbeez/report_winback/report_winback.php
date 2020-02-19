<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=' . $_GET['module']);

$app_action = (isset($_GET['app_action']) ? $_GET['app_action'] : '');
$msg = '';

if (mh_not_null($app_action)) {
    switch ($app_action) {
        case 'save':
            break;
    }
}

function dbdate($day)
{
    $rawtime = strtotime(-1 * (int)$day . " days");
    $ndate = date("Ymd", $rawtime);
    return $ndate;
}

?>

<?php 
// back to sequence page
echo mb_admin_button($back_url, MH_BUTTON_BACK_REPORTS, '', 'link') . '<br /><br />';
?>
<?php if ($msg != ''): ?>
<?php echo $msg; ?>
<?php endif; ?>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="pageHeading"><?php echo MAILBEEZ_REPORT_WINBACK_TEXT_TITLE; ?></td>
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
                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDERS_ID; ?></td>
                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_EMAIL; ?></td>
                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER_NAME; ?></td>

                            </tr>
<?php

                            //$report_query_numrows = 25;

                            if ($_GET[MH_PAGE_NAME] > 1) $rows = $_GET[MH_PAGE_NAME] * '20' - '20';


                            $winback_delay_days = MAILBEEZ_DASHBOARD_WINBACK_O_METER_DELAY_DAYS;
                            $winback_last_days = MAILBEEZ_DASHBOARD_WINBACK_O_METER_PASSED_DAYS_SKIP;

                            $date_email_passed = dbdate(-1);
                            $date_email_skip = dbdate($winback_last_days + $winback_delay_days);

                            // get a list of customers who got a winback reactivation email in the last e.g. 10 days
                            $winback_email_query_sql = "select distinct customers_id
                                from " . TABLE_MAILBEEZ_TRACKING . "
                             where module like 'winback%'
                                and date_sent <= '" . $date_email_passed . "'
                                and date_sent > '" . $date_email_skip . "' ";
                            $winback_email_query = mh_db_query($winback_email_query_sql);


                            // all customers who got a winback email
                            $winback_email_customers_id_array = array();
                            while ($winback_email = mh_db_fetch_array($winback_email_query)) {
                                $winback_email_customers_id_array[] = $winback_email['customers_id'];
                            }
                            $winback_email_customers_id_list = implode(',', $winback_email_customers_id_array);

                            $winback_email_count = sizeof($winback_email_customers_id_array);


                            // see how many of these customers placed an order in the timeframe
                            $date_skip_orders = dbdate($winback_last_days);
                            $date_passed_orders = dbdate(-1);

                            $report_query_raw = "select customers_id, customers_email_address, customers_name, date_purchased, o.orders_id, ot.text from " . TABLE_ORDERS . " o join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id and class='ot_total') where o.customers_id in (" . $winback_email_customers_id_list . ") and o.date_purchased <= '" . $date_passed_orders . "' and o.date_purchased > '" . $date_skip_orders . "' order by date_purchased desc";

                            if ($winback_email_count > 0) {

                                $report_split = new splitPageResults($_GET[MH_PAGE_NAME], '20', $report_query_raw, $report_query_numrows);
                                $report_query = mh_db_query($report_query_raw);
                                while ($report = mh_db_fetch_array($report_query)) {
                                    $rows++;

                                    if (strlen($rows) < 2) {
                                        $rows = '0' . $rows;
                                    }
                                    ?>
                                    <tr class="dataTableRow"
                                        onmouseover="this.className='dataTableRowOver';this.style.cursor='pointer'"
                                        onmouseout="this.className='dataTableRow'">
                                        <td class="dataTableContent"><?php echo $report['date_purchased']; ?>&nbsp;</td>
                                        <td class="dataTableContent"><?php echo $report['orders_id']; ?>&nbsp;</td>
                                        <td class="dataTableContent"><?php echo $report['text']; ?>&nbsp;</td>
                                        <td class="dataTableContent"><?php echo $report['customers_id']; ?>&nbsp;</td>
                                        <td class="dataTableContent"><?php echo $report['customers_email_address']; ?>
                                            &nbsp;</td>
                                        <td class="dataTableContent"><?php echo $report['customers_name']; ?>&nbsp;</td>
                                    </tr>
                                    <?php

                                }
                            }
                            ?>
                        </table>
                    </td>
                </tr>
                <?php if (is_object($report_split)) { ?>
                <tr>
                    <td colspan="3">
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td class="smallText"
                                    valign="top"><?php echo $report_split->display_count($report_query_numrows, '20', $_GET[MH_PAGE_NAME], MAILBEEZ_TEXT_DISPLAY_NUMBER_OF_ITEMS); ?></td>
                                <td class="smallText"
                                    align="right"><?php echo $report_split->display_links($report_query_numrows, '20', MAX_DISPLAY_PAGE_LINKS, $_GET[MH_PAGE_NAME], mh_get_all_get_params(array(MH_PAGE_NAME))); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
</table>
			