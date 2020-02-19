<?php
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}


$products_id = (int)$_GET['products_id'];
    $flash_page_query = "select p.products_id,p.products_image,pd.products_name from " . TABLE_PRODUCTS ." p, ". TABLE_PRODUCTS_DESCRIPTION . " pd where p.`products_id`=pd.`products_id` AND pd.`language_id` = '" . (int)$_SESSION['languages_id'] . "' AND p.`master_categories_id` = " . zen_get_products_category_id($products_id) . " AND p.`products_status` ='1' ORDER BY rand() limit 4";
    $flash_page = $db->Execute($flash_page_query);

  $row = 0;
  $col = 0;
  $list_box_contents = array();
  $title = '';
  $flash_page_display_num = $flash_page->RecordCount();
  // show only when 1 or more and equal to or greater than minimum set in admin
$col_width = floor(100/$flash_page_display_num);


    while (!$flash_page->EOF) {
		$products_price = zen_get_products_display_price($flash_page->fields['products_id']);
      $flash_page->fields['products_name'] = zen_get_products_name($flash_page->fields['products_id']);
	  $products_name = $flash_page->fields['products_name'];
		$products_name = ltrim(substr($products_name, 0, 20) . '');
	
      $products_description = zen_trunc_string(zen_clean_html(stripslashes(zen_get_products_description($flash_page->fields['products_id'], $_SESSION['languages_id']))), PRODUCT_LIST_DESCRIPTION); //To Display Product Desc 
	$products_description = ltrim(substr($products_description, 0, 150) . '..'); //Trims and Limits the desc
	
	  $list_box_contents[$row][$col] = array('params' => 'class="centerBoxContentsProducts centeredContent col col_1_of_4"',
      'text' => (($flash_page->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '
	  		<div class="centerBoxContentsInner">
				<div class="listingProductImage">
	  				<a href="' . zen_href_link(zen_get_info_page($flash_page->fields['products_id']), 'products_id=' . $flash_page->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $flash_page->fields['products_image'], $flash_page->fields['products_name'], IMAGE_PRODUCT_NEW_WIDTH, IMAGE_PRODUCT_NEW_HEIGHT) . '</a>') . 
					'<div class="detailbutton-wrapper">
							<a data-toggle="tooltip" data-original-title="Product Detail" href="' . zen_href_link(zen_get_info_page($flash_page->fields['products_id']), 'cPath=' . $productsInCategory[$flash_page->fields['products_id']] . '&products_id=' . $flash_page->fields['products_id']) . '"></a>
						</div>
					
				</div>
				<div class="listingProductContent">
					<h3 class="itemTitle">
						<a href="' . zen_href_link(zen_get_info_page($flash_page->fields['products_id']), 'products_id=' . $flash_page->fields['products_id']) . '">' . $products_name . '</a>
					</h3>
					<span class="price">' . $products_price . '</span>
				</div>
			</div>');

      $col ++;
      if ($col > (SHOW_PRODUCT_INFO_COLUMNS_flash_page - 1)) {
        $col = 0;
        $row ++;
      }
      $flash_page->MoveNext();
    }
  
  if ($flash_page->RecordCount() > 0 && $flash_page->RecordCount() >= MIN_DISPLAY_ALSO_PURCHASED) {
    $title = '<header><h1>' . TEXT_SIMILAR_PRODUCT . '</header>';
    $zc_show_silimar_product = true;
  }
  
	
?>