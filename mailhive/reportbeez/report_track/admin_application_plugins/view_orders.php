<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.6
 */

///////////////////////////////////////////////////////////////////////////////
///																		     //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////


mh_define('MH_POPUP', true);

if (!class_exists('order')) {
    include(DIR_WS_CLASSES . 'order.php');
}


$coupon_reporting = false;
if (file_exists(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_coupon_engine.php')) {
    require_once(MH_DIR_FS_CATALOG . 'mailhive/configbeez/config_coupon_engine.php');
    $cfg_ce = new config_coupon_engine();
    if ($cfg_ce->version > 2.2 && $cfg_ce->enabled) {
        $coupon_reporting = true;
    }
}

$message_id = $_GET['message_id'];
$coupon_id = $_GET['coupon_id'];
$order_found = false;

$sql_query_raw = "select distinct o.orders_id
                        from " . TABLE_MAILBEEZ_TRACKING_ORDERS . " tro
                            left join " . TABLE_ORDERS . " o
                            on (tro.orders_id = o.orders_id)
                        where tro.message_id = '" . $message_id . "'";

$sql_query = mh_db_query($sql_query_raw);

if (mh_db_num_rows($sql_query) > 0) {
    while ($query = mh_db_fetch_array($sql_query)) {
        $order_found = true;
        $order = new order($query['orders_id']);
        ?>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr style="background-color: #fff;">
                <td class="dataTableContent" style="border: 1px solid #c0c0c0" align="left" colspan="2">
                    <?php echo MAILBEEZ_REPORT_ORDER; ?>: <b><?php echo $query['orders_id']; ?></b>&nbsp;
                </td>

            </tr>

            <tr style="background-color: #fff;">
                <td class="dataTableContent" style="border: 1px solid #c0c0c0" align="left" width="50%">
                    <?php echo mh_address_format($order->customer['format_id'], $order->billing, 1, '', '<br>'); ?>

                </td>
                <td class="dataTableContent" style="border: 1px solid #c0c0c0" align="right" width="50%">
                    <table border="0" width="100%" cellspacing="0" cellpadding="2">

                        <table border="0" cellspacing="0" cellpadding="2">
                            <?php
                            for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
                                echo '          <tr>' . "\n" .
                                    '            <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                                    '            <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                                    '          </tr>' . "\n";
                            }
                            ?>
                        </table>
                    </table>
                </td>
            </tr>
        </table>
        <hr noshade="noshade" size="1" color="#c0c0c0"/>


    <?php
    }
}
if ($coupon_reporting) {
    //couponbeez::get_orders_by_message_id();
}


if (!$order_found) {
    // message not found
    ?>

    order not found

<?php
}
?>
