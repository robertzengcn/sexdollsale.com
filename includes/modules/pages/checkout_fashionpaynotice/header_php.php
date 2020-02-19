<?
// $db->Execute("update ".TABLE_ORDERS." set
// orders_status=".MODULE_PAYMENT_95EPAY_ORDER_STATUS_FILISHED_IDS.",orders_date_finished=
// now() where orders_id =".(int)$BillNo);
// exit;
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));

	// 支付平台流水号
	$TradeNo=$_POST["TradeNo"];// 供商户在支付平台查询订单时使用,请合理保存
	// 订单号
	$BillNo = $_POST["BillNo"];	
	// 币种
	$Currency = $_POST["Currency"];
	// 订单金额
	$Amount = $_POST["Amount"];
	// 支付结果
	$PaymentResult = $_POST["PaymentResult"];// 交易结果: 0 : 失败；1 : 成功
	
	// 取得的MD5校验信息
	$MD5info = $_POST["MD5info"]; 
	
	// MD5私钥
	$MD5key = MODULE_PAYMENT_95EPAY_MD5KEY;
	// $MD5key = "12345678";//从支付平台获取
	// 校验源字符串
	$md5src = $TradeNo.$BillNo.$Currency.$Amount.$PaymentResult.$MD5key;
	// MD5检验结果
	$md5sign = strtoupper(md5($md5src));
	
	
	if($MD5info==$md5sign){// 验证成功
	
		if($PaymentResult=='1'){// 支付成功
				//查看是否处理中...
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态
				if($orders_status!=MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_PROCESSING_ID)
				{
					echo "payseccess_notice_success";
					exit;
				}

				// 更新该订单对应的订单状态
				$sql_data_array = array('orders_status' => MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_SUCCESS_ID,
										'orders_date_finished' => 'now()');
				zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);// 更新订单状态
				// 查看是否更新成功
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态
				

				
				// 更新历史订单状态
				$sql_data_array = array (
				'orders_status_id' => MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_SUCCESS_ID,
				'date_added' => 'now()',
				'customer_notified' => 1
				);
				zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$BillNo);
				// 查看是否更新成功
				$orders_query = "SELECT * FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders_history = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_history_status = $orders_history->fields['orders_status_id']; // 该订单的状态

				if($orders_status==MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_SUCCESS_ID && $orders_history_status==MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_SUCCESS_ID){
					echo "payseccess_notice_success";
					log_result ("payment result:  succ	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
				}
				else{// 更新失败
					echo "payseccess_updateOrderStatus_failed";
					log_result ("payseccess_updateOrderStatus_failed		orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
				}
			
		}
		else if($PaymentResult=='0'){// 支付失败
				//查看是否处理中...
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态
				if($orders_status!=MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_PROCESSING_ID)
				{
					echo "payfail_notice_success";
					exit;
				}
			
				// 更新该订单对应的订单状态
				$sql_data_array = array('orders_status' => MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_FAIL_ID,
										'orders_date_finished' => 'now()');
				zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);// 更新订单状态
				// 查看是否更新成功
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态

				// 更新历史订单状态
				$sql_data_array = array (
				'orders_status_id' => MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_FAIL_ID,
				'date_added' => 'now()',
				'customer_notified' => 1
				);
				zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$BillNo);
				// 查看是否更新成功
				$orders_query = "SELECT * FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders_history = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_history_status = $orders_history->fields['orders_status_id']; // 该订单的状态

				if($orders_status==MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_FAIL_ID && $orders_history_status==MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_FAIL_ID){
					echo "payfail_notice_success";
					log_result ("payment result:  fail	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
				}
				else{// 更新失败
					echo "payfail_updateOrderStatus_failed";
					log_result ("payfail_updateOrderStatus_failed	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
				}
			
				
		}
		else if($PaymentResult=='2'){// 处理中...
			
				// 更新该订单对应的订单状态
				$sql_data_array = array('orders_status' => MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_PROCESSING_ID,
										'orders_date_finished' => 'now()');
				zen_db_perform(TABLE_ORDERS, $sql_data_array, 'update', 'orders_id = ' . (int)$BillNo);// 更新订单状态
				// 查看是否更新成功
				$orders_query = "SELECT * FROM " . TABLE_ORDERS . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_status = $orders->fields['orders_status']; // 该订单的状态

				// 更新历史订单状态
				$sql_data_array = array (
				'orders_status_id' => MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_PROCESSING_ID,
				'date_added' => 'now()',
				'customer_notified' => 1
				);
				zen_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array, 'update', 'orders_id=' . (int)$BillNo);
				// 查看是否更新成功
				$orders_query = "SELECT * FROM " . TABLE_ORDERS_STATUS_HISTORY . " WHERE orders_id = :ordersID  LIMIT 1";
				$orders_query = $db->bindVars($orders_query, ':ordersID', $BillNo, 'integer');
				$orders_history = $db->Execute($orders_query);
				// $orders_id = $orders->fields['orders_id']; //该顾客在商户网站中的最新订单号
				$orders_history_status = $orders_history->fields['orders_status_id']; // 该订单的状态

				if($orders_status==MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_PROCESSING_ID && $orders_history_status==MODULE_PAYMENT_95EPAY_ORDER_STATUS_PAY_PROCESSING_ID){
										
					echo "payprocess_notice_success";
					log_result ("payment result:  processing	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_EMBED95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
				}
				else{// 更新失败
					echo "payprocess_updateOrderStatus_failed";
					log_result ("payprocess_updateOrderStatus_failed	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_EMBED95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
				}
			
				
		}
		
	}
	else{// 验证失败
		echo "verification_failed";
		log_result ("verification_failed	orderNo:".$BillNo."	Amount:".$Amount.' '.MODULE_PAYMENT_95EPAY_MONEYTYPE."	95epay_tradeNo:".$TradeNo);
	}
	exit;

	// 日志消息,把支付平台反馈的参数记录下来
	function  log_result($word) {
		$fp = fopen("95epay_pay_result_notice_log.txt","a");	// log.txt请放在当前文件所在目录里
		flock($fp, LOCK_EX) ;
		fwrite($fp,$word."	time: ".date("Y-m-d h:i:s")."\r\n");
		flock($fp, LOCK_UN); 
		fclose($fp);
	}

	
?>
