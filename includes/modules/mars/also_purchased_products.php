<?php
/**
 * also_purchased_products.php
 *
 * @package modules
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: also_purchased_products.php 5369 2006-12-23 10:55:52Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
if (isset($_GET['products_id']) && SHOW_PRODUCT_INFO_COLUMNS_ALSO_PURCHASED_PRODUCTS > 0 && MIN_DISPLAY_ALSO_PURCHASED > 0) {

  $also_purchased_products = $db->Execute(sprintf(SQL_ALSO_PURCHASED, (int)$_GET['products_id'], (int)$_GET['products_id']));

  $num_products_ordered = $also_purchased_products->RecordCount();

  $row = 0;
  $col = 0;
  $list_box_contents = array();
  $title = '';

  // show only when 1 or more and equal to or greater than minimum set in admin
  if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED && $num_products_ordered > 0) {

    switch (SHOW_PRODUCT_INFO_COLUMNS_ALSO_PURCHASED_PRODUCTS) {
      case 1: $col_width = 'col_12_of_12'; break;
      case 2: $col_width = 'col_1_of_2'; break;
      case 3: $col_width = 'col_1_of_3'; break;  
      case 4: $col_width = 'col_1_of_4'; break;  
      case 5: $col_width = 'col_1_of_5'; break; 
      case 6: $col_width = 'col_1_of_6'; break;
      case 7: $col_width = 'col_1_of_7'; break;
      case 8: $col_width = 'col_1_of_8'; break;  
      case 9: $col_width = 'col_1_of_9'; break;
      case 10: $col_width = 'col_1_of_10'; break;
          
      default: $col_width = 'col_12_of_12'; break;
    }

    $currentListingPage = "alsoPurchased";      
        
    while (!$also_purchased_products->EOF) {      
      $also_purchased_products->fields['products_name'] = zen_get_products_name($also_purchased_products->fields['products_id']);

      $also_purchased_product_carousel_class = ($product_info_options->fields['product_info_alsopurchased_products_display'] == 1) ? 'centerBoxContentsAlsoPurch' : 'centerBoxContentsAlsoPurch col '.$col_width;

      $link = '<a href="' . zen_href_link(FILENAME_PRODUCTS_ALL, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $also_purchased_products->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT) . '</a>&nbsp;';
      $buy_now_button = ($product_info_options->fields['product_info_alsopurchased_products_buy_now_button_display'] == 1) ? '<div class="buyNowCont">'.$link.'</div>' : '';

      $list_box_contents[$row][$col] = array('params' => 'class="'.$also_purchased_product_carousel_class.'"',
      'text' => (($also_purchased_products->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a class="listingProductLink" href="' . zen_href_link(zen_get_info_page($also_purchased_products->fields['products_id']), 'products_id=' . $also_purchased_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $also_purchased_products->fields['products_image'], $also_purchased_products->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br />') . '<h3 class="itemTitle"><a href="' . zen_href_link(zen_get_info_page($also_purchased_products->fields['products_id']), 'products_id=' . $also_purchased_products->fields['products_id']) . '">' . $also_purchased_products->fields['products_name'] . '</a></h3><br /><span class="price">' . zen_get_products_display_price($also_purchased_products->fields['products_id']).'</span>'.$buy_now_button);

      $col ++;
      if ($col > (SHOW_PRODUCT_INFO_COLUMNS_ALSO_PURCHASED_PRODUCTS - 1)) {
        $col = 0;
        $row ++;
      }
      $also_purchased_products->MoveNext();
    }
  }
  if ($also_purchased_products->RecordCount() > 0 && $also_purchased_products->RecordCount() >= MIN_DISPLAY_ALSO_PURCHASED) {
    $title = '<h2 class="centerBoxHeading">' . TEXT_ALSO_PURCHASED_PRODUCTS . '</h2>';
    $zc_show_also_purchased = true;
  }
}
?>