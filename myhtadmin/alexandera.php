<?php
/**
 *www.myopencart.org
 *
 * 后台导出物流数据插件
 *
 * @author		www.myopencart.org 
 * @copyright	Copyright (c) 2010, Li Jin Ping.
 * @since		Version X1.0
 * @filesource
 */
$type = $_POST['type'];

$date = $_POST['date'];

if ($type === 'orders') {

//物流信息

//时间限制

         if ($date === '1') {
		 
		 $where = 'where to_days(now())-to_days(date_purchased)=1';
		 
		 } elseif ($date === '2') {
		 
		 $where = 'where to_days(now())-to_days(date_purchased)<=2';
		 
		 } elseif ($date === '7') {
		 
		 $where = 'where to_days(now())-to_days(date_purchased)<=7';
		 
		 } elseif ($date === 'all') {
		 
		 $where = '';
		 
		 }

require('includes/application_top.php');

require "./csvdatafile.php";

set_time_limit(2000);

header("Content-type:application/RFC822");

$servername = explode('.',$_SERVER['SERVER_NAME']);

if ($servername[0] === 'www') {
	
header('Content-Disposition:attackment;filename='.$servername[1].'.csv');

} else {
	
header('Content-Disposition:attackment;filename='.$servername[0].'.csv');

}

$arr_export_titles = array("Date","orders_id","customers Name","Transaction Number","Country","Payment","Email","Trans ID","Products Details");

$csvfile = new csvDataFile("",",","w");

echo $csvfile->printline($arr_export_titles);

//��ʼ��һ���¼

$orders = $db->Execute('select orders_id,delivery_name,delivery_country,payment_module_code,customers_email_address from '.TABLE_ORDERS.' '.$where.' order by orders.orders_id');

$i = 1;

while (!$orders -> EOF) {

$orders_id = $orders->fields['orders_id'];

$print_data[$i][] = date("m-d"); 

$print_data[$i][] = $orders_id;

$print_data[$i][] = $orders->fields['delivery_name'];

$print_data[$i][] = '';

$print_data[$i][] = $orders->fields['delivery_country']; 

$print_data[$i][] = $orders->fields['payment_module_code']; 

$print_data[$i][] = $orders->fields['customers_email_address']; 
 
      $resulta = $db->Execute('select comments from '.TABLE_ORDERS_STATUS_HISTORY.' where orders_id='.$orders_id.' and length(comments)>50');
	  
	  while (!$resulta -> EOF) {
		  
		  $comments = $resulta->fields['comments'];
		  
		  if(strpos($comments,'Trans ID:')) {
			  
			  $comment = '';
			  
			  $comment .= substr($comments,strpos($comments,'Trans ID:')+9,18); //返回Trans ID
			  
			  } else {
				  
				  $comment .= '';
				  
				  }
			  
		  $resulta->MoveNext();
		  
		  }
		  
$print_data[$i][] = $comment; //$orders->fields['comments']; 

unset($comment); //多出循环清空变量

//读取产品信息

      $result = $db->Execute('select b.products_options,b.products_options_values,a.products_name,a.final_price,a.products_quantity from '.TABLE_ORDERS_PRODUCTS.' as a left join '.TABLE_ORDERS_PRODUCTS_ATTRIBUTES.' as b on b.orders_products_id=a.orders_products_id where a.orders_id='.$orders_id);
	  
	  while (!$result -> EOF) {

	  $products .= $result->fields['products_quantity'];
	  
	  $products .= '+';

	  $products .= $result->fields['products_name'];
	  
	  $products .= ' $';

	  $products .= $result->fields['final_price'];
	  
	  $products .= ' #';

	  $products .= $result->fields['products_options'];
	  
	  $products .= ': ';

      $products .= $result->fields['products_options_values'];
	  
	  $products .= ' | ';	 
	  	  
	  $result->MoveNext();
	  
	  }

$print_data[$i][] = $products;

unset($products); //多个循环清空变量

$i++;

$orders->MoveNext();

}

echo $csvfile->printcsv($print_data);

require(DIR_WS_INCLUDES . 'application_bottom.php');




} elseif ($type === 'customers') {

//客服信息

//时间限制

         if ($date === '1') {
		 
		 $where = 'where c.customers_id=b.customers_id and a.countries_id=b.entry_country_id and to_days(now())-to_days(customers_dob)=1';
		 
		 } elseif ($date === '2') {
		 
		 $where = 'where c.customers_id=b.customers_id and a.countries_id=b.entry_country_id and to_days(now())-to_days(customers_dob)<=2';
		 
		 } elseif ($date === '7') {
		 
		 $where = 'where c.customers_id=b.customers_id and a.countries_id=b.entry_country_id and to_days(now())-to_days(customers_dob)<=7';
		 
		 } elseif ($date === 'all') {
		 
		 $where = 'where c.customers_id=b.customers_id and a.countries_id=b.entry_country_id';
		 
		 }

require('includes/application_top.php');

require "./csvdatafile.php";

set_time_limit(200);

header("Content-type:application/RFC822");

$servername = explode('.',$_SERVER['SERVER_NAME']);

if ($servername[0] === 'www') {
	
header('Content-Disposition:attackment;filename='.$servername[1].'_customers.csv');

} else {
	
header('Content-Disposition:attackment;filename='.$servername[0].'_customers.csv');

}

$arr_export_titles = array("Date","id","firstname","lastname","gender","email_address","telephone","street_address","suburb","postcode","city","state","countries");

$csvfile = new csvDataFile("",",","w");

echo $csvfile->printline($arr_export_titles);

//开始读一条记录

$customers = $db->Execute('select c.customers_dob,c.customers_id,c.customers_firstname,c.customers_lastname,c.customers_gender,c.customers_email_address,c.customers_telephone,b.entry_street_address,b.entry_suburb,b.entry_postcode,b.entry_city,b.entry_state,a.countries_name from '.TABLE_CUSTOMERS.' as c,'.TABLE_COUNTRIES.' as a,'.TABLE_ADDRESS_BOOK.' as b '.$where.' order by customers_id');

$i = 1;

while (!$customers -> EOF) {

$print_data[$i][] = $customers->fields['customers_dob'];

$print_data[$i][] = $customers->fields['customers_id'];

$print_data[$i][] = $customers->fields['customers_firstname'];

$print_data[$i][] = $customers->fields['customers_lastname'];

$print_data[$i][] = $customers->fields['customers_gender'];

$print_data[$i][] = $customers->fields['customers_email_address'];

$print_data[$i][] = $customers->fields['customers_telephone'];

$print_data[$i][] = $customers->fields['entry_street_address'];

$print_data[$i][] = $customers->fields['entry_suburb'];

$print_data[$i][] = $customers->fields['entry_postcode'];

$print_data[$i][] = $customers->fields['entry_city'];

$print_data[$i][] = $customers->fields['entry_state'];

$print_data[$i][] = $customers->fields['countries_name'];

$i++;

$customers->MoveNext();

}

echo $csvfile->printcsv($print_data);

require(DIR_WS_INCLUDES . 'application_bottom.php');

} else {

//不会出现的页面

die('ok');

}

?>