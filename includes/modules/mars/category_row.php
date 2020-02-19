<?php
/**
 * index category_row.php
 *
 * Prepares the content for displaying a category's sub-category listing in grid format.  
 * Once the data is prepared, it calls the standard tpl_list_box_content template for display.
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: category_row.php 4084 2006-08-06 23:59:36Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$title = '';
$num_categories = $categories->RecordCount();

$row = 0;
$col = 0;
$list_box_contents = '';
if ($num_categories > 0) {

  switch (MAX_DISPLAY_CATEGORIES_PER_ROW) {
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

  $currentListingPage = "categoryListBox";

  while (!$categories->EOF) {
    if (!$categories->fields['categories_image']) !$categories->fields['categories_image'] = 'pixel_trans.gif';
    $cPath_new = zen_get_path($categories->fields['categories_id']);

    // strip out 0_ from top level cats
    $cPath_new = str_replace('=0_', '=', $cPath_new);

    //    $categories->fields['products_name'] = zen_get_products_name($categories->fields['products_id']);

    $list_box_contents[$row][$col] = array('params' => 'class="categoryListBoxContents col '.$col_width.'"',
                                           'text' => '<a href="' . zen_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . zen_image(DIR_WS_IMAGES . $categories->fields['categories_image'], $categories->fields['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br />' . $categories->fields['categories_name'] . '</a>');

    $col ++;
    if ($col > (MAX_DISPLAY_CATEGORIES_PER_ROW -1)) {
      $col = 0;
      $row ++;
    }
    $categories->MoveNext();
  }
}
?>
