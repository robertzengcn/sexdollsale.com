<?php
/**
 * header code - calculates information for display, and calls the template file for header-rendering
 *
 * @package templateStructure
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header.php 3428 2006-04-13 05:03:41Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

# - Header Links
$sql = "select 
            c_1.configuration_value as header_home,
            c_2.configuration_value as header_specials,                
            c_3.configuration_value as header_new_products,
            c_4.configuration_value as header_featured_products,
            c_5.configuration_value as header_all_products,                
            c_6.configuration_value as header_reviews,                
            c_7.configuration_value as header_contact_us,                
            c_8.configuration_value as header_faqs               
		from " . TABLE_CONFIGURATION . "       as c_1
			inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
			inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id
			inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id
			inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id
			inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id
			inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id			
            inner join " . TABLE_CONFIGURATION . " as c_8 ON c_8.configuration_group_id = c_1.configuration_group_id            
		where   
                c_1.configuration_key = 'HEADER_HOME_DISPLAY'
            and c_2.configuration_key = 'HEADER_SPECIALS_DISPLAY'
            and c_3.configuration_key = 'HEADER_NEW_PRODUCTS_DISPLAY'
            and c_4.configuration_key = 'HEADER_FEATURED_PRODUCTS_DISPLAY'
            and c_5.configuration_key = 'HEADER_ALL_PRODUCTS_DISPLAY'
            and c_6.configuration_key = 'HEADER_REVIEWS_DISPLAY'
            and c_7.configuration_key = 'HEADER_CONTACT_US_DISPLAY'
            and c_8.configuration_key = 'HEADER_FAQS_DISPLAY'          
          ";          

$header_links = $db->Execute($sql); 
asort($header_links->fields);
# -------------------------------------------------------------------------------------------------------------

?>