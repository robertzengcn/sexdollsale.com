<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_featured.php 18698 2011-05-04 14:50:06Z wilt $
 */

  $featured_box_class = ($sidebox_options->fields['sidebox_featured_products_display'] == 1) ? " sideBoxSlider" : ""; 

  $content = "";
  $content .= '<div class="sideBoxContent sideBoxProduct'.$featured_box_class.'">';
  $content .= '<ul class="slides">';
  $featured_box_counter = 0;
  while (!$random_featured_product->EOF) {
    $featured_box_counter++;
    $featured_box_price = zen_get_products_display_price($random_featured_product->fields['products_id']);

    $content .= "\n" . '  <li>';
    $content .= '<div class="img_cont"><a href="' .zen_href_link(zen_get_info_page($random_featured_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_featured_product->fields["master_categories_id"]) . '&products_id=' . $random_featured_product->fields["products_id"]) . '">' .zen_image(DIR_WS_IMAGES . $random_featured_product->fields['products_image'], $random_featured_product->fields['products_name'], IMAGE_FEATURED_PRODUCTS_LISTING_WIDTH,IMAGE_FEATURED_PRODUCTS_LISTING_HEIGHT). '</a></div>';
    $content .= '<div class="price_out">' . $featured_box_price . '</div>'; 
    $content .= '<div class="product_info"><a href="' . zen_href_link(zen_get_info_page($random_featured_product->fields["products_id"]), 'cPath=' . zen_get_generated_category_path_rev($random_featured_product->fields["master_categories_id"]) . '&products_id=' . $random_featured_product->fields["products_id"]) . '">';     
    $content .= '<h4>'.$random_featured_product->fields['products_name'].'</h4>';    
    $content .= '<div>' . $featured_box_price . '</div>';
    $content .= '</a></div>';   
    $content .= '</li>';

    $random_featured_product->MoveNextRandom();
  }
  $content .= '</ul>';
  $content .= '</div>' . "\n";
