<?php
/**
 * main page code - gets general information from the database. 
 *
 * @package templateStructure
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: main page.php 3428 2006-04-13 05:03:41Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

# - Homepage options
$sql = "select 
            c_1.configuration_value as homepage_layout,
            c_2.configuration_value as homepage_content_display,
            c_3.configuration_value as homepage_index_heading_display,
            c_4.configuration_value as homepage_product_text_alignment,
            c_5.configuration_value as homepage_new_products_display,
            c_6.configuration_value as homepage_featured_products_display,
            c_7.configuration_value as homepage_special_products_display,            
            c_8.configuration_value as homepage_new_products_hover_effect,
            c_9.configuration_value as homepage_featured_products_hover_effect,
            c_10.configuration_value as homepage_special_products_hover_effect,
            c_11.configuration_value as homepage_new_products_hide_below,
            c_12.configuration_value as homepage_featured_products_hide_below,
            c_13.configuration_value as homepage_special_products_hide_below,            
            c_14.configuration_value as homepage_new_products_buy_now_button_display,
            c_15.configuration_value as homepage_featured_products_buy_now_button_display,
            c_16.configuration_value as homepage_special_buy_now_button_products_display,            
            c_17.configuration_value as homepage_new_products_responsive_column_layout,
            c_18.configuration_value as homepage_featured_products_responsive_column_layout,
            c_19.configuration_value as homepage_special_products_responsive_column_layout           
		from " . TABLE_CONFIGURATION . "       as c_1
			inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id			
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_8 ON c_8.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_9 ON c_9.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_10 ON c_10.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_11 ON c_11.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_12 ON c_12.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_13 ON c_13.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_14 ON c_14.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_15 ON c_15.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_16 ON c_16.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_17 ON c_17.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_18 ON c_18.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_19 ON c_19.configuration_group_id = c_1.configuration_group_id
		where   
                c_1.configuration_key = 'HOMEPAGE_LAYOUT'
            and c_2.configuration_key = 'HOMEPAGE_CONTENT_DISPLAY'                            
            and c_3.configuration_key = 'HOMEPAGE_INDEX_HEADING_DISPLAY'           
            and c_4.configuration_key = 'HOMEPAGE_PRODUCT_TEXT_ALIGNMENT'             
            and c_5.configuration_key = 'HOMEPAGE_NEW_PRODUCTS_DISPLAY'            
            and c_6.configuration_key = 'HOMEPAGE_FEATURED_PRODUCTS_DISPLAY'            
            and c_7.configuration_key = 'HOMEPAGE_SPECIAL_PRODUCTS_DISPLAY'             
            and c_8.configuration_key = 'HOMEPAGE_NEW_PRODUCTS_HOVER_EFFECT' 
            and c_9.configuration_key = 'HOMEPAGE_FEATURED_PRODUCTS_HOVER_EFFECT' 
            and c_10.configuration_key = 'HOMEPAGE_SPECIAL_PRODUCTS_HOVER_EFFECT'                         
            and c_11.configuration_key = 'HOMEPAGE_NEW_PRODUCTS_HIDE_BELOW' 
            and c_12.configuration_key = 'HOMEPAGE_FEATURED_PRODUCTS_HIDE_BELOW' 
            and c_13.configuration_key = 'HOMEPAGE_SPECIAL_PRODUCTS_HIDE_BELOW'                         
            and c_14.configuration_key = 'HOMEPAGE_NEW_PRODUCTS_BUY_NOW_BUTTON_DISPLAY' 
            and c_15.configuration_key = 'HOMEPAGE_FEATURED_PRODUCTS_BUY_NOW_BUTTON_DISPLAY' 
            and c_16.configuration_key = 'HOMEPAGE_SPECIAL_BUY_NOW_BUTTON_PRODUCTS_DISPLAY'             
            and c_17.configuration_key = 'HOMEPAGE_NEW_PRODUCTS_RESPONSIVE_COLUMN_LAYOUT'            
            and c_18.configuration_key = 'HOMEPAGE_FEATURED_PRODUCTS_RESPONSIVE_COLUMN_LAYOUT'            
            and c_19.configuration_key = 'HOMEPAGE_SPECIAL_PRODUCTS_RESPONSIVE_COLUMN_LAYOUT'            
          ";          

$homepage_options = $db->Execute($sql); 
$homepage_content_order = explode(",", $homepage_options->fields['homepage_content_display']);

# - Product listing options
$sql = "select 
            c_1.configuration_value as product_listing_category_image_alignment,
            c_2.configuration_value as product_listing_details_display,
            c_3.configuration_value as product_listing_responsive_column_layout            
        from " . TABLE_CONFIGURATION . "       as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id            
        where   
                c_1.configuration_key = 'PRODUCT_LISTING_CATEGORY_IMAGE_ALIGNMENT'
            and c_2.configuration_key = 'PRODUCT_LISTING_DETAILS_DISPLAY'
            and c_3.configuration_key = 'PRODUCT_LISTING_RESPONSIVE_COLUMN_LAYOUT'            
          ";          

$product_listing_options = $db->Execute($sql);

# - Product info options
$sql = "select 
            c_1.configuration_value as product_info_layout,
            c_2.configuration_value as product_info_alsopurchased_products_display,            
            c_3.configuration_value as product_info_alsopurchased_products_hide_below,
            c_4.configuration_value as product_info_alsopurchased_products_buy_now_button_display,
            c_5.configuration_value as product_info_alsopurchased_responsive_column_layout,
            c_6.configuration_value as product_info_rating_display,
            c_7.configuration_value as product_info_reviews_display_tabs,
            c_8.configuration_value as product_info_addthis,
            c_9.configuration_value as product_info_sharethis,           
            c_10.configuration_value as product_info_custom_tab_1,           
            c_11.configuration_value as product_info_custom_tab_2,           
            c_12.configuration_value as product_info_custom_tab_3,      
            c_13.configuration_value as product_info_custom_content_1,           
            c_14.configuration_value as product_info_custom_content_2,           
            c_15.configuration_value as product_info_custom_content_3    
                   
        from " . TABLE_CONFIGURATION . "           as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_8 ON c_8.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_9 ON c_9.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_10 ON c_10.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_11 ON c_11.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_12 ON c_12.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_13 ON c_13.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_14 ON c_12.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_15 ON c_13.configuration_group_id = c_1.configuration_group_id
        where   
                c_1.configuration_key = 'PRODUCT_INFO_LAYOUT'
            and c_2.configuration_key = 'PRODUCT_INFO_ALSOPURCHASED_PRODUCTS_DISPLAY'                        
            and c_3.configuration_key = 'PRODUCT_INFO_ALSOPURCHASED_PRODUCTS_HIDE_BELOW'                     
            and c_4.configuration_key = 'PRODUCT_INFO_ALSOPURCHASED_PRODUCTS_BUY_NOW_BUTTON_DISPLAY'                        
            and c_5.configuration_key = 'PRODUCT_INFO_ALSOPURCHASED_RESPONSIVE_COLUMN_LAYOUT'                     
            and c_6.configuration_key = 'PRODUCT_INFO_RATING_DISPLAY'                     
            and c_7.configuration_key = 'PRODUCT_INFO_REVIEWS_DISPLAY_TABS'                     
            and c_8.configuration_key = 'PRODUCT_INFO_ADDTHIS'            
            and c_9.configuration_key = 'PRODUCT_INFO_SHARETHIS'            
            and c_10.configuration_key = 'PRODUCT_INFO_CUSTOM_TAB_1'            
            and c_11.configuration_key = 'PRODUCT_INFO_CUSTOM_TAB_2'            
            and c_12.configuration_key = 'PRODUCT_INFO_CUSTOM_TAB_3' 
            and c_13.configuration_key = 'PRODUCT_INFO_CUSTOM_CONTENT_1'            
            and c_14.configuration_key = 'PRODUCT_INFO_CUSTOM_CONTENT_2'            
            and c_15.configuration_key = 'PRODUCT_INFO_CUSTOM_CONTENT_3'
          ";          

$product_info_options = $db->Execute($sql); 

# - Sidebox options
$sql = "select 
            c_1.configuration_value as sidebox_new_products_display,
            c_2.configuration_value as sidebox_featured_products_display,
            c_3.configuration_value as sidebox_special_products_display            
        from " . TABLE_CONFIGURATION . "           as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id            
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id                                   
        where   
                c_1.configuration_key = 'SIDEBOX_NEW_PRODUCTS_DISPLAY'
            and c_2.configuration_key = 'SIDEBOX_FEATURED_PRODUCTS_DISPLAY'            
            and c_3.configuration_key = 'SIDEBOX_SPECIAL_PRODUCTS_DISPLAY'            
          ";          

$sidebox_options = $db->Execute($sql); 

# - General options
$sql = "select             
            c_1.configuration_value as general_site_theme,
            c_2.configuration_value as general_site_layout,
            c_3.configuration_value as general_site_text_style,
            c_4.configuration_value as general_button_style,
            c_5.configuration_value as general_item_hover_style,                    
            c_6.configuration_value as general_loader_display,
            c_7.configuration_value as sideblock_left_display,
            c_8.configuration_value as sideblock_right_display,
            c_9.configuration_value as sideblock_left_width,
            c_10.configuration_value as sideblock_right_width
        from " . TABLE_CONFIGURATION . "           as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_8 ON c_8.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_9 ON c_9.configuration_group_id = c_1.configuration_group_id                        
            inner join " . TABLE_CONFIGURATION . " as c_10 ON c_10.configuration_group_id = c_1.configuration_group_id                        
        where   
                c_1.configuration_key = 'GENERAL_SITE_THEME'
            and c_2.configuration_key = 'GENERAL_SITE_LAYOUT'
            and c_3.configuration_key = 'GENERAL_SITE_TEXT_STYLE'
            and c_4.configuration_key = 'GENERAL_BUTTON_STYLE'
            and c_5.configuration_key = 'GENERAL_ITEM_HOVER_STYLE'
            and c_6.configuration_key = 'GENERAL_LOADER_DISPLAY'
            and c_7.configuration_key = 'SIDEBLOCK_LEFT_DISPLAY'
            and c_8.configuration_key = 'SIDEBLOCK_RIGHT_DISPLAY'
            and c_9.configuration_key = 'SIDEBLOCK_LEFT_WIDTH'
            and c_10.configuration_key = 'SIDEBLOCK_RIGHT_WIDTH'
          ";          

$general_site_options = $db->Execute($sql); 

# -------------------------------------------------------------------------------------------------------------

?>