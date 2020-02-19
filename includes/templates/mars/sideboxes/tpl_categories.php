<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_categories.php 4162 2006-08-17 03:55:02Z ajeh $
 */

  $content = "";
  
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent '.CATEGORY_LINKS_CONTAINER.'">' . "\n";

  $content .= $box_categories_array . "\n";
  

  if (SHOW_CATEGORIES_BOX_SPECIALS == 'true' or SHOW_CATEGORIES_BOX_PRODUCTS_NEW == 'true' or SHOW_CATEGORIES_BOX_FEATURED_PRODUCTS == 'true' or SHOW_CATEGORIES_BOX_PRODUCTS_ALL == 'true') {
// display a separator between categories and links
    if (SHOW_CATEGORIES_SEPARATOR_LINK == '1') {
      $content .= '<hr id="catBoxDivider" />' . "\n";      
    }
    $content .= '<ul class="'.CATEGORY_LINKS_CONTAINER.' categoryLinksExtra">' . "\n";
    if (SHOW_CATEGORIES_BOX_SPECIALS == 'true') {
      $show_this = $db->Execute("select s.products_id from " . TABLE_SPECIALS . " s where s.status= 1 limit 1");
      if ($show_this->RecordCount() > 0) {
        $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_SPECIALS) . '">' . CATEGORIES_BOX_HEADING_SPECIALS . '</a>' . '</li>' . "\n";
      }
    }
    if (SHOW_CATEGORIES_BOX_PRODUCTS_NEW == 'true') {
      // display limits
//      $display_limit = zen_get_products_new_timelimit();
      $display_limit = zen_get_new_date_range();

      $show_this = $db->Execute("select p.products_id
                                 from " . TABLE_PRODUCTS . " p
                                 where p.products_status = 1 " . $display_limit . " limit 1");
      if ($show_this->RecordCount() > 0) {
        $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_PRODUCTS_NEW) . '">' . CATEGORIES_BOX_HEADING_WHATS_NEW . '</a>' . '</li>' . "\n";
      }
    }
    if (SHOW_CATEGORIES_BOX_FEATURED_PRODUCTS == 'true') {
      $show_this = $db->Execute("select products_id from " . TABLE_FEATURED . " where status= 1 limit 1");
      if ($show_this->RecordCount() > 0) {
        $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_FEATURED_PRODUCTS) . '">' . CATEGORIES_BOX_HEADING_FEATURED_PRODUCTS . '</a>' . '</li>' . "\n";
      }
    }
    if (SHOW_CATEGORIES_BOX_PRODUCTS_ALL == 'true') {
      $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_PRODUCTS_ALL) . '">' . CATEGORIES_BOX_HEADING_PRODUCTS_ALL . '</a>' . '</li>' . "\n";
    }
    $content .= '</ul>' . "\n";
  }
  $content .= '</div>';
?>