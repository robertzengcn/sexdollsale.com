<?php
/**
 * column_right module 
 *
 * @package templateStructure
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: column_right.php 4274 2006-08-26 03:16:53Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
$column_box_default='tpl_box_default_right.php';
// Check if there are boxes for the column
$column_right_display= $db->Execute("select layout_box_name from " . TABLE_LAYOUT_BOXES . " where layout_box_location=1 and layout_box_status=1 and layout_template ='" . $template_dir . "'" . ' order by layout_box_sort_order');

$sql = "select
            c_1.configuration_value as general_sidebox_display,
            c_2.configuration_value as homepage_sidebox_display,
            c_3.configuration_value as product_info_sidebox_display          
        from " . TABLE_CONFIGURATION . "         as c_1
          inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id                             
        where   
                c_1.configuration_key = 'GENERAL_SIDEBOX_DISPLAY'            
            and c_2.configuration_key = 'HOMEPAGE_SIDEBOX_DISPLAY'            
            and c_3.configuration_key = 'PRODUCT_INFO_SIDEBOX_DISPLAY'                        
          ";          

$sidebox_display = $db->Execute($sql);

$general_sidebox_display = explode(",", $sidebox_display->fields['general_sidebox_display']);
$homepage_sidebox_display = explode(",", $sidebox_display->fields['homepage_sidebox_display']);
$product_info_sidebox_display = explode(",", $sidebox_display->fields['product_info_sidebox_display']);

// safety row stop
$box_cnt=0;
while (!$column_right_display->EOF and $box_cnt < 100) {
  $box_cnt++;
  if ( file_exists(DIR_WS_MODULES . 'sideboxes/' . $column_right_display->fields['layout_box_name']) or file_exists(DIR_WS_MODULES . 'sideboxes/' . $template_dir . '/' . $column_right_display->fields['layout_box_name']) ) {
?>
<?php
//$column_box_spacer = 'column_box_spacer_right';
    $called_sidebox = $general_sidebox_display; 
    if ($current_page_base == 'index' and $cPath == '') { // We are on Homepage

       $called_sidebox = $homepage_sidebox_display;

    }else if($current_page == "product_info" || 
       $current_page == "product_music_info" ||
       $current_page == "product_free_shipping_info" ||
       $current_page == "document_general_info" ||
       $current_page == "document_product_info"){ // We are on Product info page

       $called_sidebox = $product_info_sidebox_display;
    }
    if(isSideboxAvaibleRight($column_right_display->fields['layout_box_name'], $called_sidebox)){
      $column_width = BOX_WIDTH_RIGHT;
      if ( file_exists(DIR_WS_MODULES . 'sideboxes/' . $template_dir . '/' . $column_right_display->fields['layout_box_name']) ) {
        $box_id = zen_get_box_id($column_right_display->fields['layout_box_name']);
        require(DIR_WS_MODULES . 'sideboxes/' . $template_dir . '/' . $column_right_display->fields['layout_box_name']);
      } else {
        $box_id = zen_get_box_id($column_right_display->fields['layout_box_name']);
        require(DIR_WS_MODULES . 'sideboxes/' . $column_right_display->fields['layout_box_name']);
      }
    }
  } // file_exists
  $column_right_display->MoveNext();
} // while column_right
$box_id = '';

function isSideboxAvaibleRight($sidebox_name, $sidebox_display){
  switch ($sidebox_name) {
    case 'banner_box.php':            return in_array('1', $sidebox_display); break;
    case 'banner_box2.php':           return in_array('2', $sidebox_display); break;
    case 'banner_box_all.php':        return in_array('3', $sidebox_display); break;
    case 'best_sellers.php':          return in_array('4', $sidebox_display); break;
    case 'categories.php':            return in_array('5', $sidebox_display); break;
    case 'currencies.php':            return in_array('6', $sidebox_display); break;
    case 'document_categories.php':   return in_array('7', $sidebox_display); break;
    case 'ezpages.php':               return in_array('8', $sidebox_display); break;
    case 'featured.php':              return in_array('9', $sidebox_display); break;
    case 'information.php':           return in_array('10', $sidebox_display); break;
    case 'languages.php':             return in_array('11', $sidebox_display); break;
    case 'manufacturers.php':         return in_array('12', $sidebox_display); break;
    case 'manufacturer_info.php':     return in_array('13', $sidebox_display); break;
    case 'more_information.php':      return in_array('14', $sidebox_display); break;
    case 'music_genres.php':          return in_array('15', $sidebox_display); break;
    case 'order_history.php':         return in_array('16', $sidebox_display); break;
    case 'product_notifications.php': return in_array('17', $sidebox_display); break;
    case 'record_companies.php':      return in_array('18', $sidebox_display); break;
    case 'reviews.php':               return in_array('19', $sidebox_display); break;
    case 'search.php':                return in_array('20', $sidebox_display); break;
    case 'search_header.php':         return in_array('21', $sidebox_display); break;
    case 'shopping_cart.php':         return in_array('22', $sidebox_display); break;
    case 'specials.php':              return in_array('23', $sidebox_display); break;
    case 'whats_new.php':             return in_array('24', $sidebox_display); break;
    case 'whos_online.php':           return in_array('25', $sidebox_display); break;
    case 'custom_sidebox_1.php':      return in_array('26', $sidebox_display); break;
    case 'custom_sidebox_2.php':      return in_array('27', $sidebox_display); break;
    case 'custom_sidebox_3.php':      return in_array('28', $sidebox_display); break;
    case 'custom_sidebox_4.php':      return in_array('29', $sidebox_display); break;
    case 'custom_sidebox_5.php':      return in_array('30', $sidebox_display); break;

    default: return false; break;
  }  
}

?>