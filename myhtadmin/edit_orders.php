<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+

  global $db;
  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

    $oID = zen_db_prepare_input($_GET['oID']);
  $step = zen_db_prepare_input($_POST['step']);
  $add_product_categories_id = zen_db_prepare_input($_POST['add_product_categories_id']);
  $add_product_products_id = zen_db_prepare_input($_POST['add_product_products_id']);
  $add_product_quantity = zen_db_prepare_input($_POST['add_product_quantity']);



  // New "Status History" table has different format.
  $OldNewStatusValues = (zen_field_exists(TABLE_ORDERS_STATUS_HISTORY, "old_value") && zen_field_exists(TABLE_ORDERS_STATUS_HISTORY, "new_value"));
  $CommentsWithStatus = zen_field_exists(TABLE_ORDERS_STATUS_HISTORY, "comments");
  $SeparateBillingFields = zen_field_exists(TABLE_ORDERS, "billing_name");

  // Optional Tax Rate/Percent
  $AddShippingTax = "0.0"; // e.g. shipping tax of 17.5% is "17.5"

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = $db -> Execute("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$_SESSION['languages_id'] . "'");
#  while ($orders_status = zen_db_fetch_array($orders_status_query)) {
  while (!$orders_status_query -> EOF) {
    $orders_statuses[] = array('id' => $orders_status_query->fields['orders_status_id'],
                               'text' => $orders_status_query->fields['orders_status_name']);

    $orders_status_array[$orders_status_query->fields['orders_status_id']] = $orders_status_query->fields['orders_status_name'];

//    $orders_status_array[$orders_status->fields['orders_status_id']] = $orders_status->fields['orders_status_name'];

    $orders_status_query -> MoveNext();
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
$order_query = $db -> Execute("select products_id, products_quantity from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################


  if (zen_not_null($action)) {
    switch ($action) {

	// Update Order
	case 'update_order':

		$oID = zen_db_prepare_input($_GET['oID']);
		$order = new order($oID);
		$status = zen_db_prepare_input($_POST['status']);
        $comments = zen_db_prepare_input($_POST['comments']);
//print_r($_POST);
		// Update Order Info
		$UpdateOrders = "update " . TABLE_ORDERS . " set
			customers_name = '" . zen_db_input(stripslashes($_POST['update_customer_name'])) . "',
			customers_company = '" . zen_db_input(stripslashes($_POST['update_customer_company'])) . "',
			customers_street_address = '" . zen_db_input(stripslashes($_POST['update_customer_street_address'])) . "',
			customers_suburb = '" . zen_db_input(stripslashes($_POST['update_customer_suburb'])) . "',
			customers_city = '" . zen_db_input(stripslashes($_POST['update_customer_city'])) . "',
			customers_state = '" . zen_db_input(stripslashes($_POST['update_customer_state'])) . "',
			customers_postcode = '" . zen_db_input($_POST['update_customer_postcode']) . "',
			customers_country = '" . zen_db_input(stripslashes($_POST['update_customer_country'])) . "',
			customers_telephone = '" . zen_db_input($_POST['update_customer_telephone']) . "',
			customers_email_address = '" . zen_db_input($_POST['update_customer_email_address']) . "',";

		if($SeparateBillingFields){
		$UpdateOrders .= "billing_name = '" . zen_db_input(stripslashes($_POST['update_billing_name'])) . "',
			billing_company = '" . zen_db_input(stripslashes($_POST['update_billing_company'])) . "',
			billing_street_address = '" . zen_db_input(stripslashes($_POST['update_billing_street_address'])) . "',
			billing_suburb = '" . zen_db_input(stripslashes($_POST['update_billing_suburb'])) . "',
			billing_city = '" . zen_db_input(stripslashes($_POST['update_billing_city'])) . "',
			billing_state = '" . zen_db_input(stripslashes($_POST['update_billing_state'])) . "',
			billing_postcode = '" . zen_db_input($_POST['update_billing_postcode']) . "',
			billing_country = '" . zen_db_input(stripslashes($_POST['update_billing_country'])) . "',";
		}
//支払い方法を追記
		$UpdateOrders .= "delivery_name = '" . zen_db_input(stripslashes($_POST['update_delivery_name'])) . "',
			delivery_company = '" . zen_db_input(stripslashes($_POST['update_delivery_company'])) . "',
			delivery_street_address = '" . zen_db_input(stripslashes($_POST['update_delivery_street_address'])) . "',
			delivery_suburb = '" . zen_db_input(stripslashes($_POST['update_delivery_suburb'])) . "',
			delivery_city = '" . zen_db_input(stripslashes($_POST['update_delivery_city'])) . "',
			delivery_state = '" . zen_db_input(stripslashes($_POST['update_delivery_state'])) . "',
			delivery_postcode = '" . zen_db_input($_POST['update_delivery_postcode']) . "',
			delivery_country = '" . zen_db_input(stripslashes($_POST['update_delivery_country'])) . "',
			payment_method = '" . zen_get_paymod_name(zen_db_input($_POST['update_info_payment_method'])) . "',
			payment_module_code = '" . zen_db_input($_POST['update_info_payment_method']) . "',
			cc_type = '" . zen_db_input($_POST['update_info_cc_type']) . "',
			cc_owner = '" . zen_db_input($_POST['update_info_cc_owner']) . "',";


		if(substr($update_info_cc_number,0,8) != "(Last 4)")
		$UpdateOrders .= "cc_number = '". $_POST['update_info_cc_number']. "',";

		$UpdateOrders .= "cc_expires = '". $_POST['update_info_cc_expires']. "',
			orders_status = '" . zen_db_input($status) . "'";

		if(!$CommentsWithStatus)
		{
//			$UpdateOrders .= ", comments = '" . zen_db_input($comments) . "'";
		}

		$UpdateOrders .= " where orders_id = '" . zen_db_input($oID) . "';";

		$db -> Execute($UpdateOrders);
		$order_updated = true;


        	$check_status = $db -> Execute("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        	#$check_status = zen_db_fetch_array($check_status_query);

		// Update Status History & Email Customer if Necessary
/////////////////////////////////////////////////////////////////////
//20080728 デフォルトの動作が実状にそぐわないため、ステータスが同一の場合でもコメントの通知を行えるように下記分岐をコメントアウト
//		if ($order->info['orders_status'] != $status){
			// Notify Customer
          		$customer_notified = '0';
			if (isset($_POST['notify']) && ($_POST['notify'] == 'on')){
			$notify_comments = '';

//        $comments = zen_db_prepare_input($_POST['comments']);

			if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on') && zen_not_null($comments)) {
              $notify_comments = EMAIL_TEXT_COMMENTS_UPDATE . "\n". $comments . "\n\n";
			  }
//			  $notify_comments = strip_tags($notify_comments);
//送信メールのコンテンツフォーマット
			  $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" .
			  EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n\n" .
			  EMAIL_TEXT_INVOICE_URL . ' ' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n\n" .
			  EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($check_status->fields['date_purchased']) . "\n\n" .
			  strip_tags($notify_comments) .
			  EMAIL_TEXT_STATUS_UPDATED . sprintf(EMAIL_TEXT_STATUS_LABEL, $orders_status_array[$status]) .
			  EMAIL_TEXT_STATUS_PLEASE_REPLY .
			  STORE_NAME . "\n" .
			  EMAIL_FROM;
//HTMLメール送信ロジック抜けを修正 20080827
			$html_msg['EMAIL_CUSTOMERS_NAME']    = $check_status->fields['customers_name'];
			$html_msg['EMAIL_TEXT_ORDER_NUMBER'] = EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID;
			$html_msg['EMAIL_TEXT_INVOICE_URL']  = '<a href="' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') .'">'.str_replace(':','',EMAIL_TEXT_INVOICE_URL).'</a>';
			$html_msg['EMAIL_TEXT_DATE_ORDERED'] = EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($check_status->fields['date_purchased']);
			$html_msg['EMAIL_TEXT_STATUS_COMMENTS'] = nl2br($notify_comments);
			$html_msg['EMAIL_TEXT_STATUS_UPDATED'] = str_replace('\n','', EMAIL_TEXT_STATUS_UPDATED);
			$html_msg['EMAIL_TEXT_STATUS_LABEL'] = str_replace('\n','', sprintf(EMAIL_TEXT_STATUS_LABEL, $orders_status_array[$status] ));
			$html_msg['EMAIL_TEXT_NEW_STATUS'] = '';
			$html_msg['EMAIL_TEXT_STATUS_PLEASE_REPLY'] = str_replace('\n','', EMAIL_TEXT_STATUS_PLEASE_REPLY);

//メール送信
//宛先氏名、宛先アドレス、件名（#注文番号）、コンテンツ、店名、返信アドレス
            zen_mail($check_status->fields['customers_name'],
            $check_status->fields['customers_email_address'],
            EMAIL_TEXT_SUBJECT . ' #' . $oID,
            $email,
            STORE_NAME,
            EMAIL_FROM, $html_msg, 'order_status');

		if (SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO_STATUS == '1' and SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO != '') {

//20080728 管理者宛にコピーが送られていなかったバグの修正。顧客あてと同一の処理をしてしまっていたorz
              zen_mail(STORE_OWNER,
              SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO,
              SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO_SUBJECT . ' ' . EMAIL_TEXT_SUBJECT . ' #' . $oID,
              $email,
              STORE_NAME,
              EMAIL_FROM);


		}
			  $customer_notified = '1';
			}

			// "Status History" table has gone through a few
			// different changes, so here are different versions of
			// the status update.

			// NOTE: Theoretically, there shouldn't be a
			//       orders_status field in the ORDERS table. It
			//       should really just use the latest value from
			//       this status history table.

			if($CommentsWithStatus){
			$db -> Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
				(orders_id, orders_status_id, date_added, customer_notified, comments)
				values ('" . zen_db_input($oID) . "', '" . zen_db_input($status) . "', now(), " . zen_db_input($customer_notified) . ", '" . zen_db_input($comments)  . "')");
			}else{
			$db -> Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
				(orders_id, orders_status_id, date_added, customer_notified, comments)
				values ('" . zen_db_input($oID) . "', '" . zen_db_input($status) . "', now(), " . zen_db_input($customer_notified) . ", '" . zen_db_input($comments)  . "')");
			}
//		}

		// Update Products
		$RunningSubTotal = 0;
		$RunningTax = 0;
        $update_products = $_POST['update_products'];
		foreach($update_products as $orders_products_id => $products_details){
			// Update orders_products Table
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			#$order = zen_db_fetch_array($order_query);
			if ($products_details["qty"] != $order_query->fields['products_quantity']){
				$differenza_quantita = ($products_details["qty"] - $order_query->fields['products_quantity']);
					$db -> Execute("update " . TABLE_PRODUCTS . " set products_quantity = products_quantity - " . $differenza_quantita . ", products_ordered = products_ordered + " . $differenza_quantita . " where products_id = '" . (int)$order_query->fields['products_id'] . "'");
			}
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			if($products_details["qty"] > 0){
				$Query = "update " . TABLE_ORDERS_PRODUCTS . " set
					products_model = '" . $products_details["model"] . "',
					products_name = '" . str_replace("'", "&#39;", $products_details["name"]) . "',
					final_price = '" . $products_details["final_price"] . "',
					products_tax = '" . $products_details["tax"] . "',
					products_quantity = '" . $products_details["qty"] . "',
					products_prid = '" . (int)$order_query->fields['products_id'] . "'
					where orders_products_id = '$orders_products_id'
					";
				$db -> Execute($Query);
/*
 * ****************************************************************
 * bof 商品ごとの消費税計算 20080923
 * ****************************************************************
 */
				// Update Tax and Subtotals
				// 四捨五入
				if(DISPLAY_PRICE_WITH_TAX_ADMIN == 'false'){
					for($i=0; $i<$products_details["qty"]; $i++){
					$RunningSubTotal += $products_details["final_price"];
//					$RunningTax += (($products_details["tax"]/100) * $products_details["final_price"]);
					}
				}else{
					//消費税計算を「個数×単価×税」ではなく、丁寧に「単価×税を個数分ループ」で取得
					for($i=0; $i<$products_details["qty"]; $i++){
					$RunningSubTotal += round($products_details["final_price"] * (1 + ($products_details["tax"]/100)));
//					$RunningTax += round(($products_details["tax"]/100) * $products_details["final_price"]);
					}
				}
//消費税項目用だけ上記ループ内で取ってはいけない。デフォルトの処理結果と誤差が発生する。
				$RunningTax += round(($products_details["tax"]/100) * ($products_details["qty"] * $products_details["final_price"]));
/*
 * ****************************************************************
 * eof 商品ごとの消費税計算 20080923
 * ****************************************************************
 */
				// Update Any Attributes
				if(IsSet($products_details[attributes])){
					foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details){
						$Query = "update " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
							products_options = '" . $attributes_details["option"] . "',
							products_options_values = '" . $attributes_details["value"] . "'
							where orders_products_attributes_id = '$orders_products_attributes_id';";
						$db -> Execute($Query);
					}
				}
			}else{
				// 0 Quantity = Delete
				$Query = "delete from " . TABLE_ORDERS_PRODUCTS . " where orders_products_id = '$orders_products_id';";
				$db -> Execute($Query);
					//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
				#$order = zen_db_fetch_array($order_query);
/*
 * *****************************************************************
 * 在庫減算処理 デフォルトのまま。不具合あるか要監視。修正 20080823
 * *****************************************************************
 */

				if ($products_details["qty"] != $order_query->fields['products_quantity']){
						$differenza_quantita = ($products_details["qty"] -  $order_query->fields['products_quantity']);
						$db -> Execute("update " . TABLE_PRODUCTS . "
										set
										products_quantity = products_quantity - " . $differenza_quantita . ",
										products_ordered = products_ordered + " . $differenza_quantita . "
										where products_id = '" . (int)$order_query->fields['products_id'] . "'");
					}

					//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
				$Query = "delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_products_id = '$orders_products_id';";
				$db -> Execute($Query);
			}
         $order_query -> MoveNext();
		}

		// Shipping Tax
            $update_totals = $_POST['update_totals'];
			foreach($update_totals as $total_index => $total_details){
				extract($total_details,EXTR_PREFIX_ALL,"ot");
				if($ot_class == "ot_shipping")
				{
					$RunningTax += (($AddShippingTax / 100) * $ot_value);
				}
			}

		// Update Totals

			$RunningTotal = 0;
			$sort_order = 0;

			// Do pre-check for Tax field existence
				$ot_tax_found = 0;
				foreach($update_totals as $total_details){
					extract($total_details,EXTR_PREFIX_ALL,"ot");
					if($ot_class == "ot_tax")
					{
						$ot_tax_found = 1;
						break;
					}
				}

			foreach($update_totals as $total_index => $total_details){
				extract($total_details,EXTR_PREFIX_ALL,"ot");

				if( trim(strtolower($ot_title)) == "tax" || trim(strtolower($ot_title)) == "tax:" ){
					if($ot_class != "ot_tax" && $ot_tax_found == 0){
						// Inserting Tax
						$ot_class = "ot_tax";
						$ot_value = "x"; // This gets updated in the next step
						$ot_tax_found = 1;
					}
				}

				if( trim($ot_title) && trim($ot_value)){
					$sort_order++;

					// Update ot_subtotal, ot_tax, and ot_total classes
						if($ot_class == "ot_subtotal"){
							if(DISPLAY_PRICE_WITH_TAX_ADMIN == 'false'){
							$ot_value = $RunningSubTotal;
							}else{
							$ot_value = $RunningSubTotal;
							}
						}

						if($ot_class == "ot_tax"){
							if(DISPLAY_PRICE_WITH_TAX_ADMIN == 'false'){
							$ot_value = round($RunningTax);
							}else{
							$ot_value = $RunningTax;
							}
						// echo "ot_value = $ot_value<br>\n";
						}

						if($ot_class == "ot_total"){
							if(DISPLAY_PRICE_WITH_TAX_ADMIN == 'false'){
							$ot_value = $RunningTotal;
							}else{
							$ot_value = $RunningTotal-$RunningTax;
							}
						}
//						$ot_value = $RunningTotal-$RunningTax;

					// Set $ot_text (display-formatted value)
						// $ot_text = "\$" . number_format($ot_value, 2, '.', ',');

						$order = new order($oID);
//あんどぷらす版ポイントモジュール
//"円"付記がよろしくないから"P"付記に分岐
//$RunningTotalに取得ポイント、総ポイントを算入しないように切り分け（モジュールの配置順に関わらず処理できるように）
//$RunningTotalに利用ポイントは算入

/* *******************************************************************************************************************
 * ポイントイベント：還元率取得 20090101
 * セッションで管理してるからこの取得はかなりめんどい というか正確にいかなるときも取得するのは無理かも
 * イベントを削除もしくは名前変更、内容変更している場合が取得に難あり
 * 受注時に適用したイベント等セッションでしか管理していない情報をDBテーブルに格納するように変更しないといけない
 * ただし、アップデートが多すぎるのでしばらく様子見。そもそもポイントイベントがあまり使われていない気がする。
 * *******************************************************************************************************************
 */
//まずorder_totalの文字列から適用イベント（還元率関連）と還元率を取得 ※ここに難あり
$check_point_event_reduction = $db->Execute("select ot.title from ".TABLE_ORDERS." o, ".TABLE_ORDERS_TOTAL." ot 
											where o.orders_id = '".(int)$_GET['oID']."'
											and o.orders_id = ot.orders_id
											and ot.class = 'ot_points_use'
											");
//titleから還元率を取得してみる
$reduction_rate_st = mb_strpos($check_point_event_reduction->fields['title'], '*');
$reduction_rate_end = mb_strpos($check_point_event_reduction->fields['title'], ')');
//還元率
$reduction_rate = mb_substr($check_point_event_reduction->fields['title'], $reduction_rate_st+1, ($reduction_rate_end-$reduction_rate_st-1));
//echo $reduction_rate;
if($reduction_rate == 0){
$reduction_rate = 1;
}

/* ************************************************************************
 * あんどぷらす版会員ランクモジュールアドバンス対応：還元率を取得 20090101
 * ************************************************************************
 */
$rank_reduction = 1;
if(function_exists('is_rank_installed')){
	$check_rank_reduction = $db->Execute("select r.rank_point_reduction_rate from ".TABLE_CUSTOMERS_RANK." cr, ".TABLE_ORDERS." o, ".TABLE_RANKS." r 
										where cr.customers_id = o.customers_id
										and o.orders_id = '".(int)$_GET['oID']."'
										and cr.rank_id = r.rank_id
										and r.language_id = '".(int)$_SESSION['languages_id']."'
										");
	$rank_reduction = $check_rank_reduction->fields['rank_point_reduction_rate'];
	if($rank_reduction == 0){
	$rank_reduction = 1;
	}
}

						if((MODULE_ORDER_TOTAL_POINT_STATUS && $ot_class == "ot_points_getting")){
							$RunningTotal += $ot_value * (-1);
							$ot_text = number_format(floor($ot_value)).' P';
						}elseif((MODULE_ORDER_TOTAL_POINT_CAN_USE_STATUS && $ot_class == "ot_points_can_use")){
							$RunningTotal += $ot_value * (-1);
							$ot_text = number_format(floor($ot_value)).' P';
						}elseif((MODULE_ORDER_TOTAL_POINTS_USE_STATUS && $ot_class == "ot_points_use")){
							$RunningTotal -= $ot_value * $rank_reduction * $reduction_rate;
							$RunningTotal -= $ot_value;
//							$RunningTotal -= $ot_value * (($rank_reduction) * ($reduction_rate));

//							$RunningTotal += $ot_value * (-2) * $rank_reduction * $reduction_rate;
//							$RunningTotal += $ot_value * ($rank_reduction - 1);
//							$RunningTotal += $ot_value*($reduction_rate-1)*2;
							$ot_text = '-'.number_format(floor($ot_value * $rank_reduction)).' P';
						}elseif((MODULE_ORDER_TOTAL_GROUP_PRICING_STATUS && $ot_class == "ot_group_pricing")){
							$RunningTotal += $ot_value * (-2);
							$ot_text = '-'.$currencies->format(floor($ot_value), true, $order->info['currency'], $order->info['currency_value']);
						}elseif((MODULE_ORDER_TOTAL_COUPON_STATUS && $ot_class == "ot_coupon")){
							$RunningTotal += $ot_value * (-2);
							$ot_text = '-'.$currencies->format(floor($ot_value), true, $order->info['currency'], $order->info['currency_value']);
						}elseif((MODULE_ORDER_TOTAL_GV_STATUS && $ot_class == "ot_gv")){
							$RunningTotal += $ot_value * (-2);
							$ot_text = '-'.$currencies->format(floor($ot_value), true, $order->info['currency'], $order->info['currency_value']);
						}elseif(MODULE_RANK_CHANGE_METHOD && $ot_class == 'ot_rank_discount'){
							$RunningTotal += $ot_value * (-2);
							$ot_text = '-'.$currencies->format(floor($ot_value), true, $order->info['currency'], $order->info['currency_value']);
						}elseif(MODULE_RANK_CHANGE_METHOD && $ot_class == 'ot_rank_shipping'){
							$RunningTotal += $ot_value * (-2);
							$ot_text = '-'.$currencies->format(floor($ot_value), true, $order->info['currency'], $order->info['currency_value']);
						}else{
							$ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);
						}

						if($ot_class == "ot_total"){
							$ot_text = $ot_text;
						}

					if($ot_total_id > 0){
						// In Database Already - Update

						$Query = "update " . TABLE_ORDERS_TOTAL . "
								set
								title = '$ot_title', text = '$ot_text',
								value = '$ot_value',
								sort_order = '$sort_order'
								where orders_total_id = '$ot_total_id'";
						$db -> Execute($Query);
					}else{

						// New Insert
						$Query = "insert into " . TABLE_ORDERS_TOTAL . "
								set
								orders_id = '$oID',
								title = '$ot_title',
								text = '$ot_text',
								value = '$ot_value',
								class = '$ot_class',
								sort_order = '$sort_order'";
						$db -> Execute($Query);
					}

					$RunningTotal += $ot_value;

				}elseif($ot_total_id > 0){
					// Delete Total Piece
					$Query = "delete from " . TABLE_ORDERS_TOTAL . " where orders_total_id = '$ot_total_id'";
					$db -> Execute($Query);
				}

			}

		if ($order_updated){
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
		}
/*
 * ****************************************************
 * BOF ポイント情報更新 ポイントモジュールアドバンス用
 * ****************************************************
 */
	if(function_exists('zen_edit_orders_for_points')){
/*
$_POST['points_fixation']
$_POST['points_use']
$_POST['points_pending']
$_POST['configuration_value']
$_POST['points_history_id']
*/
	//POST値の命名規則が異なるのでこの時点で更新済みのordersとorders_totalからポイント履歴と顧客別ポイント情報テーブルを更新する。
	$check_orders_total = "select * from ".TABLE_ORDERS_TOTAL." where orders_id = '".(int)$oID."' ";
	$res = mysql_query($check_orders_total);
	$check_ph = $db->Execute($check_orders_total);
//	$order_list = "";
	while($row = mysql_fetch_array($res,MYSQL_ASSOC)){
		if($_POST['status'] == '1'){
			if($row['class'] == 'ot_points_getting'){
				$_POST['points_pending'] = floor($row['value']);
			}elseif($row['class'] == 'ot_points_use'){
				$_POST['points_use'] = floor($row['value']);
			}
		}else{
			if($row['class'] == 'ot_points_getting'){
				if($_POST['status'] != POINTS_CANCEL_STATUS){
					$_POST['points_fixation'] = floor($row['value']);
				}elseif($_POST['status'] != POINTS_FIXATION_STATUS){
					$_POST['points_pending'] = floor($row['value']);
				}
			}elseif($row['class'] == 'ot_points_use'){
				$_POST['points_use'] = floor($row['value']);
			}
		}
	}
	$_POST['points_history_id'] = $_GET['oID'];
	$_POST['configuration_value'] = $_POST['status'] ;

	//修正すべきはポイント履歴と顧客別ポイント情報（ordersとorders_totalはデフォルトの機能をそのまま使う）
	zen_edit_orders_for_points($oID);
	//何かしらのトリガーによる分岐も何も無しでとりあえずzen_edit_orders_for_pointsが動いたこととしてアラート表示ｗ
	$messageStack->add_session(SUCCESS_ORDER_UPDATED_BY_POINTS, 'success');
	}
/*
 * ****************************************************
 * EOF ポイント情報更新 ポイントモジュールアドバンス用
 * ****************************************************
 */
		zen_redirect(zen_href_link("edit_orders.php", zen_get_all_get_params(array('action')) . 'action=edit'));

	break;

	// Add a Product
	case 'add_product':
		if($step == 5){

			// Get Order Info
			$oID = zen_db_prepare_input($_GET['oID']);
			$order = new order($oID);

			$AddedOptionsPrice = 0;

//オプションをSESSIONから変数に格納
$add_product_options = zen_db_prepare_input($_SESSION['add_product_options']);

			// Get Product Attribute Info
			if(isset($add_product_options)){
//				echo 'これが表示される時はオプション設定がDBにinsertされている SELECT句';
//where句で言語指定追記
				foreach($add_product_options as $option_id => $option_value_id){
					$result = $db -> Execute("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa
					LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON (po.products_options_id=pa.options_id)
					LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON (pov.products_options_values_id=pa.options_values_id)
					WHERE pa.products_id='" . $add_product_products_id . "'
					and pov.language_id = '" . (int)$_SESSION['languages_id'] . "'
					and pa.options_id='" . $option_id . "'
					and pa.options_values_id='" . $option_value_id . "'");
					###r.l. $row = zen_db_fetch_array($result);
					$row = $result->fields;
					extract($row, EXTR_PREFIX_ALL, "opt");
					$AddedOptionsPrice += $opt_options_values_price;
					$option_value_details[$option_id][$option_value_id] = array ("options_values_price" => $opt_options_values_price);
					$option_names[$option_id] = $opt_products_options_name;
					$option_values_names[$option_value_id] = $opt_products_options_values_name;
				}
//このタイミングでセッション破棄しないと別のオプションなし商品追加時に前回セッションが持ちまわって予期せぬ結果に。
				unset($_SESSION['add_product_options']);
			}

			// Get Product Info
//where句で言語指定追記
			$InfoQuery = "select p.products_model,p.products_price,pd.products_name,p.products_tax_class_id from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id=p.products_id where p.products_id='$add_product_products_id' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
			$result = $db -> Execute($InfoQuery);
			#$row = zen_db_fetch_array($result);
			extract($result->fields, EXTR_PREFIX_ALL, "p");

			// Following functions are defined at the bottom of this file
			$CountryID = zen_get_country_id($order->delivery["country"]);
			$ZoneID = zen_get_zone_id($CountryID, $order->delivery["state"]);

			$ProductsTax = zen_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);

			$Query = "insert into " . TABLE_ORDERS_PRODUCTS . " set
				orders_id = '".$oID."',
				products_id = '".$add_product_products_id."',
				products_model = '".$p_products_model."',
				products_name = '" . str_replace("'", "&#39;", $p_products_name) . "',
				products_price = '".$p_products_price."',
				final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
				products_tax = '".$ProductsTax."',
				products_quantity = '".$add_product_quantity."';";
			$db -> Execute($Query);
			$new_product_id = zen_db_insert_id();
			//UPDATE_INVENTORY_QUANTITY_START##############################################################################################################
			$db -> Execute("update " . TABLE_PRODUCTS . " set
							products_quantity = products_quantity - " . $add_product_quantity . ",
							products_ordered = products_ordered + " . $add_product_quantity . "
							where products_id = '" . $add_product_products_id . "'
							");
			//UPDATE_INVENTORY_QUANTITY_END##############################################################################################################
			if(isset($add_product_options)){
//				echo 'これが表示される時はオプション設定がDBにinsertされるところ';
//where句で言語指定追記
				foreach($add_product_options as $option_id => $option_value_id){
					$Query = "insert into " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
						orders_id = '".$oID."',
						orders_products_id = '".$new_product_id."',
						products_options = '" . zen_get_option_name_language($option_id,(int)$_SESSION['languages_id']) . "',
						products_options_values = '" . $option_values_names[$option_value_id] . "',
						options_values_price = '" . $option_value_details[$option_id][$option_value_id]["options_values_price"] . "',
						price_prefix = '+';";
					$db -> Execute($Query);
				}
			}

			// Calculate Tax and Sub-Totals 大幅改変
			$order = new order($oID);
			$RunningSubTotal = 0;
			$RunningTax = 0;

/*
 * ****************************************************************
 * bof 消費税の計算 20080923
 * ****************************************************************
 */
			for ($i=0; $i<sizeof($order->products); $i++){
				//数
				$count_value = $order->products[$i]['qty'];
			// 四捨五入
				if(DISPLAY_PRICE_WITH_TAX_ADMIN == 'false'){
					for($n=0; $n<$count_value; $n++){
					$RunningSubTotal += $order->products[$i]["final_price"];
					}
				}else{
					//消費税計算を「個数×単価×税」ではなく、丁寧に「単価×税を個数分ループ」で取得
					for($n=0; $n<$count_value; $n++){
					$RunningSubTotal += round($order->products[$i]["final_price"] * (1 + ($order->products[$i]["tax"]/100)));
					}
				}
			$RunningTax += round(($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
			}
/*
 * ****************************************************************
 * bof 消費税の計算 20080923
 * ****************************************************************
 */


//表記ローカライズ
			// Tax
			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '" . ($currencies->format($RunningTax, true,$order->info['currency'], $order->info['currency_value'])) . "',
				value = '" . $RunningTax . "'
				where class='ot_tax' and orders_id='".$oID."'";
			$db -> Execute($Query);

//表記ローカライズ
			// Sub-Total
			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '" . $currencies->format($RunningSubTotal, true,$order->info['currency'], $order->info['currency_value']) . "',
				value = '" . $RunningSubTotal . "'
				where class='ot_subtotal' and orders_id='".$oID."'";
			$db -> Execute($Query);

			// Total
			$Query = "	select sum(value) as total_value
						from " . TABLE_ORDERS_TOTAL . "
						where class != 'ot_total'
						and orders_id= '".$oID."'
						";
			$result = $db -> Execute($Query);
			#$row = zen_db_fetch_array($result);
			$Total = $result->fields["total_value"];

/*
 * ****************************************************************
 * bof 商品追加時の合計計算 20080923
 * ****************************************************************
 */
			$sql = mysql_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id= '".$oID."' ");
			while($row = mysql_fetch_array($sql,MYSQL_ASSOC)){
			//ループでclassに応じて減算値を加算 最終的に総合計から減算
				if((MODULE_ORDER_TOTAL_POINT_STATUS && $row['class'] == "ot_points_getting")){
					$Total += $row['value'] * (-1);
				}elseif((MODULE_ORDER_TOTAL_POINT_CAN_USE_STATUS && $row['class'] == "ot_points_can_use")){
					$Total += $row['value']  * (-1);
				}elseif((MODULE_ORDER_TOTAL_POINTS_USE_STATUS && $row['class'] == "ot_points_use")){
					$Total += $row['value']  * (-2);
				}elseif((MODULE_ORDER_TOTAL_GROUP_PRICING_STATUS && $row['class'] == "ot_group_pricing")){
					$Total += $row['value']  * (-2);
				}elseif((MODULE_ORDER_TOTAL_COUPON_STATUS && $row['class'] == "ot_coupon")){
					$Total += $row['value']  * (-2);
				}elseif((MODULE_ORDER_TOTAL_GV_STATUS && $row['class'] == "ot_gv")){
					$Total += $row['value']  * (-2);
				}
			}
//分岐
			if(DISPLAY_PRICE_WITH_TAX_ADMIN == 'false'){
				$pre_value = $Total;
			}else{
				$pre_value = $Total - $RunningTax;
			}
/*
 * ****************************************************************
 * bof 商品追加時の合計計算 20080923
 * ****************************************************************
 */
//表記ローカライズ

			$Query = "update " . TABLE_ORDERS_TOTAL . " set
				text = '" . $currencies->format($Total, true,$order->info['currency'], $order->info['currency_value']) . "',
				value = '" . $pre_value . "'
				where class='ot_total' and orders_id='".$oID."'";
			$db -> Execute($Query);

			zen_redirect(zen_href_link("edit_orders.php", zen_get_all_get_params(array('action')) . 'action=edit'));

		}
	break;
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = zen_db_prepare_input($_GET['oID']);

    $orders_query = $db -> Execute("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!$orders_query->RecordCount()) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" media="print" href="includes/stylesheet_print.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>

</head>
<body onLoad="init()">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>


      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_ORDERS, zen_get_all_get_params(array('action'))) . '">' . zen_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>


<!-- Begin Addresses Block -->
      <tr><?php echo zen_draw_form('edit_order', "edit_orders.php", zen_get_all_get_params(array('action','paycc')) . 'action=update_order'); ?>
      <td>
      <table width="100%" border="0"><tr> <td><div align="center">
      <table width="548" border="0" align="center">
  <!--DWLayoutTable-->
  <tr>
    <td colspan="2" valign="top"><b> <?php echo ENTRY_CUSTOMER; ?> </b></td>
    <td width="6" rowspan="9" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="150" valign="top"><b> <?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
    <td width="6" rowspan="9" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="150" valign="top"><span class="main"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></span></td>
    <td width="1">&nbsp;</td>
  </tr>
  <tr>
    <td width="60" valign="top"> <?php echo ENTRY_CUSTOMER_NAME; ?>:</td>
    <td width="150" valign="top"><span class="main">
      <input name="update_customer_name" size="25" value="<?php echo zen_html_quotes($order->customer['name']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_name" size="25" value="<?php echo zen_html_quotes($order->billing['name']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_name" size="25" value="<?php echo zen_html_quotes($order->delivery['name']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"> <?php echo ENTRY_CUSTOMER_COMPANY; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_company" size="25" value="<?php echo zen_html_quotes($order->customer['company']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_company" size="25" value="<?php echo zen_html_quotes($order->billing['company']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_company" size="25" value="<?php echo zen_html_quotes($order->delivery['company']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"> <?php echo ENTRY_CUSTOMER_COUNTRY; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_country" size="25" value="<?php echo zen_html_quotes($order->customer['country']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_country" size="25" value="<?php echo zen_html_quotes($order->billing['country']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_country" size="25" value="<?php echo zen_html_quotes($order->delivery['country']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"> <?php echo ENTRY_CUSTOMER_POSTCODE; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_postcode" size="25" value="<?php echo $order->customer['postcode']; ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_postcode" size="25" value="<?php echo $order->billing['postcode']; ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_postcode" size="25" value="<?php echo $order->delivery['postcode']; ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_STATE; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_state" size="25" value="<?php echo zen_html_quotes($order->customer['state']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_state" size="25" value="<?php echo zen_html_quotes($order->billing['state']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_state" size="25" value="<?php echo zen_html_quotes($order->delivery['state']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_CITY; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_city" size="25" value="<?php echo zen_html_quotes($order->customer['city']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_city" size="25" value="<?php echo zen_html_quotes($order->billing['city']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_city" size="25" value="<?php echo zen_html_quotes($order->delivery['city']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_ADDRESS; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_street_address" size="25" value="<?php echo zen_html_quotes($order->customer['street_address']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_street_address" size="25" value="<?php echo zen_html_quotes($order->billing['street_address']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_street_address" size="25" value="<?php echo zen_html_quotes($order->delivery['street_address']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_SUBURB; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_suburb" size="25" value="<?php echo zen_html_quotes($order->customer['suburb']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_suburb" size="25" value="<?php echo zen_html_quotes($order->billing['suburb']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_suburb" size="25" value="<?php echo zen_html_quotes($order->delivery['suburb']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</div></td></tr></table>
<!-- End Addresses Block -->

      <tr>
	<td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Phone/Email Block -->
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
      		<tr>
      		  <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
      		  <td class="main"><input name='update_customer_telephone' size='15' value='<?php echo $order->customer['telephone']; ?>'></td>
      		</tr>
      		<tr>
      		  <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
      		  <td class="main"><input name='update_customer_email_address' size='35' value='<?php echo $order->customer['email_address']; ?>'></td>
      		</tr>
      	</table></td>
      </tr>

<!-- End Phone/Email Block -->

      <tr>
	<td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Payment Block -->
      <tr>
	<td><table border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
	    <td class="main">
<?php
/*
 * クーポンもしくはギフト券が設定されている時、支払い方法にギフト券/クーポンを追加 20080922ポイントも追加
 * 上記モジュールがインストールされていて、無料商品のときの支払い方法が実質ギフト券/クーポンとなることからこのように対応
 */
$MODULE_PAYMENT_INSTALLED = MODULE_PAYMENT_INSTALLED;
if(MODULE_ORDER_TOTAL_GV_STATUS || MODULE_ORDER_TOTAL_COUPON_STATUS){$MODULE_PAYMENT_INSTALLED .= ';GV/DC';}
if(MODULE_ORDER_TOTAL_POINTS_USE_STATUS){$MODULE_PAYMENT_INSTALLED .= ';POINTS';}
	$paymod = split(';',ereg_replace("(.php)","",$MODULE_PAYMENT_INSTALLED));
	for($i=0, $n=sizeof($paymod); $i<$n; $i++){
		$paymod_array[] = array('id' => $paymod[$i],
								'text' => zen_get_paymod_name($paymod[$i]));
	}
echo zen_draw_pull_down_menu('update_info_payment_method', $paymod_array,$order->info['payment_module_code']);
?>
	    <?php
//この辺の処理ちょっと不明、暫定的に言語ファイルで「カード決済に変更するのはやめたほうが良いかも」的な表示をしてます。
	    if($order->info['payment_method'] != "Credit Card")
	    echo ENTRY_UPDATE_TO_CC;

	    ?></td>
	  </tr>

	<?php if ($order->info['cc_type'] || $order->info['cc_owner'] || $order->info['payment_method'] == "Credit Card" || $order->info['cc_number']) { ?>
	  <!-- Begin Credit Card Info Block -->
	  <tr>
	    <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
	    <td class="main"><input name='update_info_cc_type' size='10' value='<?php echo $order->info['cc_type']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
	    <td class="main"><input name='update_info_cc_owner' size='20' value='<?php echo $order->info['cc_owner']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
	    <td class="main"><input name='update_info_cc_number' size='20' value='<?php echo $order->info['cc_number']; ?>'></td>
	  </tr>
	  <tr>
	    <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
	    <td class="main"><input name='update_info_cc_expires' size='4' value='<?php echo $order->info['cc_expires']; ?>'></td>
	  </tr>
	  <!-- End Credit Card Info Block -->
	<?php } ?>

	<?php
        if( (zen_not_null($order->info['account_name']) || zen_not_null($order->info['account_number']) || zen_not_null($order->info['po_number'])) ) {
		?>
		          <tr>
		            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
		          </tr>
		          <tr>
		            <td class="main"><?php echo ENTRY_ACCOUNT_NAME; ?></td>
		            <td class="main"><?php echo $order->info['account_name']; ?></td>
		          </tr>
		          <tr>
		            <td class="main"><?php echo ENTRY_ACCOUNT_NUMBER; ?></td>
		            <td class="main"><?php echo $order->info['account_number']; ?></td>
		          </tr>
		          <tr>
		            <td class="main"><?php echo ENTRY_PURCHASE_ORDER_NUMBER; ?></td>
		            <td class="main"><?php echo $order->info['po_number']; ?></td>
		          </tr>
		<?php
		// purchaseorder end
		    }
?>

	</table></td>
      </tr>
<!-- End Payment Block -->

      <tr>
	<td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
	<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr class="dataTableHeadingRow">
<?php //一覧表はorders.phpの形式踏襲 ?>
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
	  </tr>

	<!-- Begin Products Listings Block -->
	<?
      	// Override order.php Class's Field Limitations
		$index = 0;
		$order->products = array();
		$orders_products_query = $db -> Execute("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
		#while ($orders_products = zen_db_fetch_array($orders_products_query)) {
//onetime_charges追加
		while (!$orders_products_query -> EOF){
		$order->products[$index] = array('qty' => $orders_products_query->fields['products_quantity'],
                                        'name' => str_replace("'", "&#39;", $orders_products_query->fields['products_name']),
                                        'model' => $orders_products_query->fields['products_model'],
                                        'tax' => $orders_products_query->fields['products_tax'],
                                        'price' => $orders_products_query->fields['products_price'],
										'onetime_charges' => $orders_products_query->fields['onetime_charges'],
                                        'final_price' => $orders_products_query->fields['final_price'],
                                        'orders_products_id' => $orders_products_query->fields['orders_products_id']);

		$subindex = 0;
		$attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products_query->fields['orders_products_id'] . "'";
		$attributes_query = $db -> Execute($attributes_query_string);

      while (!$attributes_query -> EOF){
		  $order->products[$index]['attributes'][$subindex] = array('option' => $attributes_query->fields['products_options'],
		                                                           'value' => $attributes_query->fields['products_options_values'],
		                                                           'prefix' => $attributes_query->fields['price_prefix'],
		                                                           'price' => $attributes_query->fields['options_values_price'],
		                                                           'orders_products_attributes_id' => $attributes_query->fields['orders_products_attributes_id']);
		$subindex++;
      $attributes_query -> MoveNext();
		}
		$index++;
      $orders_products_query -> MoveNext();

		}
$one_time_charge = 0;
	for ($i=0; $i<sizeof($order->products); $i++) {
		$orders_products_id = $order->products[$i]['orders_products_id'];

		$RowStyle = "dataTableContent";

		echo '	  <tr class="dataTableRow">' . "\n" .
		   '	    <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" .
		   '	    <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='25' value='" . $order->products[$i]['name'] . "'>";

// Has Attributes?

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
//echo "オプションがあるときこれ表示";
			for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
				$orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
				echo '<br><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . $order->products[$i]['attributes'][$j]['value'] . "'>";
				echo '</i></small></nobr>';
//デバッグ
//echo $_SESSION['languages_id'];
//echo $order->products[$i]['attributes'][$j]['option'];
			}
		}
//zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax'])$currencies->format($order->products[$i]['onetime_charges'], true, $order->info['currency'], $order->info['currency_value'])

		echo '</td>' . "\n" .
			'<td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='18' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
			'<td class="' . $RowStyle . '" align="center" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='3' value='" . zen_display_tax_value($order->products[$i]['tax']) . "'>" . '%</td>' . "\n" .
			'<td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='10' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" .
		($order->products[$i]['onetime_charges'] != '0' ? '<br />'."<input name='update_products[$orders_products_id][onetime_charges]' size='10' value='" . number_format($order->products[$i]['onetime_charges'], 2, '.', '')."'>" : ''). '</td>' ."\n" .
			'<td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][add_tax]' size='10' value='" . number_format(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), 2, '.', '') . "'>" .
		($order->products[$i]['onetime_charges'] != '0' ? '<br />'."<input name='update_products[$orders_products_id][onetime_charges]' size='10' value='" . number_format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), 2, '.', '')."'>" : ''). '</td>' ."\n" .
			'<td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) .
		($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format($order->products[$i]['onetime_charges'], true, $order->info['currency'], $order->info['currency_value']) : '') . '</td>' . "\n" .
			'<td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax'])* $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) .
		($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') . '</td>' . "\n" .
			'</tr>' . "\n";

//ワンタイムチャージへの対応 小計及び合計それぞれにワンタイムチャージの金額を加算
	$one_time_charge += $order->products[$i]['onetime_charges'];


	}

//	デバッグ
/*
	print_r($_SESSION);
	echo 'ここまでSESSION値<br />';
	print_r($_POST);
	echo 'ここまでPOST値<br />';
	print_r($_GET);
	echo 'ここまでGET値<br />';
*/
	?>
	<!-- End Products Listings Block -->

	<!-- Begin Order Total Block -->
	  <tr>
	    <td align="right" colspan="8">
	    	<table border="0" cellspacing="0" cellpadding="2" width="100%">
	    	<tr>
	    	<td align='center' valign='top'><br><a href="<? echo $PHP_SELF . "?oID=$oID&action=add_product&step=1"; ?>"><u><b><font size='3'><?php echo TEXT_DATE_ORDER_ADDNEW; ?> </font></b></u></a></td>
	    	<td align='right'>
	    	<table border="0" cellspacing="0" cellpadding="2">
<?php

      	// Override order.php Class's Field Limitations
		$totals_query = $db -> Execute("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
		$order->totals = array();
		#while ($totals = zen_db_fetch_array($totals_query)) {
      while (!$totals_query -> EOF){

         $order->totals[] = array(	'title' => $totals_query->fields['title'],
         							'text' => $totals_query->fields['text'],
         							'class' => $totals_query->fields['class'],
         							'value' => $totals_query->fields['value'],
         							'orders_total_id' => $totals_query->fields['orders_total_id']);
         $totals_query -> MoveNext();
         }

	$TotalsArray = array();
	for ($i=0; $i<sizeof($order->totals); $i++) {

		if(($order->totals[$i]['class'] == "ot_subtotal") || ($order->totals[$i]['class'] == "ot_total")){

		$TotalsArray[] = array(	"Name" => $order->totals[$i]['title'],
								"Price" => number_format($order->totals[$i]['value']+$one_time_charge , 2, '.', ''),
								"Class" => $order->totals[$i]['class'],
								"TotalID" => $order->totals[$i]['orders_total_id']);
		$TotalsArray[] = array(	"Name" => "          ",
								"Price" => "",
								"Class" => "ot_custom",
								"TotalID" => "0");
		}else{
		$TotalsArray[] = array(	"Name" => $order->totals[$i]['title'],
								"Price" => number_format($order->totals[$i]['value'], 2, '.', ''),
								"Class" => $order->totals[$i]['class'],
								"TotalID" => $order->totals[$i]['orders_total_id']);
		$TotalsArray[] = array(	"Name" => "          ",
								"Price" => "",
								"Class" => "ot_custom",
								"TotalID" => "0");
		}
	}

	array_pop($TotalsArray);
	foreach($TotalsArray as $TotalIndex => $TotalDetails){

		$TotalStyle = "smallText";
		if(($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total")){

			echo	'	      <tr>' . "\n" .
				'		<td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
				'		<td class="main"><b>' . $TotalDetails["Price"] .
						"<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" .
						"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
						"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
				'	      </tr>' . "\n";
			$UpdateOrders1 = "update " . TABLE_ORDERS . " set order_total = '" . $TotalDetails['Price'] . "'";
			$UpdateOrders1 .= " where orders_id = '" . zen_db_input($oID) . "';";
			$db -> Execute($UpdateOrders1);
//			print_r($TotalDetails);
		}elseif($TotalDetails["Class"] == "ot_tax"){
			echo	'	      <tr>' . "\n" .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim($TotalDetails["Name"])) . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
				'		<td class="main"><b>' . $TotalDetails["Price"] .
						"<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
						"<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
				'	      </tr>' . "\n";
			$UpdateOrders2 = "update " . TABLE_ORDERS . " set order_tax = '" . $TotalDetails['Price'] . "'";
			$UpdateOrders2 .= " where orders_id = '" . zen_db_input($oID) . "';";
			$db -> Execute($UpdateOrders2);
		}else{
			echo	'	      <tr>' . "\n" .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(strip_tags(trim($TotalDetails["Name"]))) . "' value='" . strip_tags(trim($TotalDetails["Name"])) . "'>" . '</td>' . "\n" .
				'		<td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" .
						"<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" .
						"<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" .
						'</td>' . "\n" .
				'	      </tr>' . "\n";
		}
	}
?>
	    	</table>
	    	</td>
	    	</tr>
	    	</table>
	    </td>
	  </tr>
	<!-- End Order Total Block -->

	</table></td>
      </tr>

      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <? // if($CommentsWithStatus) { ?>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
            <? //} ?>
          </tr>
<?php
    $orders_history_query = $db -> Execute("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . zen_db_input($oID) . "' order by date_added");
    if ($orders_history_query->RecordCount()) {
      #while ($orders_history = zen_db_fetch_array($orders_history_query)) {
      while (!$orders_history_query -> EOF){
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . zen_datetime_short($orders_history_query->fields['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history_query->fields['customer_notified'] == '1') {
          echo zen_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo zen_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history_query->fields['orders_status_id']] . '</td>' . "\n";

        //if($CommentsWithStatus) {
        echo '            <td class="smallText">' . nl2br(zen_db_output($orders_history_query->fields['comments'])) . '&nbsp;</td>' . "\n";
        //}

        echo '          </tr>' . "\n";
        $orders_history_query -> MoveNext();
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>

      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
        <td class="main">
        <?
        //if($CommentsWithStatus) {
        	echo zen_draw_textarea_field('comments', 'soft', '60', '5');
	//}
/*
	else
	{
		echo zen_draw_textarea_field('comments', 'soft', '60', '5', $order->info['comments']);
	}
*/
        		?>
        </td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo zen_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo zen_draw_checkbox_field('notify', '', true); ?></td>
          </tr>
          <? //if($CommentsWithStatus) { ?>
          <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo zen_draw_checkbox_field('notify_comments', '', true); ?></td>
          </tr>
          <? //} ?>
        </table></td>
      </tr>

      <tr>
	<td align='center' valign="top"><?php echo zen_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
      </tr>
      </form>
<?php
  }

if($action == "add_product")
{
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo ADDING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . zen_href_link(FILENAME_ORDERS, zen_get_all_get_params(array('action'))) . '">' . zen_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>

<?
	// ############################################################################
	//   Get List of All Products
	// ############################################################################

		//$result = zen_db_query("SELECT products_name, p.products_id, x.categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id=p.products_id LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON cd.categories_id=ptc.categories_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " x ON x.categories_id=ptc.categories_id ORDER BY categories_id");
		$result = $db -> Execute("SELECT products_name, p.products_model, p.products_id, categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id=p.products_id LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON cd.categories_id=ptc.categories_id where pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ORDER BY categories_name");
		#hile($row = zen_db_fetch_array($result)) 		{
//品番用追記
		while (!$result -> EOF){
 		   extract($result->fields,EXTR_PREFIX_ALL,"db");
			$ProductList[$db_categories_id][$db_products_id] = $db_products_name;
			$CategoryList[$db_categories_id] = $db_categories_name;
			$LastCategory = $db_categories_name;
			$ProductsModelList[$db_products_id]= $db_products_model;
         $result -> MoveNext();
		}

		// ksort($ProductList);

		$LastOptionTag = "";
		$ProductSelectOptions = "<option value='0'>Don't Add New Product" . $LastOptionTag . "\n";
		$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
		foreach($ProductList as $Category => $Products)
		{
			$ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
			$ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
			asort($Products);
			foreach($Products as $Product_ID => $Product_Name)
			{
				$ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
			}

			if($Category != $LastCategory)
			{
				$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
				$ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
			}
		}


	// ############################################################################
	//   Add Products Steps
	// ############################################################################

		echo "<tr><td><table border='0'>\n";

		// Set Defaults
			if(!IsSet($add_product_categories_id))
			$add_product_categories_id = 0;

			if(!IsSet($add_product_products_id))
			$add_product_products_id = 0;

		// Step 1: Choose Category
			echo "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			echo "<td class='dataTableContent' align='right'><b>STEP 1:</b></td><td class='dataTableContent' valign='top'>";
			echo ' ' . zen_draw_pull_down_menu('add_product_categories_id', zen_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
			echo "<input type='hidden' name='step' value='2'>";
			echo "</td>\n";
			echo "</form></tr>\n";
			echo "<tr><td colspan='3'>&nbsp;</td></tr>\n";

		// Step 2: Choose Product
		if(($step > 1) && ($add_product_categories_id > 0))
		{
			echo "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			echo "<td class='dataTableContent' align='right'><b>STEP 2:</b></td><td class='dataTableContent' valign='top'><select name=\"add_product_products_id\" onChange=\"this.form.submit();\">";
			$ProductOptions = "<option value='0'>" .  ADDPRODUCT_TEXT_SELECT_PRODUCT . "\n";
			asort($ProductList[$add_product_categories_id]);
			foreach($ProductList[$add_product_categories_id] as $ProductID => $ProductName)
			{
//			$ProductOptions .= "<option value='$ProductID'> $ProductName\n";
//品番をリスト冒頭に挿入
			$ProductOptions .= "<option value='$ProductID'> [$ProductsModelList[$ProductID]] : $ProductName\n";
			}
			$ProductOptions = str_replace("value='$add_product_products_id'","value='$add_product_products_id' selected", $ProductOptions);
			echo $ProductOptions;
			echo "</select></td>\n";
			echo "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			echo "<input type='hidden' name='step' value='3'>";
			echo "</form></tr>\n";
			echo "<tr><td colspan='3'>&nbsp;</td></tr>\n";
		}

		// Step 3: Choose Options
		if(($step > 2) && ($add_product_products_id > 0))
		{
			// Get Options for Products
			$result = $db -> Execute("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE pa.products_id='$add_product_products_id' and pov.language_id = '" . (int)$_SESSION['languages_id'] . "'");

			// Skip to Step 4 if no Options
			if($result->RecordCount() == 0)
			{
				echo "<tr class=\"dataTableRow\">\n";
				echo "<td class='dataTableContent' align='right'><b>STEP 3:</b></td><td class='dataTableContent' valign='top' colspan='2'><i>".ADDPRODUCT_TEXT_OPTIONS_NOTEXIST."</i></td>";
				echo "</tr>\n";
				$step = 4;
			}
			else
			{
#				while($row = zen_db_fetch_array($result))  {
            while (!$result -> EOF){
 					extract($result->fields,EXTR_PREFIX_ALL,"db");
					$Options[$db_products_options_id] = $db_products_options_name;
					$ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
               $result -> MoveNext();
				}

				echo "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
				echo "<td class='dataTableContent' align='right'><b>STEP 3:</b></td><td class='dataTableContent' valign='top'>";
				foreach($ProductOptionValues as $OptionID => $OptionValues)
				{
					$OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[$OptionID]'>";
					foreach($OptionValues as $OptionValueID => $OptionValueName)
					{
					$OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
					}
					$OptionOption .= "</select><br>\n";
//POST値を扱ってデータ受け渡す。デフォルトスクリプトだとオプションが登録できなかった。
					if(isset($_POST['add_product_options']) && isset($_POST['add_product_options'][$OptionID]))
					$OptionOption = str_replace("value='".$_POST['add_product_options'][$OptionID]."'","value='".$_POST['add_product_options'][$OptionID]."' selected",$OptionOption);

					echo $OptionOption;
				}
				echo "</td>";
				echo "<td class='dataTableContent' align='center'><input type='submit' value='" . ADDPRODUCT_TEXT_OPTIONS_CONFIRM . "'>";
				echo "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
				echo "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
				echo "<input type='hidden' name='step' value='4'>";
				echo "</td>\n";
				echo "</form></tr>\n";
			}

			echo "<tr><td colspan='3'>&nbsp;</td></tr>\n";
		}

		// Step 4: Confirm

		if($step > 3)
		{
			echo "<tr class=\"dataTableRow\"><form action='$PHP_SELF?oID=$oID&action=$action' method='POST'>\n";
			echo "<td class='dataTableContent' align='right'><b>STEP 4:</b></td>";
			echo "<td class='dataTableContent' valign='top'><input name='add_product_quantity' size='2' value='1'>" . ADDPRODUCT_TEXT_CONFIRM_QUANTITY . "</td>";
			echo "<td class='dataTableContent' align='center'><input type='submit' value='" . ADDPRODUCT_TEXT_CONFIRM_ADDNOW . "'>";

			if(isset($_POST['add_product_options'])){
//POST値でデータ扱う
$add_product_options = zen_db_prepare_input($_POST['add_product_options']);


				foreach($_POST['add_product_options'] as $option_id => $option_value_id)
				{
					echo "<input type='hidden' name='add_product_options[$option_id]' value='$option_value_id'>";
//追加オプションをセッションに入れて修正画面に回す
					$_SESSION['add_product_options'] = zen_db_prepare_input($_POST['add_product_options']);
				}
			}
			echo "<input type='hidden' name='add_product_categories_id' value='$add_product_categories_id'>";
			echo "<input type='hidden' name='add_product_products_id' value='$add_product_products_id'>";
			echo "<input type='hidden' name='add_product_options' value='$add_product_options'>";
			echo "<input type='hidden' name='step' value='5'>";
			echo "</td>\n";
			echo "</form></tr>\n";
			$step = 5;


		}

		echo "</table></td></tr>\n";
}



?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
<?php //echo 'セッションに入れた' .print_r($_SESSION['add_product_options']) ?>
<?php //echo date("D M j Y G:i:s"); ?>
</body>
</html>
<?
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>