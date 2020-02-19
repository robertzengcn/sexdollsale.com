<?php
/**
 * Common Template - tpl_columnar_display.php
 *
 * This file is used for generating tabular output where needed, based on the supplied array of table-cell contents.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_columnar_display.php 3157 2006-03-10 23:24:22Z drbyte $
 */

?>
<?php
  if ($title) {    
   echo $title; 
 }

// Setting the Product Listing Layout Classes 
if(isset($currentListingPage)){
  switch ($currentListingPage) {
    case 'newProducts': 
      $product_listing_responsive_column_class = ($homepage_options->fields['homepage_new_products_responsive_column_layout'] == 1) ? $currentListingPage." newProductsResponsive" : $currentListingPage; 
      $home_slider_class = ($homepage_options->fields['homepage_new_products_display'] == 1) ? ' newproductslider' : '';
      $home_slider_effect_class = isset($sliderEffect) ? $sliderEffect : '';
      break;
    case 'featuredProducts': 
      $product_listing_responsive_column_class = ($homepage_options->fields['homepage_featured_products_responsive_column_layout'] == 1) ? $currentListingPage." featuredProductsResponsive" : $currentListingPage; 
      $home_slider_class = ($homepage_options->fields['homepage_featured_products_display'] == 1) ? ' featuredproductslider' : '';
      $home_slider_effect_class = isset($sliderEffect) ? $sliderEffect : '';
      break;
    case 'specialProducts': 
      $product_listing_responsive_column_class = ($homepage_options->fields['homepage_special_products_responsive_column_layout'] == 1) ? $currentListingPage." specialProductsResponsive" : $currentListingPage; 
      $home_slider_class = ($homepage_options->fields['homepage_special_products_display'] == 1) ? ' specialproductslider' : '';
      $home_slider_effect_class = isset($sliderEffect) ? $sliderEffect : '';
      break;
    case 'alsoPurchased': 
      $product_listing_responsive_column_class = ($product_info_options->fields['product_info_alsopurchased_responsive_column_layout'] == 1) ? $currentListingPage." alsoPurchasedResponsive" : $currentListingPage; 
      $home_slider_class = ($product_info_options->fields['product_info_alsopurchased_products_display'] == 1) ? ' alsopurchasedproductslider' : '';
      $home_slider_effect_class = isset($sliderEffect) ? $sliderEffect : '';
      break; 
    case 'categoryListBoxContents': 
      $product_listing_responsive_column_class = $currentListingPage;       
      $home_slider_class = ''; 
      $home_slider_effect_class = '';
      break;  
        
  }  
}else{ 
  $product_listing_responsive_column_class = ($product_listing_options->fields['product_listing_responsive_column_layout'] == 1) ? "generalProducts generalProductsResponsive" : "generalProducts";    
  $home_slider_class = '';  
}

$alignClass = "";
switch ($homepage_options->fields['homepage_product_text_alignment']) {
  case 2: $alignClass = " leftText"; break;
  case 3: $alignClass = " rightText"; break;
}


if (is_array($list_box_contents) > 0 ) {
  echo '
  <div class="slidercont'.$home_slider_class.' '.$home_slider_effect_class.' '.$alignClass.'">    
    <ul class="slides section group '.$product_listing_responsive_column_class.'">';
      for($row=0;$row<sizeof($list_box_contents);$row++) {
        $params = "";
        //if (isset($list_box_contents[$row]['params'])) $params .= ' ' . $list_box_contents[$row]['params'];    
        for($col=0;$col<sizeof($list_box_contents[$row]);$col++) {
          $r_params = "";
          if (isset($list_box_contents[$row][$col]['params'])) $r_params .= ' ' . (string)$list_box_contents[$row][$col]['params'];
          if (isset($list_box_contents[$row][$col]['text'])) {
             echo '<li' . $r_params . '>' . $list_box_contents[$row][$col]['text'] .  '</li>' . "\n"; 
          }    
        }
      }
  echo '</ul>

  <ul class="navBtns">
    <li><a class="prev" href="#"></a></li>
    <li><a class="next" href="#"></a></li>
  </ul>  
  
  </div>
  ';
}
?> 
