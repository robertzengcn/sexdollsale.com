<?php
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
if (!defined('IS_ADMIN_FLAG')) {
	die('Illegal Access');
}
global $db;
$products_search_query_raw = "SELECT p.products_type, p.products_id, pd.products_name, p.products_image, p.products_price,
                                     p.products_model, p.products_quantity,
                                    p.product_is_always_free_shipping, p.products_qty_box_status
                             FROM " . TABLE_PRODUCTS . " p
                             LEFT JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd
                             WHERE p.products_status = 1
                             AND p.products_id = pd.products_id order by rand()";

$check_products = $db->Execute($products_search_query_raw);
if (!$check_products->RecordCount()){
	echo json_encode(array('stute'=>false,'code'=>1,'msg'=>'there are not data','data'=>null));
	exit();
	
}else{

		
		
	$productlink='<a href="'. zen_href_link(zen_get_info_page($check_products->fields['products_id']),'products_id=' . $check_products->fields['products_id']).'"> '.zen_image_out(DIR_WS_IMAGES . $check_products->fields['products_image'], $check_products->fields['products_name'], 400, 400).'</a>';

	echo $productlink;
	exit();
}
