<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_featured_products.php 2935 2006-02-01 11:12:40Z birdbrain $
 */
  $zc_show_featured = false;
  include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_FEATURED_PRODUCTS_MODULE));
?>

<!-- bof: featured products  -->
<?php 
	$sum = 0;
	for($row=0;$row<sizeof($list_box_contents);$row++) {
		for($col=0;$col<sizeof($list_box_contents[$row]);$col++) {		
			if (isset($list_box_contents[$row][$col]['text'])) {
			 $sum ++;
			}    
		}
	}
	
	if ($zc_show_featured == true){
		if ($sum > $homepage_options->fields['homepage_featured_products_hide_below']) { ?>
			<div class="centerBoxWrapper" id="featuredProducts">
				<?php
				/**
				 * require the list_box_content template to display the product
				 */
				  require($template->get_template_dir('tpl_columnar_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display.php');
				?>
			</div>
<?php 
		} 
	}	
?>
<!-- eof: featured products  -->
