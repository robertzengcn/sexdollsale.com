<?php
/**
 *
 * @package page
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: J_Schilz for Integrated COWOA - 14 April 2007
 */
// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_ACCOUNT_HISTORY_INFO');

if (!isset($_REQUEST['order_id']) || (isset($_REQUEST['order_id']) && !is_numeric($_REQUEST['order_id'])))
  $errorInvalidID=TRUE;

$query_email_address = trim($_REQUEST['query_email_address']);  
if(!isset($query_email_address) || zen_validate_email($query_email_address) == false)
  $errorInvalidEmail=TRUE;

if(!$errorInvalidID && !$errorInvalidEmail)
{

  $customer_info_query = "SELECT customers_email_address, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country,
                                 billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country     
                          FROM   " . TABLE_ORDERS . "
                          WHERE  orders_id = :ordersID";

  $customer_info_query = $db->bindVars($customer_info_query, ':ordersID', $_POST['order_id'], 'integer');
  $customer_info = $db->Execute($customer_info_query);

  if (isset($query_email_address) && $customer_info->fields['customers_email_address'] != $query_email_address && $customer_info->fields['customers_email_address'] != $query_email_address . '.')
    $errorNoMatch=TRUE;
  else
  {

    $statuses_query = "SELECT os.orders_status_name, osh.date_added, osh.comments
                       FROM   " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh
                       WHERE      osh.orders_id = :ordersID
                       AND        osh.orders_status_id = os.orders_status_id
                       AND        os.language_id = :languagesID
                       AND        osh.customer_notified >= 0
                       ORDER BY   osh.date_added";

    $statuses_query = $db->bindVars($statuses_query, ':ordersID', $_POST['order_id'], 'integer');
    $statuses_query = $db->bindVars($statuses_query, ':languagesID', $_SESSION['languages_id'], 'integer');
    $statuses = $db->Execute($statuses_query);

    while (!$statuses->EOF) {

      $statusArray[] = array('date_added'=>$statuses->fields['date_added'],
      'orders_status_name'=>$statuses->fields['orders_status_name'],
      'comments'=>$statuses->fields['comments']);

      $statuses->MoveNext();
    }

    require(DIR_WS_CLASSES . 'order.php');
    $order = new order($_POST['order_id']);
  }
}

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE_1, zen_href_link(FILENAME_ACCOUNT, '', 'SSL'));



// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_ACCOUNT_HISTORY_INFO');
?>