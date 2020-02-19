<?php
/**
 *
 */
if (isset ($zco_notifie))
	$zco_notifier->notify('NOTIFY_HEADER_START_CHECKOUT_PAYRESULT');
//重置通知
$messageStack->reset();
require (DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

//判断是不是第二次传输数据
////////////////////////////////////////////////////////////////
	/**
	**第二次传输【最终支付结果将会获取到】
	*/
	if(isset($_REQUEST["ReturnBillNo"]) && !empty($_REQUEST["ReturnBillNo"])){
		//订单号
		$returnBillNo = $_REQUEST["ReturnBillNo"];	
		//币种
		$ReturnCurrency = $_REQUEST["ReturnCurrency"];
		//金额
		$ReturnAmount = $_REQUEST["ReturnAmount"];
		//支付状态
		$ReturnSucceed = $_REQUEST["ReturnSucceed"];//返回码: 1 :表示交易成功 ; 0: 表示交易失败
		//支付结果
		$ReturnResult = $_REQUEST["ReturnResult"];// success: 表示成功 ;   fail:表示失败
		if($ReturnSucceed='1'){//支付成功
				//查看是否处理中...
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态
				if($orders_status!=MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_PROCESSING_ID)
				{
					echo "success";
					exit;
				}

				// 更新该订单对应的订单状态
				$sql_data_array = array('orders_status' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_SUCCESS_ID,
										'orders_date_finished' => 'now()');
				zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int) $returnBillNo);// 更新订单状态
				
				// 更新历史订单状态
				$sql_data_array = array (
				'orders_status_id' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_SUCCESS_ID,
				'date_added' => 'now()',
				'customer_notified' => 0
				);
				zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$returnBillNo);
				log_result ("payment result:  ".$ReturnResult."	orderNo:".$returnBillNo);
			exit;
		}
		else if($ReturnSucceed='0'){//支付失败
				//查看是否处理中...
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态
				if($orders_status!=MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_PROCESSING_ID)
				{
					echo "success";
					exit;
				}

			// 更新该订单对应的订单状态
				$sql_data_array = array('orders_status' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_FAIL_ID,
										'orders_date_finished' => 'now()');
				zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int) $returnBillNo);// 更新订单状态
				
				// 更新历史订单状态
				$sql_data_array = array (
				'orders_status_id' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_FAIL_ID,
				'date_added' => 'now()',
				'customer_notified' => 0
				);
				zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$returnBillNo);
				
				log_result ("payment result:  ".$ReturnResult."	orderNo:".$returnBillNo);
			exit;
		}
		else{
			exit;
		}

		
	}
	

////////////////////////////////////////////////////////////////////

//获得接口返回数据
$BillNo = $_REQUEST['BillNo'];
$Currency = $_REQUEST['Currency'];
$Amount = $_REQUEST['Amount'];
$Succeed = $_REQUEST['Succeed'];
$Result = $_REQUEST['Result'];
$MD5info = $_REQUEST['MD5info'];
$MD5key = MODULE_PAYMENT_FASHIONPAY_MD5KEY;
$md5src = $BillNo . $Currency . $Amount . $Succeed . $MD5key;
$md5sign = strtoupper(md5($md5src));
//基本验证


if($MD5info==$md5sign){

	$DisAmount =number_format($Amount * ($currencies->get_value($_SESSION['currency'])), 2, '.', '').'  '.$_SESSION['currency'];
			if ($Succeed == '88'){
				$messageStack->add('checkout_payresult', '<br/>Your Order Number :'.$BillNo.'<br/>Amount:'.$DisAmount.'<br/>Payment Result:'.$Result, 'success');
				$sql_data_array = array('orders_status' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_SUCCESS_ID, 
										'orders_date_finished' => 'now()', 
										);
			zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态
			
				$sql_data_array = array('orders_status_id' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_SUCCESS_ID, 
										'date_added' => 'now()');
			zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态历史
			
			}
			else if ($Succeed == '19'){
					$messageStack->add('checkout_payresult', '<br/>Your Order Number :'.$BillNo.'<br/>Amount:'.$DisAmount.'<br/>Payment Result:'.$Result, 'success');
					$sql_data_array = array('orders_status' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_PROCESSING_ID, 
										'orders_date_finished' => 'now()', 
										);
			zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态
					$sql_data_array = array('orders_status_id' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_PROCESSING_ID, 
											'date_added' => 'now()');
					zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态历史
				
					//$order->send_order_email($BillNo,2);//付款成功发邮件
			}
			else if ($Succeed == '1' || $Succeed == '9'){
					$messageStack->add('checkout_payresult', '<br/>Your Order Number :'.$BillNo.'<br/>Amount:'.$DisAmount.'<br/>Payment Result:'.$Result, 'success');
					$sql_data_array = array('orders_status' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_PROCESSING_ID, 
										'orders_date_finished' => 'now()', 
										);
			zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态
					$sql_data_array = array('orders_status_id' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_PROCESSING_ID, 
											'date_added' => 'now()');
					zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态历史
					
			}

			else if ($Succeed == '5' || $Succeed == '6' || $Succeed == '7' || $Succeed == '8'){
					$messageStack->add('checkout_payresult', '<br/>Your Order Number :'.$BillNo.'<br/>Amount:'.$DisAmount.'<br/>Payment Result:'.$Result, 'success');
					
					
			}
			else{
				$messageStack->add('checkout_payresult', '<br/>Your Order Number :'.$BillNo.'<br/>Amount:'.$DisAmount.'<br/>Payment Result:'.$Result.$Succeed, 'error');					
					$sql_data_array = array('orders_status' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_FASHIONPAYDECLINED_ID, 
										'orders_date_finished' => 'now()', 
										);
			zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态
					$sql_data_array = array('orders_status_id' => MODULE_PAYMENT_FASHIONPAY_ORDER_STATUS_PAY_FASHIONPAYDECLINED_ID, 
											'date_added' => 'now()');
					zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);//更新订单状态历史
					
					log_result ("payment result:  ".$Result."	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_FASHIONPAY_MONEYTYPE."	responseCode:".$Succeed);
					
			}		
			
	}
	else{
		$messageStack->add('checkout_payresult', 'pay result: verification failed'.$Succeed.MODULE_PAYMENT_FASHIONPAY_MD5KEY, 'error');
		log_result ("verification failed,please check yout md5key,thank you!".$BillNo);
	}


	 //日志消息,把支付平台反馈的参数记录下来
	function  log_result($word) {
		$fp = fopen("fashionpay_pay_error_log.txt","a");	//log.txt请放在当前文件所在目录里
		flock($fp, LOCK_EX) ;
		fwrite($fp,$word."	time: ".date("Y-m-d h:i:s")."\r\n");
		flock($fp, LOCK_UN); 
		fclose($fp);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// unregister session variables used during checkout
$_SESSION['cart']->reset(true);
//unset($_SESSION['cart']);
unset($_SESSION['cartID']);
unset($_SESSION['orders_id']);
unset($_SESSION['order_summary']);
unset($_SESSION['order_number_created']);
unset($_SESSION['sendto']);
unset($_SESSION['billto']);
unset($_SESSION['shipping']);
unset($_SESSION['payment']);
unset($_SESSION['comments']); 
?>
