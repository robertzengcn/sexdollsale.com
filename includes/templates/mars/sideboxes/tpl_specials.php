<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_specials.php 18698 2011-05-04 14:50:06Z wilt $
 */
  
  $specials_box_class = ($sidebox_options->fields['sidebox_special_products_display'] == 1) ? " sideBoxSlider" : ""; 
  
  $content = "";
  $content .= '<div class="sideBoxContent sideBoxProduct'.$specials_box_class.'">';
  $content .= zen_image($template->get_template_dir(OTHER_IMAGE_ON_SALE, DIR_WS_TEMPLATE, $current_page_base,'buttons/' . $_SESSION['language'] . '/') . OTHER_IMAGE_ON_SALE, OTHER_REVIEWS_ON_SALE_ALT);
  $content .= '<ul class="slides">';  
  $specials_box_counter = 0;    
  while (!$random_specials_sidebox_product->EOF) {
    $specials_box_counter++;
    $products_display_price = zen_get_products_display_price($random_specials_sidebox_product->fields['products_id']);    
    $products_special_price = zen_get_products_special_price($random_specials_sidebox_product->fields['products_id']);    
    

    $content .= "\n" . '  <li>';
    $content .= '<div class="img_cont"><a href="' . zen_href_link(zen_get_info_page($random_specials_sidebox_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_specials_sidebox_product->fields["master_categories_id"]) . '&products_id=' . $random_specials_sidebox_product->fields["products_id"]).'">' .zen_image(DIR_WS_IMAGES . $random_specials_sidebox_product->fields['products_image'], $random_specials_sidebox_product->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH,IMAGE_PRODUCT_LISTING_HEIGHT). '</a></div>';
    $content .= '<div class="price_out">' . $currencies->format($products_special_price) . '</div>'; 
    $content .= '<div class="product_info"><a href="' . zen_href_link(zen_get_info_page($random_specials_sidebox_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_specials_sidebox_product->fields["master_categories_id"]) . '&products_id=' . $random_specials_sidebox_product->fields["products_id"]).'">';     
    $content .= '<h4>'.$random_specials_sidebox_product->fields['products_name'].'</h4>';    
    $content .= '<div>' . $products_display_price . '</div>';
    $content .= '</a></div>';   
    $content .= '</li>';

    $random_specials_sidebox_product->MoveNextRandom();
  }
  $content .= '</ul>';
  $content .= '</div>' . "\n";
