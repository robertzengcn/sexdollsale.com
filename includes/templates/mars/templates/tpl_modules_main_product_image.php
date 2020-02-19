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
	require(DIR_WS_MODULES . zen_get_module_directory('additional_images.php'));
?>
<div id="productMainImage" class="centeredContent">
<?php
	echo '<img id="productImage" src="'.$products_image_medium.'" data-zoom-image="'. $products_image_large .'" alt="'.$products_name.'"/>';
?>
</div>

<?php $one_image = (empty($additionalImages)) ? ' class="oneimage"' : ''; ?>

<div id="productAdditionalImages" class="group">
	<div class="productslider">
	  <ul id="productSlider" class="slides">
		<li <?php echo $one_image; ?>>
			<a href="#" data-image="<?php echo $products_image_medium; ?>" data-zoom-image="<?php echo $products_image_large; ?>" title="<?php echo $products_name; ?>" >
		    	<img src="<?php echo $products_image_small; ?>" alt="<?php echo $products_name; ?>" />
			</a>
		</li>

<?php 	
if ($flag_show_product_info_additional_images != 0 && $num_images > 0) {
				
			foreach ($additionalImages as $additionalImage) {
				echo '
					<li>
						<a href="#" data-image="'.$additionalImage['medium'].'" data-zoom-image="'.$additionalImage['large'].'" title="'.$additionalImage['name'].'" >
					    	'.$additionalImage['small'].'
						</a>
					</li>		
				';
			}
 		} ?>
	  </ul>			  	
	</div>
	
	<div class="swipeWrapper hide">	    
		<a href="<?php echo $products_image_large; ?>" rel="prettyPhoto[gal1]" data-image-swipe="<?php echo $products_image_medium; ?>" class="swipebox" title="<?php echo $products_name; ?>" >
		    <img src="<?php echo $products_image_small; ?>" alt="<?php echo $products_name; ?>" />
		</a>
<?php 	if ($flag_show_product_info_additional_images != 0 && $num_images > 0) {  			
				foreach ($additionalImages as $additionalImage) {
					echo '						
							<a href="'.$additionalImage['large'].'" rel="prettyPhoto[gal1]" data-image-swipe="'.$additionalImage['medium'].'" class="swipebox" title="'.$additionalImage['name'].'">
						    	'.$additionalImage['small'].'
							</a>
						
					';
				}
			} 
		?>
	</div>
	
</div>
