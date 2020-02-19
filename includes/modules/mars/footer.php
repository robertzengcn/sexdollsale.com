<?php
/**
 * footer code - calculates information for display, and calls the template file for footer-rendering
 *
 * @package templateStructure
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: footer.php 3428 2006-04-13 05:03:41Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

# - Footer Follow Us
$sql = "select 
            c_1.configuration_value as facebook,
            c_2.configuration_value as twitter,
            c_3.configuration_value as googleplus,                
            c_4.configuration_value as linkedin,                
            c_5.configuration_value as youtube,                
            c_6.configuration_value as vimeo,                
            c_7.configuration_value as tumblr,                
            c_8.configuration_value as rss,                
            c_9.configuration_value as skype, 
            c_10.configuration_value as follow_order                
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
		where   
                c_1.configuration_key = 'FOOTER_FOLLOW_US_FACEBOOK'
          	and c_2.configuration_key = 'FOOTER_FOLLOW_US_TWITTER'
            and c_3.configuration_key = 'FOOTER_FOLLOW_US_GOOGLE_PLUS'
            and c_4.configuration_key = 'FOOTER_FOLLOW_US_LINKED_IN'
            and c_5.configuration_key = 'FOOTER_FOLLOW_US_YOUTUBE'
            and c_6.configuration_key = 'FOOTER_FOLLOW_US_VIMEO'
            and c_7.configuration_key = 'FOOTER_FOLLOW_US_TUMBLR'
            and c_8.configuration_key = 'FOOTER_FOLLOW_US_RSS'
            and c_9.configuration_key = 'FOOTER_FOLLOW_US_SKYPE'
            and c_10.configuration_key = 'FOOTER_FOLLOW_ORDER'
          ";          

$follow_us_links = $db->Execute($sql); 
$follow_us_order = explode(",", $follow_us_links->fields['follow_order']);

$facebook = $twitter = $googleplus = $linkedin = $youtube = $vimeo = $tumblr = $rss = $skype = false;
if ($follow_us_links->RecordCount() != 0) {
    if($follow_us_links->fields['facebook']){ 	$facebook = $follow_us_links->fields['facebook']; }
    if($follow_us_links->fields['twitter']){  	$twitter = $follow_us_links->fields['twitter']; }
    if($follow_us_links->fields['googleplus']){ $googleplus = $follow_us_links->fields['googleplus']; }
    if($follow_us_links->fields['linkedin']){ 	$linkedin = $follow_us_links->fields['linkedin']; }
    if($follow_us_links->fields['youtube']){ 	$youtube = $follow_us_links->fields['youtube']; }
    if($follow_us_links->fields['vimeo']){ 		$vimeo = $follow_us_links->fields['vimeo']; }
    if($follow_us_links->fields['tumblr']){ 	$tumblr = $follow_us_links->fields['tumblr']; }
    if($follow_us_links->fields['rss']){ 		$rss = $follow_us_links->fields['rss']; }
    if($follow_us_links->fields['skype']){ 		$skype = $follow_us_links->fields['skype']; }
}   

# -------------------------------------------------------------------------------------------------------------
# - Footer Contact Info
$sql = "select configuration_value as contact_info
          from " . TABLE_CONFIGURATION . "
          where configuration_key ='FOOTER_CONTACT_INFO' ";

$contact_info_links = $db->Execute($sql); 
$contact_info = false;

if ($contact_info_links->RecordCount() != 0) {
    if($contact_info_links->fields['contact_info']){ $contact_info = $contact_info_links->fields['contact_info']; }
}

# -------------------------------------------------------------------------------------------------------------
# - Footer Payment Options
$sql = "select 
            c_1.configuration_value as visa,
            c_2.configuration_value as mastercard,
            c_3.configuration_value as americanexpress,                
            c_4.configuration_value as paypal,                
            c_5.configuration_value as discover,                
            c_6.configuration_value as westernunion                            
        from " . TABLE_CONFIGURATION . "       as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id            
        where   
                c_1.configuration_key = 'FOOTER_PAYMENT_OPTION_VISA'
            and c_2.configuration_key = 'FOOTER_PAYMENT_OPTION_MASTERCARD'
            and c_3.configuration_key = 'FOOTER_PAYMENT_OPTION_AMERICANEXPRESS'
            and c_4.configuration_key = 'FOOTER_PAYMENT_OPTION_PAYPAL'
            and c_5.configuration_key = 'FOOTER_PAYMENT_OPTION_DISCOVER'
            and c_6.configuration_key = 'FOOTER_PAYMENT_OPTION_WESTERNUNION'            
          ";          

$payment_options = $db->Execute($sql); 
asort($payment_options->fields);

# -------------------------------------------------------------------------------------------------------------
# - Footer Info Options
$sql = "select 
            c_1.configuration_value as info_custom_info,
            c_2.configuration_value as info_twitter,
            c_3.configuration_value as info_facebook,
            c_4.configuration_value as info_about_us,                
            c_5.configuration_value as info_contacts                           
        from " . TABLE_CONFIGURATION . "       as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id
            inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id                       
            inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id                       
        where                   
                c_1.configuration_key = 'FOOTER_CUSTOM_INFO'
            and c_2.configuration_key = 'FOOTER_TWITTER'
            and c_3.configuration_key = 'FOOTER_FACEBOOK'
            and c_4.configuration_key = 'FOOTER_ABOUT_US'
            and c_5.configuration_key = 'FOOTER_CONTACTS'            
          ";          

$info_content = $db->Execute($sql); 
asort($info_content->fields);

# -------------------------------------------------------------------------------------------------------------
# - Footer General and Facebook Url Options
$sql = "select 
            c_1.configuration_value as smallinfo,
            c_2.configuration_value as biginfo,
            c_3.configuration_value as facebook_url                         
        from " . TABLE_CONFIGURATION . "       as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id           
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id
        where   
                c_1.configuration_key = 'FOOTER_SMALL_INFO'
            and c_2.configuration_key = 'FOOTER_BIG_INFO'
            and c_3.configuration_key = 'FOOTER_FACEBOOK_URL'           
          ";          

$general_options = $db->Execute($sql); 

$smallinfo = $biginfo = false;
if ($general_options->RecordCount() != 0) {
    if($general_options->fields['smallinfo'] != 0){ $smallinfo = true; }
    if($general_options->fields['biginfo'] != 0){   $biginfo = true; }
    if($general_options->fields['facebook_url']){   $facebook_url = $general_options->fields['facebook_url']; }
}
# -------------------------------------------------------------------------------------------------------------
$time_start = explode(' ', PAGE_PARSE_START_TIME);
$time_end = explode(' ', microtime());
$parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

if (STORE_PAGE_PARSE_TIME == 'true') {
  error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . $_SERVER['REQUEST_URI'] . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
}
?>