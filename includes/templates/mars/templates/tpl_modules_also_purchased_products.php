<?php
/**
 * Module Template
 *
 * Displays content related to "also-purchased-products"
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_also_purchased_products.php 3206 2006-03-19 04:04:09Z birdbrain $
 */
  $zc_show_also_purchased = false;
  include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_ALSO_PURCHASED_PRODUCTS));
?>
<!-- bof: also purchased products  -->
<?php 
	$sum = 0;
	for($row=0;$row<sizeof($list_box_contents);$row++) {
		for($col=0;$col<sizeof($list_box_contents[$row]);$col++) {		
			if (isset($list_box_contents[$row][$col]['text'])) {
			 $sum ++;
			}    
		}
	}

	if ($zc_show_also_purchased == true) { 
		if ($sum > $product_info_options->fields['product_info_alsopurchased_products_hide_below']) { ?>		
			<div class="centerBoxWrapper" id="alsoPurchased">
			<?php
			  	require($template->get_template_dir('tpl_columnar_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display.php');
			?>
		</div>
<?php 
		} 
	}	
?>
<!-- eof: also purchased products  -->