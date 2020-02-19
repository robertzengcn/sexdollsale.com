<?php
/**
 * new_products.php module
 *
 * @package modules
 * @copyright Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: new_products.php 8730 2008-06-28 01:31:22Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

// initialize vars
$categories_products_id_list = '';
$list_of_products = '';
$new_products_query = '';

$display_limit = zen_get_new_date_range();

if ( (($manufacturers_id > 0 && $_GET['filter_id'] == 0) || $_GET['music_genre_id'] > 0 || $_GET['record_company_id'] > 0) || (!isset($new_products_category_id) || $new_products_category_id == '0') ) {
  $new_products_query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name,
                                p.products_date_added, p.products_price, p.products_type, p.master_categories_id
                           from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                           where p.products_id = pd.products_id
                           and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                           and   p.products_status = 1 " . $display_limit;
} else {
  // get all products and cPaths in this subcat tree
  $productsInCategory = zen_get_categories_products_list( (($manufacturers_id > 0 && $_GET['filter_id'] > 0) ? zen_get_generated_category_path_rev($_GET['filter_id']) : $cPath), false, true, 0, $display_limit);

  if (is_array($productsInCategory) && sizeof($productsInCategory) > 0) {
    // build products-list string to insert into SQL query
    foreach($productsInCategory as $key => $value) {
      $list_of_products .= $key . ', ';
    }
    $list_of_products = substr($list_of_products, 0, -2); // remove trailing comma

    $new_products_query = "select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name,
                                  p.products_date_added, p.products_price, p.products_type, p.master_categories_id
                           from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                           where p.products_id = pd.products_id
                           and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                           and p.products_status = 1
                           and p.products_id in (" . $list_of_products . ")";
  }
}

if ($new_products_query != '') $new_products = $db->ExecuteRandomMulti($new_products_query, MAX_DISPLAY_NEW_PRODUCTS);

$row = 0;
$col = 0;
$list_box_contents = array();
$title = '';

$num_products_count = ($new_products_query == '') ? 0 : $new_products->RecordCount();

// show only when 1 or more
if ($num_products_count > 0) {  
  switch (SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS) {
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

  $currentListingPage = "newProducts";

  while (!$new_products->EOF) {
    $products_price = zen_get_products_display_price_mars($new_products->fields['products_id']);
    if (!isset($productsInCategory[$new_products->fields['products_id']])) $productsInCategory[$new_products->fields['products_id']] = zen_get_generated_category_path_rev($new_products->fields['master_categories_id']);
    
    $new_product_carousel_class = ($homepage_options->fields['homepage_new_products_display'] == 1) ? 'centerBoxContentsNew' : 'centerBoxContentsNew col '.$col_width;
    
    $link = '<a href="' . zen_href_link(FILENAME_PRODUCTS_ALL, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $new_products->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_BUY_NOW, BUTTON_BUY_NOW_ALT) . '</a>&nbsp;';
    $buy_now_button = ($homepage_options->fields['homepage_new_products_buy_now_button_display'] == 1) ? '<div class="buyNowCont">'.$link.'</div>' : '';
    
    // Effect 1
    if($homepage_options->fields['homepage_new_products_hover_effect'] == 1){
      $list_box_contents[$row][$col] = array(
        'params' => 'class="'.$new_product_carousel_class.'"',
        'text' => (($new_products->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a class="listingProductLink" href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $new_products->fields['products_image'], $new_products->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '</a><br />') . '<h3 class="itemTitle"><a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">' . $new_products->fields['products_name'] . '</a></h3><br /><span class="price">' . $products_price.'</span>'.$buy_now_button
      );

      $sliderEffect = 'new_he_1';
    }
    
    // Effect 2
    if($homepage_options->fields['homepage_new_products_hover_effect'] == 2){
      $list_box_contents[$row][$col] = array(
        'params' => 'class="'.$new_product_carousel_class.'"',      
        'text' => '
          <div class="productStyle_2">
            ' . zen_image(DIR_WS_IMAGES . $new_products->fields['products_image'], $new_products->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '
            <a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">
              <div class="info">
                <h4>' . $new_products->fields['products_name'] . '</h4>
                <h6>' . $products_price.'</span></h6>
              </div>
            </a>
          </div>          
        '      
      );

      $sliderEffect = 'new_he_2';
    }

    // Effect 3
    if($homepage_options->fields['homepage_new_products_hover_effect'] == 3){
      $list_box_contents[$row][$col] = array(
        'params' => 'class="'.$new_product_carousel_class.'"',              
        'text' => '          
          <figure class="productStyle_3">
            ' . zen_image(DIR_WS_IMAGES . $new_products->fields['products_image'], $new_products->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '
            <figcaption>
              <a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'cPath=' . $productsInCategory[$featured_products->fields['products_id']] . '&products_id=' . $featured_products->fields['products_id']) . '"><h4>' . $new_products->fields['products_name'] . '</h4></a>
              <h6>' . $products_price.'</h6>
              <div class="button_cont">'.$buy_now_button.'</div>
            </figcaption>
          </figure>      
        '
      );

      $sliderEffect = 'new_he_3';
    }

    // Effect 4
    if($homepage_options->fields['homepage_new_products_hover_effect'] == 4){
      $list_box_contents[$row][$col] = array(
        'params' => 'class="'.$new_product_carousel_class.'"',      
        'text' => '          
          <figure class="productStyle_4">
            <a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $new_products->fields['products_image'], $new_products->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '</a>
            <figcaption>
              <h4><a href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$new_products->fields['products_id']] . '&products_id=' . $new_products->fields['products_id']) . '">' . $new_products->fields['products_name'] . '</a></h4>
              <h6>' . $products_price.'</h6>            
            </figcaption>
          </figure>            
        '
      );

      $sliderEffect = 'new_he_4';
    }

    $col ++;
    if ($col > (SHOW_PRODUCT_INFO_COLUMNS_NEW_PRODUCTS - 1)) {
      $col = 0;
      $row ++;
    }
    $new_products->MoveNextRandom();
  }

  if ($new_products->RecordCount() > 0) {
    if (isset($new_products_category_id) && $new_products_category_id != 0) {
      $category_title = zen_get_categories_name((int)$new_products_category_id);
      $title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . ($category_title != '' ? ' - ' . $category_title : '' ) . '</h2>';
    } else {
      $title = '<h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')) . '</h2>';
    }
    $zc_show_new_products = true;
  }
}
?>