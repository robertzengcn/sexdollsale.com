<?php
/**
 *scrolling recent-orders sidebox definitions - text for inclusion in a new blank sidebox
 *
 * @package templateSystem
 * @copyright 2010 dannysun
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: recent_orders_sidebox.php 2007-05-26 kuroi $
 * @email prettywish@126.com 
 * @QQ 305962848
 */

  // test if box should display
  $show_recent_orders_sidebox = true;

  if ($show_recent_orders_sidebox == true) {
  $recent_orders_id = array();
  $recent_orders_product_id =array();
  $recent_orders_product_name =array();
  $recent_orders_product_image =array();
  $recent_orders_delivery_country =array();
  $recent_orders_delivery_state =array();
  $count =0;
  $my_recent_orders_id = $db->Execute("select orders_id,delivery_country,delivery_state from " . TABLE_ORDERS . " order by date_purchased desc limit 10");
  while (!$my_recent_orders_id->EOF) 
  {
  	$rows++;
    $recent_orders_id[$count] =$my_recent_orders_id->fields['orders_id'];
	$recent_orders_delivery_country[$count] =$my_recent_orders_id->fields['delivery_country'];
	$recent_orders_delivery_state[$count] =$my_recent_orders_id->fields['delivery_state'];
	$recent_orders_product =$db->Execute("select products_id,products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id='" . $recent_orders_id[$count] . "' order by orders_products_id asc limit 1");
	while (!$recent_orders_product->EOF) 
	{
		$rows++;
		$recent_orders_product_name[$count] =$recent_orders_product->fields['products_name'];
		$recent_orders_product_id[$count] =$recent_orders_product->fields['products_id'];		
		$recent_orders_product_image_sql =$db->Execute("select products_image from " . TABLE_PRODUCTS . " where products_id='" . $recent_orders_product_id[$count]. "'");
		while (!$recent_orders_product_image_sql->EOF) 
		{
			$rows++;
			$recent_orders_product_image[$count] =$recent_orders_product_image_sql->fields['products_image'];		
			$recent_orders_product_image_sql->MoveNext();
		}			
		$recent_orders_product->MoveNext();
	}	
	$my_recent_orders_id->MoveNext();
	$count++;
  } 
      require($template->get_template_dir('tpl_recent_orders_sidebox.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_recent_orders_sidebox.php');
      $title =  BOX_HEADING_RECENT_ORDERS_SIDEBOX;
      $title_link = false;
      require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
 }
  $count =0;//dont forget to do this;
?>