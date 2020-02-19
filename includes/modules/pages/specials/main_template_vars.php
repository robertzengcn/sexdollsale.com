<?php
/**
 * Specials
 *
 * @package page
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: main_template_vars.php 18802 2011-05-25 20:23:34Z drbyte $
 */

if (MAX_DISPLAY_SPECIAL_PRODUCTS > 0 ) {
  $specials_query_raw = "SELECT p.products_id, p.products_image, pd.products_name,
                          p.master_categories_id
                         FROM (" . TABLE_PRODUCTS . " p
                         LEFT JOIN " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                         LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                         WHERE p.products_id = s.products_id and p.products_id = pd.products_id and p.products_status = '1'
                         AND s.status = 1
                         AND pd.language_id = :languagesID
                         ORDER BY s.specials_date_added DESC";

  $specials_query_raw = $db->bindVars($specials_query_raw, ':languagesID', $_SESSION['languages_id'], 'integer');
  $specials_split = new splitPageResults($specials_query_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);
  $specials = $db->Execute($specials_split->sql_query);
  $row = 0;
  $col = 0;
  $list_box_contents = array();
  $title = '';

  $num_products_count = $specials->RecordCount();
  if ($num_products_count) {

    switch (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS) {
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
  
    $list_box_contents = array();
    while (!$specials->EOF) {

      $products_price = zen_get_products_display_price($specials->fields['products_id']);
      $specials->fields['products_name'] = zen_get_products_name($specials->fields['products_id']);
      $list_box_contents[$row][$col] = array('params' => 'class="specialsListBoxContents col '.$col_width.'"',
                                             'text' => '<a href="' . zen_href_link(zen_get_info_page($specials->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($specials->fields['master_categories_id']) . '&products_id=' . $specials->fields['products_id']) . '">' . (($specials->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : zen_image(DIR_WS_IMAGES . $specials->fields['products_image'], $specials->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH, IMAGE_PRODUCT_LISTING_HEIGHT) . '</a>') . '<br /><a href="' . zen_href_link(zen_get_info_page($specials->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($specials->fields['master_categories_id']) . '&products_id=' . $specials->fields['products_id']) . '">' . $specials->fields['products_name'] . '</a><br />' . $products_price);
      $col ++;
      if ($col > (SHOW_PRODUCT_INFO_COLUMNS_SPECIALS_PRODUCTS - 1)) {
        $col = 0;
        $row ++;
      }
      $specials->MoveNext();
    }
    require($template->get_template_dir('tpl_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_specials_default.php');
  }
}
