<?php
/**
 * Module Template
 * 
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_main_product_image.php 18698 2011-05-04 14:50:06Z wilt $
 */
?>
<?php 
	require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_MAIN_PRODUCT_IMAGE)); 	
?>
<div id="reviewsImage" class="centeredContent">
	<?php echo '<img src="'.$products_image_small.'" alt="Product Image"/>'; ?>
</div>
