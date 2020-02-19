<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_best_sellers.php 2982 2006-02-07 07:56:41Z birdbrain $
 */
  require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));	

  $content = '';
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">' . "\n";  
	  $content .= '<ul>' . "\n";
	  for ($i=1; $i<=sizeof($bestsellers_list); $i++) {
	  	$bestsellers_list_image = zen_image(DIR_WS_IMAGES . $bestsellers_list[$i]['image'], $bestsellers_list[$i]['name'], BESTSELLER_IMAGE_WIDTH, BESTSELLER_IMAGE_HEIGHT);
	    $content .= '<li>
				    	<a class="bestseller_img" href="' . zen_href_link(zen_get_info_page($bestsellers_list[$i]['id']), 'products_id=' . $bestsellers_list[$i]['id']) . '">' . $bestsellers_list_image . '</a>
				    	<p>
				    		<a href="' . zen_href_link(zen_get_info_page($bestsellers_list[$i]['id']), 'products_id=' . $bestsellers_list[$i]['id']) . '">
				    		 <strong>' . zen_trunc_string($bestsellers_list[$i]['name'], BEST_SELLERS_TRUNCATE, BEST_SELLERS_TRUNCATE_MORE) . '</strong>
				    		   <span>' . $currencies->format($bestsellers_list[$i]['price']) .'</span>
				    		</a>
				    	</p>
				    </li>' . "\n";
	  }
	  $content .= '</ul>' . "\n";  
  $content .= '</div>';
?>
