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

$message_found = false;
$message_id = $_GET['message_id'];

$sql_query_raw = "select *
                        from " . TABLE_MAILBEEZ_TRACKING_CLICKS . "
                        where message_id = '" . $message_id . "'";

$sql_query = mh_db_query($sql_query_raw);

if (mh_db_num_rows($sql_query) > 0) {
    ?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr style="background-color: #fff;">
        <td class="dataTableContent" style="border: 1px solid #c0c0c0; font-weight: bold;" align="left"><?php echo MAILBEEZ_REPORT_DATE; ?></td>
        <td class="dataTableContent" style="border: 1px solid #c0c0c0; font-weight: bold;" align="left"><?php echo MAILBEEZ_REPORT_LINK_URL; ?></td>
    </tr>
    <?php
    while ($query = mh_db_fetch_array($sql_query)) {
        $message_found = true;
        ?>
        <tr style="background-color: #fff;">
            <td class="dataTableContent" style="border: 1px solid #c0c0c0" align="left" nowrap="">
                <?php echo $query['date_record']; ?>
            </td>
            <td class="dataTableContent" style="border: 1px solid #c0c0c0" align="left">
                <?php echo $query['url']; ?>
            </td>
        </tr>

        <?php
    }
    ?>
</table>
<?php
}

if (!$message_found) {
    // message not found
    ?>
<?php echo MAILBEEZ_REPORT_NO_CLICK_TRACKING; ?>
<br/>
<?php
}
?>
