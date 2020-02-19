<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_whats_new.php 18698 2011-05-04 14:50:06Z wilt $
 */  
  $whats_new_box_class = ($sidebox_options->fields['sidebox_new_products_display'] == 1) ? " sideBoxSlider" : ""; 

  $content = "";
  $content .= '<div class="sideBoxContent sideBoxProduct'.$whats_new_box_class.'">';
  $content .= '<ul class="slides">';  
  $whats_new_box_counter = 0;
  while (!$random_whats_new_sidebox_product->EOF) {
    $whats_new_box_counter++;
    $whats_new_price = zen_get_products_display_price($random_whats_new_sidebox_product->fields['products_id']);

    $content .= "\n" . '  <li>';
    $content .= '<div class="img_cont"><a href="' . zen_href_link(zen_get_info_page($random_whats_new_sidebox_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_whats_new_sidebox_product->fields["master_categories_id"]) . '&products_id=' . $random_whats_new_sidebox_product->fields["products_id"]).'">' .zen_image(DIR_WS_IMAGES . $random_whats_new_sidebox_product->fields['products_image'], $random_whats_new_sidebox_product->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH,IMAGE_PRODUCT_NEW_HEIGHT). '</a></div>';
    $content .= '<div class="price_out">' . $whats_new_price . '</div>'; 
    $content .= '<div class="product_info"><a href="' . zen_href_link(zen_get_info_page($random_whats_new_sidebox_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_whats_new_sidebox_product->fields["master_categories_id"]) . '&products_id=' . $random_whats_new_sidebox_product->fields["products_id"]).'">';     
    $content .= '<h4>'.$random_whats_new_sidebox_product->fields['products_name'].'</h4>';    
    $content .= '<div>' . $whats_new_price . '</div>';
    $content .= '</a></div>';   
    $content .= '</li>';

    $random_whats_new_sidebox_product->MoveNextRandom();
  }
  $content .= '</ul>';
  $content .= '</div>' . "\n";
