<?php
/**
 * js header - load the necessary custom Mars theme script files
 * 
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: languages.php 2718 2005-12-28 06:42:39Z drbyte $
 */

$sql = "select 
            c_1.configuration_value as footer_twitter_username,
            c_2.configuration_value as footer_twitter_count,                
            c_3.configuration_value as footer_twitter_rotate,
            c_4.configuration_value as footer_twitter_rotate_speed,
            c_5.configuration_value as footer_twitter_failed,                
            c_6.configuration_value as footer_twitter_time,                
            c_7.configuration_value as footer_twitter_timeago                           
        from " . TABLE_CONFIGURATION . "         as c_1
          inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id                
        where   
                c_1.configuration_key = 'FOOTER_TWITTER_USERNAME'
            and c_2.configuration_key = 'FOOTER_TWITTER_COUNT'
            and c_3.configuration_key = 'FOOTER_TWITTER_ROTATE'
            and c_4.configuration_key = 'FOOTER_TWITTER_ROTATE_SPEED'
            and c_5.configuration_key = 'FOOTER_TWITTER_FAILED'
            and c_6.configuration_key = 'FOOTER_TWITTER_TIME'
            and c_7.configuration_key = 'FOOTER_TWITTER_TIMEAGO'            
          ";          

$footer_twitter = $db->Execute($sql);

$sql = "select
            c_1.configuration_value as header_google_maps_display,
            c_2.configuration_value as header_google_maps_latitude,
            c_3.configuration_value as header_google_maps_longitude,
            c_4.configuration_value as header_google_maps_marker_title,
            c_5.configuration_value as header_google_maps_marker_description, 
            c_6.configuration_value as header_google_analytics_display, 
            c_7.configuration_value as javascript_jquery,
            c_8.configuration_value as javascript_html5shim,                
            c_9.configuration_value as javascript_css3_mediaqueries_js,
            c_10.configuration_value as top_menu_is_sticky,
            c_11.configuration_value as header_content_is_sticky,
            c_12.configuration_value as main_menu_is_sticky,
            c_13.configuration_value as main_menu_home_button_display,
            c_14.configuration_value as main_menu_responsive_breakpoint,
            c_15.configuration_value as sideblock_left_width,
            c_16.configuration_value as sideblock_right_width
        from " . TABLE_CONFIGURATION . "         as c_1
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
        where   
                c_1.configuration_key = 'HEADER_GOOGLE_MAPS_DISPLAY'            
            and c_2.configuration_key = 'HEADER_GOOGLE_MAPS_LATITUDE'            
            and c_3.configuration_key = 'HEADER_GOOGLE_MAPS_LONGITUDE'            
            and c_4.configuration_key = 'HEADER_GOOGLE_MAPS_MARKER_TITLE'            
            and c_5.configuration_key = 'HEADER_GOOGLE_MAPS_MARKER_DESCRIPTION'                        
            and c_6.configuration_key = 'HEADER_GOOGLE_ANALYTICS_DISPLAY'                        
            and c_7.configuration_key = 'JAVASCRIPT_JQUERY_LOAD'
            and c_8.configuration_key = 'JAVASCRIPT_HTML5SHIM_LOAD'
            and c_9.configuration_key = 'JAVASCRIPT_CSS3_MEDIAQUERIES_JS_LOAD'            
            and c_10.configuration_key = 'TOP_MENU_IS_STICKY'            
            and c_11.configuration_key = 'HEADER_CONTENT_IS_STICKY'            
            and c_12.configuration_key = 'MAIN_MENU_IS_STICKY'            
            and c_13.configuration_key = 'MAIN_MENU_HOME_BUTTON_DISPLAY'            
            and c_14.configuration_key = 'MAIN_MENU_RESPONSIVE_BREAKPOINT'            
            and c_15.configuration_key = 'SIDEBLOCK_LEFT_WIDTH'
            and c_16.configuration_key = 'SIDEBLOCK_RIGHT_WIDTH'
          ";          

$javascript_options = $db->Execute($sql);

/* -----*/
if($current_page == "product_info" || 
   $current_page == "product_music_info" ||
   $current_page == "product_free_shipping_info" ||
   $current_page == "document_general_info" ||
   $current_page == "document_product_info"){ 

  $sql = "select
            c_1.configuration_value as product_info_zoom_type,
            c_2.configuration_value as product_info_zoom_easing,
            c_3.configuration_value as product_info_zoom_window_width,
            c_4.configuration_value as product_info_zoom_window_height,
            c_5.configuration_value as product_info_zoom_window_fade_in, 
            c_6.configuration_value as product_info_zoom_window_fade_out,
            c_7.configuration_value as product_info_zoom_level,                
            c_8.configuration_value as product_info_zoom_border_size,
            c_9.configuration_value as product_info_zoom_offset_x,
            c_10.configuration_value as product_info_zoom_offset_y,
            c_11.configuration_value as product_info_zoom_tint,
            c_12.configuration_value as product_info_zoom_tint_color,
            c_13.configuration_value as product_info_zoom_tint_opacity,
            c_14.configuration_value as product_info_large_image_display           
        from " . TABLE_CONFIGURATION . "         as c_1
          inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
          inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_8 ON c_8.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_9 ON c_9.configuration_group_id = c_1.configuration_group_id          
          inner join " . TABLE_CONFIGURATION . " as c_10 ON c_10.configuration_group_id = c_1.configuration_group_id                    
          inner join " . TABLE_CONFIGURATION . " as c_11 ON c_10.configuration_group_id = c_1.configuration_group_id                    
          inner join " . TABLE_CONFIGURATION . " as c_12 ON c_10.configuration_group_id = c_1.configuration_group_id                    
          inner join " . TABLE_CONFIGURATION . " as c_13 ON c_10.configuration_group_id = c_1.configuration_group_id                    
          inner join " . TABLE_CONFIGURATION . " as c_14 ON c_10.configuration_group_id = c_1.configuration_group_id                    
        where   
                c_1.configuration_key = 'PRODUCT_INFO_ZOOM_TYPE'            
            and c_2.configuration_key = 'PRODUCT_INFO_ZOOM_EASING'            
            and c_3.configuration_key = 'PRODUCT_INFO_ZOOM_WINDOW_WIDTH'            
            and c_4.configuration_key = 'PRODUCT_INFO_ZOOM_WINDOW_HEIGHT'            
            and c_5.configuration_key = 'PRODUCT_INFO_ZOOM_WINDOW_FADE_IN'                        
            and c_6.configuration_key = 'PRODUCT_INFO_ZOOM_WINDOW_FADE_OUT'
            and c_7.configuration_key = 'PRODUCT_INFO_ZOOM_LEVEL'
            and c_8.configuration_key = 'PRODUCT_INFO_ZOOM_BORDER_SIZE'            
            and c_9.configuration_key = 'PRODUCT_INFO_ZOOM_OFFSET_X'            
            and c_10.configuration_key = 'PRODUCT_INFO_ZOOM_OFFSET_Y'            
            and c_11.configuration_key = 'PRODUCT_INFO_ZOOM_TINT'            
            and c_12.configuration_key = 'PRODUCT_INFO_ZOOM_TINT_COLOR'            
            and c_13.configuration_key = 'PRODUCT_INFO_ZOOM_TINT_OPACITY'            
            and c_14.configuration_key = 'PRODUCT_INFO_LARGE_IMAGE_DISPLAY'            
          ";          

  $product_info_images = $db->Execute($sql);
}

// - Homepage slider, homepage and product info(also purchased) carousel options
$sql = "select 
            c_1.configuration_value as homepage_set_slider,
            c_2.configuration_value as homepage_slider_options_revolution,
            c_3.configuration_value as homepage_slider_options_flex,
            c_4.configuration_value as homepage_carousel_new_products_options,
            c_5.configuration_value as homepage_carousel_featured_products_options,
            c_6.configuration_value as homepage_carousel_special_products_options,        
            c_7.configuration_value as product_info_carousel_alsopurchased_products_options 

    from " . TABLE_CONFIGURATION . "       as c_1
            inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id      
            inner join " . TABLE_CONFIGURATION . " as c_3 ON c_3.configuration_group_id = c_1.configuration_group_id                       
            inner join " . TABLE_CONFIGURATION . " as c_4 ON c_4.configuration_group_id = c_1.configuration_group_id      
            inner join " . TABLE_CONFIGURATION . " as c_5 ON c_5.configuration_group_id = c_1.configuration_group_id      
            inner join " . TABLE_CONFIGURATION . " as c_6 ON c_6.configuration_group_id = c_1.configuration_group_id      
            inner join " . TABLE_CONFIGURATION . " as c_7 ON c_7.configuration_group_id = c_1.configuration_group_id      
    where       
                c_1.configuration_key = 'HOMEPAGE_SET_SLIDER'
            and c_2.configuration_key = 'HOMEPAGE_SLIDER_OPTIONS_REVOLUTION'
            and c_3.configuration_key = 'HOMEPAGE_SLIDER_OPTIONS_FLEX'
            and c_4.configuration_key = 'HOMEPAGE_CAROUSEL_NEW_PRODUCTS_OPTIONS'
            and c_5.configuration_key = 'HOMEPAGE_CAROUSEL_FEATURED_PRODUCTS_OPTIONS'                            
            and c_6.configuration_key = 'HOMEPAGE_CAROUSEL_SPECIAL_PRODUCTS_OPTIONS'                       
            and c_7.configuration_key = 'PRODUCT_INFO_CAROUSEL_ALSOPURCHASED_PRODUCTS_OPTIONS'                       
          ";          

$homepage_carousel_options = $db->Execute($sql); 
$homepage_set_slider = $homepage_carousel_options->fields['homepage_set_slider'];
$homepage_slider_options_revolution = explode(",", $homepage_carousel_options->fields['homepage_slider_options_revolution']);
$homepage_slider_options_flex = explode(",", $homepage_carousel_options->fields['homepage_slider_options_flex']);
$homepage_carousel_new = explode(",", $homepage_carousel_options->fields['homepage_carousel_new_products_options']);
$homepage_carousel_featured = explode(",", $homepage_carousel_options->fields['homepage_carousel_featured_products_options']);
$homepage_carousel_special = explode(",", $homepage_carousel_options->fields['homepage_carousel_special_products_options']);
$product_info_carousel_alsopurchased = explode(",", $homepage_carousel_options->fields['product_info_carousel_alsopurchased_products_options']);

// --- Revolution Slider Defaults ---
$slider_opt_rev[0] = 'navigationType : "none",';
$slider_opt_rev[1] = 'navigationArrows:"none"';
$slider_opt_rev[2] = 'navigationStyle:"square",';
$slider_opt_rev[3] = 'touchenabled:"off",';
$slider_opt_rev[4] = 'onHoverStop:"off",';

//Revolution Slider
foreach ($homepage_slider_options_revolution as $slider_option_rev){   
  switch ($slider_option_rev) {
    case 1: $slider_opt_rev[0] = 'navigationType:"bullet",'; break;
    case 2: $slider_opt_rev[1] = 'navigationArrows:"verticalcentered",'; break;
    case 3: $slider_opt_rev[2] = 'navigationStyle:"round",'; break;
    case 4: $slider_opt_rev[3] = 'touchenabled:"on",'; break;
    case 5: $slider_opt_rev[4] = 'onHoverStop:"on",'; break;    
  }
}                

// --- FlexSlider Defaults ---
$slider_opt_flex[0] = '';
$slider_opt_flex[1] = 'controlNav: false,';
$slider_opt_flex[2] = 'directionNav: false,';
$slider_opt_flex[3] = '';
$slider_opt_flex[4] = '';

//FlexSlider
foreach ($homepage_slider_options_flex as $slider_option_flex) {   
  switch ($slider_option_flex) {
    case 1: $slider_opt_flex[0] = 'animation: "slide",'; break;
    case 2: $slider_opt_flex[1] = ''; break;
    case 3: $slider_opt_flex[2] = ''; break;
    case 4: $slider_opt_flex[3] = 'multipleKeyboard : true,'; break;
    case 5: $slider_opt_flex[4] = 'mousewheel : true,'; break;    
  }
}

// --- Carousel ---
$new_opt[0] = $featured_opt[0] = $special_opt[0] = $alsopurchased_opt[0] = 'circular : false,';
$new_opt[1] = $featured_opt[1] = $special_opt[1] = $alsopurchased_opt[1] = 'infinite : false,';
$new_opt[2] = $featured_opt[2] = $special_opt[2] = $alsopurchased_opt[2] = 'auto : false,';
$new_opt[3] = $featured_opt[3] = $special_opt[3] = $alsopurchased_opt[3] = '';
$new_opt[4] = $featured_opt[4] = $special_opt[4] = $alsopurchased_opt[4] = '';
$new_opt[5] = $featured_opt[5] = $special_opt[5] = $alsopurchased_opt[5] = '';

//New Products
foreach ($homepage_carousel_new as $new_option) {   
  switch ($new_option) {
    case 1: $new_opt[0] = ''; break;
    case 2: $new_opt[1] = ''; break;
    case 3: $new_opt[2] = 'auto: { items: 1 },'; break;
    case 4: $new_opt[3] = 'responsive : true,'; break;
    case 5: $new_opt[4] = 'mousewheel  : true,'; break;    
    case 6: $new_opt[5] = 'swipe: { onTouch : true },'; break;
  }
}

//Featured Products
foreach ($homepage_carousel_featured as $featured_option) {   
  switch ($featured_option) {
    case 1: $featured_opt[0] = ''; break;
    case 2: $featured_opt[1] = ''; break;
    case 3: $featured_opt[2] = 'auto: { items: 1 },'; break;
    case 4: $featured_opt[3] = 'responsive : true,'; break;
    case 5: $featured_opt[4] = 'mousewheel  : true,'; break;    
    case 6: $featured_opt[5] = 'swipe: { onTouch : true },'; break;
  }
}

//Special Products
foreach ($homepage_carousel_special as $special_option) {   
  switch ($special_option) {
    case 1: $special_opt[0] = ''; break;
    case 2: $special_opt[1] = ''; break;
    case 3: $special_opt[2] = 'auto: { items: 1 },'; break;
    case 4: $special_opt[3] = 'responsive : true,'; break;
    case 5: $special_opt[4] = 'mousewheel  : true,'; break;    
    case 6: $special_opt[5] = 'swipe: { onTouch : true },'; break;
  }
}

//Also Purchased Products
foreach ($product_info_carousel_alsopurchased as $alsopurchased_option) {   
  switch ($alsopurchased_option) {
    case 1: $alsopurchased_opt[0] = ''; break;
    case 2: $alsopurchased_opt[1] = ''; break;
    case 3: $alsopurchased_opt[2] = 'auto: { items: 1 },'; break;
    case 4: $alsopurchased_opt[3] = 'responsive : true,'; break;
    case 5: $alsopurchased_opt[4] = 'mousewheel  : true,'; break;    
    case 6: $alsopurchased_opt[5] = 'swipe: { onTouch : true },'; break;
  }
}

/* -->> --------------------------

  Table of Content:
   1   - jQuery, html5shim and css3-mediaqueries-js
   2   - General jQuery Plugins
   3   - FlexSlider 2 and carouFredSel
   4   - jquery.tweetable and jquery.timeago Twitter feed in footer
   5   - Nicescroll, ElevateZoom, Swipebox, prettyPhoto
   6   - Common script calls
   7   - Inline script callsBig FirstBig First
   
  -->> --------------------------- */

/**
 * ----------------------------------------------------------------------------------------------------------
 * 1 - jQuery, html5shim and css3-mediaqueries-js
 * ----------------------------------------------------------------------------------------------------------
 */
?>
<?php if($javascript_options->fields['javascript_jquery'] == 1){
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/libs/jquery-1.10.2.min.js"></script>'."\n";   
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/libs/jquery-migrate-1.2.1.min.js"></script>'."\n"; 
} else{ ?>
  <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
  <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<?php } ?>


<!-- html5.js, css3-mediaqueries.js and selectivizr for IE less than 9 -->
<!--[if lt IE 9]>
  
  <?php if($javascript_options->fields['javascript_html5shim'] == 1){
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/libs/html5.js"></script>'."\n";     
  } else{ ?>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <?php } 
        if($javascript_options->fields['javascript_css3_mediaqueries_js'] == 1){
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/libs/css3-mediaqueries.js"></script>'."\n";     
  } else{ ?>    
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
  <?php } 

    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/libs/selectivizr-min.js"></script>'."\n";     
  ?>

<![endif]-->

<?php
  
/**
 * ----------------------------------------------------------------------------------------------------------
 * 2 - General jQuery Plugins  
 * ----------------------------------------------------------------------------------------------------------
 */     
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.selectbox-0.2.min.js"></script>'."\n";  
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.tooltipster.min.js"></script>'."\n";  
  if($javascript_options->fields['header_google_maps_display'] == 1){
    echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>';
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.gmaps.js"></script>'."\n";  
  }
?>

<?php  
/**
 * ----------------------------------------------------------------------------------------------------------
 * 3 - FlexSlider 2 and carouFredSel
 * ----------------------------------------------------------------------------------------------------------
 */   
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.mousewheel.js"></script>'."\n"; 
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.flexslider.min.js"></script>'."\n"; 
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.carouFredSel-6.2.1.min.js"></script>'."\n"; 
  
  if( $homepage_set_slider == 1){ // Revolution Slider
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.themepunch.revolution.min.js"></script>'."\n"; 
  }
?>

<?php  
/**
 * ----------------------------------------------------------------------------------------------------------
 * 4 - jquery.tweetable and jquery.timeago Twitter feed in footer
 * ----------------------------------------------------------------------------------------------------------
 */   

  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.tweetable.min.js"></script>'."\n";
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.timeago.js"></script>'."\n";

?>

<?php  
/**
 * ----------------------------------------------------------------------------------------------------------
 * 5 - Nicescroll, ElevateZoom, Swipebox, prettyPhoto
 * ----------------------------------------------------------------------------------------------------------
 */   

  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.nicescroll.min.js"></script>'."\n";

  /* Add here your custom info pages */
  if($current_page == "product_info" || 
     $current_page == "product_music_info" ||
     $current_page == "product_free_shipping_info" ||
     $current_page == "document_general_info" ||
     $current_page == "document_product_info"){ ?> 

    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "ur-e809c2e3-896d-2d83-d1fa-b581ca833bde", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
        
  <?php
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.elevateZoom-2.5.5.min.js"></script>'."\n";
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.swipebox.min.js"></script>'."\n";    
    echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.prettyPhoto.js"></script>'."\n";    
  }
?>

<?php  
/**
 * ----------------------------------------------------------------------------------------------------------
 * 6 - Common script calls
 * ----------------------------------------------------------------------------------------------------------
 */   
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.add2home.js"></script>'."\n";  
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/common.js"></script>'."\n";  
  echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/script.js"></script>'."\n";  
?>

<?php 
/**
 * ----------------------------------------------------------------------------------------------------------
 * 7 - Inline script calls
 * ----------------------------------------------------------------------------------------------------------
 */
?>
<script type="text/javascript">
    
    /**
     * --------------------------------------------------------------------------------------------------
     * Window Load
     * --------------------------------------------------------------------------------------------------
     */   
        
    $(window).load(function() {        
        /**
         * --------------------------------------------------------------------------------------------------
         * Carousel setup
         * --------------------------------------------------------------------------------------------------
         */               
        var newproductslider = featuredproductslider = specialproductslider = alsopurchasedproductslider = brandslider = greatofferslider = false;

        if($('.slidercont').hasClass('newproductslider')){ newproductslider = true; }
        if($('.slidercont').hasClass('featuredproductslider')){ featuredproductslider = true; }
        if($('.slidercont').hasClass('specialproductslider')){ specialproductslider = true; }
        if($('.slidercont').hasClass('alsopurchasedproductslider')){ alsopurchasedproductslider = true; }
        if($('.slidercont').hasClass('brandslider')){ brandslider = true; }
        if($('.slidercont').hasClass('greatofferslider')){ greatofferslider = true; }
        
        var carousel_1 = $(".newproductslider .slides");
        var carousel_2 = $(".featuredproductslider .slides");
        var carousel_3 = $(".specialproductslider .slides");
        var carousel_4 = $(".alsopurchasedproductslider .slides");
        var carousel_5 = $(".brandslider .slides");
        var carousel_6 = $(".greatofferslider .slides");                
        var responsiveAfter = 3; // Slider will be responsive after the set nr. of items

        /* ----- Window Resize Check ----- */
        function getItemWidth(){ if(window.innerWidth < 600) return 400; else return 300; }
        function resized(){
          var itemWidth = getItemWidth();              
          carousel_1.trigger("configuration", ["items.width", itemWidth]);
          carousel_1.trigger("updateSizes");                             
          carousel_2.trigger("configuration", ["items.width", itemWidth]);
          carousel_2.trigger("updateSizes");                             
          carousel_3.trigger("configuration", ["items.width", itemWidth]);
          carousel_3.trigger("updateSizes");                             
          carousel_4.trigger("configuration", ["items.width", itemWidth]);
          carousel_4.trigger("updateSizes");                             
          carousel_5.trigger("configuration", ["items.width", itemWidth]);
          carousel_5.trigger("updateSizes");
          carousel_6.trigger("configuration", ["items.width", itemWidth]);
          carousel_6.trigger("updateSizes");                                      
        }
        var $window = $(window);                      
        $window.resize($.throttle( 250, resized));             
        $window.on( "orientationchange", function( event ) {
          resized();
        });

        /* ----- Carousel 1 - New Products ----- */
        if(newproductslider){
          carousel_1.carouFredSel({
            <?php foreach ($new_opt as $new) { echo $new; } ?>
            width: "100%",                
            height:'auto',            
            prev: '.newproductslider .prev',
            next: '.newproductslider .next',            
            onWindowResize: 'debounce',
            items : {
                width   : 300,                  
                visible   : {
                    min     : 1,
                    max     : 5
                }
            }              
          });                  
        }

        /* ----- Carousel 2 - Featured Products ----- */
        if(featuredproductslider){
          carousel_2.carouFredSel({
            <?php foreach ($featured_opt as $featured) { echo $featured; } ?>
            width: "100%",                
            height: "auto",
            prev: '.featuredproductslider .prev',
            next: '.featuredproductslider .next',
            onWindowResize: 'debounce',
            items : {
                width   : 300,
                visible   : {
                    min     : 1,
                    max     : 5
                }
            }               
          });          
        }
        
        /* ----- Carousel 3 - Special Products ----- */
        if(specialproductslider){
          carousel_3.carouFredSel({                
            <?php foreach ($special_opt as $special) { echo $special; } ?>
            width: "100%",                
            height: "auto",
            prev: '.specialproductslider .prev',
            next: '.specialproductslider .next',
            onWindowResize: 'debounce',
            items : {
                width   : 300,
                visible   : {
                    min     : 1,
                    max     : 5
                }
            }              
          });          
        }

        /* ----- Carousel 4 - Also Purchased Products (On Product Info Page) ----- */
        if(alsopurchasedproductslider){
          carousel_4.carouFredSel({                
            <?php foreach ($alsopurchased_opt as $alsopurchased) { echo $alsopurchased; } ?>
            width: "100%",                
            height: "auto",
            prev: '.alsopurchasedproductslider .prev',
            next: '.alsopurchasedproductslider .next',
            onWindowResize: 'debounce',
            items : {
                width   : 300,
                visible   : {
                    min     : 1,
                    max     : 5
                }
            }              
          });          
        }

        /* ----- Carousel 5 - Brands slider ----- */
        if(brandslider){
          carousel_5.carouFredSel({                            
            auto    : false,
            responsive : true,
            mousewheel  : true,
            swipe: { onTouch : true },            
            width: "100%",                
            height: "auto",
            prev: '.brandslider .prev',
            next: '.brandslider .next',
            onWindowResize: 'debounce',
            items : {
                width   : 300,
                visible   : {
                    min     : 1,
                    max     : 5
                }
            }              
          });          
        }

        /* ----- Carousel 6 - Great Offer slider ----- */
        if(greatofferslider){
          carousel_6.carouFredSel({                            
            auto    : false,
            responsive : true,
            mousewheel  : true,
            swipe: { onTouch : true },            
            width: "100%",                
            height: "auto",
            prev: '.greatofferslider .prev',
            next: '.greatofferslider .next',
            onWindowResize: 'debounce',
            items : {
                width   : 300,
                visible   : {
                    min     : 1,
                    max     : 5
                }
            }              
          });          
        }
      
    });

    /**
     * --------------------------------------------------------------------------------------------------
     * Doocument Ready
     * --------------------------------------------------------------------------------------------------
     */   
    jQuery(function(){
        <?php if($javascript_options->fields['header_google_maps_display'] == 1){ ?>
        /**
         * --------------------------------------------------------------------------------------------------
         * Google Maps
         * --------------------------------------------------------------------------------------------------
         */          
        function showMap(){
          var map;
          map = new GMaps({
            el: '#map',
            lat: <?php echo $javascript_options->fields['header_google_maps_latitude']; ?>,
            lng: <?php echo $javascript_options->fields['header_google_maps_longitude']; ?>
          });        
          map.addMarker({
            <?php if(!empty($javascript_options->fields['header_google_maps_marker_title'])){ echo "title: '".$javascript_options->fields['header_google_maps_marker_title']."',"; } ?>
            <?php if(!empty($javascript_options->fields['header_google_maps_marker_description'])){ ?>        
              infoWindow: {
                content: "<?php echo $javascript_options->fields['header_google_maps_marker_description']; ?>"
              },
            <?php } ?>
            lat: <?php echo $javascript_options->fields['header_google_maps_latitude']; ?>,
            lng: <?php echo $javascript_options->fields['header_google_maps_longitude']; ?>
          });  
        }

        // Google Maps Button Toggle 
        var mapIsHidden = true;
        $('.mapBtn').toggle(function() {
          $('#mapWrapper').slideDown(function(){
            if(mapIsHidden){
              showMap();
              mapIsHidden = false;
            }
          });
        }, function() {
          $('#mapWrapper').slideUp();
        });
        <?php } ?>
        /**
         * --------------------------------------------------------------------------------------------------
         * Tweetable
         * --------------------------------------------------------------------------------------------------
         */
        $('.<?php echo TWITTER_CONTAINER ?>').tweetable({
          username: '<?php echo $footer_twitter->fields["footer_twitter_username"] ?>', 
          limit: <?php echo ($footer_twitter->fields['footer_twitter_count']) ? $footer_twitter->fields['footer_twitter_count'] : '3';  ?>,
          
          rotate: <?php echo ($footer_twitter->fields['footer_twitter_rotate']) ? 'true' : 'false'; ?>,
          speed: <?php echo ($footer_twitter->fields['footer_twitter_rotate_speed']) ? $footer_twitter->fields['footer_twitter_rotate_speed'] : 4000; ?>, 

          replies: false,
          position: 'append',
          failed: '<?php echo $footer_twitter->fields['footer_twitter_failed']; ?>',

          time: <?php echo ($footer_twitter->fields['footer_twitter_time']) ? 'true' : 'false'; ?>,
          html5: <?php echo ($footer_twitter->fields['footer_twitter_timeago']) ? 'true' : 'false'; ?>,
          onComplete:function($ul){
            <?php if($footer_twitter->fields['footer_twitter_timeago']){ ?>
              $('time').timeago();
            <?php } ?>
          }
        });
        
        if (isIE()) {
          $('.<?php echo TWITTER_CONTAINER ?>').html('<?php echo $footer_twitter->fields['footer_twitter_failed']; ?>');
        }
        
        /**
         * --------------------------------------------------------------------------------------------------
         * Sticky Elements
         * --------------------------------------------------------------------------------------------------
         */
        <?php 
          $topMn = $javascript_options->fields['top_menu_is_sticky'];
          $headerCont = $javascript_options->fields['header_content_is_sticky'];
          $mainMn = $javascript_options->fields['main_menu_is_sticky'];        
        ?>

        var header = $("header");
        var headerWrapper = $("#headerWrapper");
        var navigation = $(".navigation");
        var headerTotalHeight = 0

        <?php if($topMn == 1 and $headerCont == 1 and $mainMn == 1){  ?>
                    header.sticky({ topSpacing: 0,center:true });
                    headerWrapper.sticky({ topSpacing: 42,center:true, wrapperClassName:'sticky_header'});  
                    navigation.sticky({ topSpacing: 134,center:true, wrapperClassName:'sticky_nav'});  
                    headerTotalHeight = header.height() + headerWrapper.height() + navigation.height();                    

        <?php } else if($topMn == 0 and $headerCont == 0 and $mainMn == 1) { ?>                                        
                    navigation.sticky({ topSpacing: 0,center:true, wrapperClassName:'sticky_nav'});
                    headerTotalHeight = navigation.height();

        <?php } else if($topMn == 0 and $headerCont == 1 and $mainMn == 0){ ?>                                        
                    headerWrapper.sticky({ topSpacing: 0, center:true, wrapperClassName:'sticky_header'});
                    headerTotalHeight = headerWrapper.height();

        <?php } else if($topMn == 0 and $headerCont == 1 and $mainMn == 1) { ?>                    

                    headerWrapper.sticky({ topSpacing: 0,center:true, wrapperClassName:'sticky_header'});                     
                    navigation.sticky({ topSpacing: 93,center:true, wrapperClassName:'sticky_nav'}); 
                    headerTotalHeight = headerWrapper.height() + navigation.height();

        <?php } else if($topMn == 1 and $headerCont == 0 and $mainMn == 0){ ?>                                        
                    header.sticky({ topSpacing: 0,center:true });
                    headerTotalHeight = header.height();
                    
        <?php } else if($topMn == 1 and $headerCont == 0 and $mainMn == 1){ ?>                                        
                    header.sticky({ topSpacing: 0,center:true });
                    navigation.sticky({ topSpacing: 42,center:true, wrapperClassName:'sticky_nav'});
                    headerTotalHeight = header.height() + navigation.height();  

        <?php } else if($topMn == 1 and $headerCont == 1 and $mainMn == 0){ ?>                                        
                    header.sticky({ topSpacing: 0,center:true });
                    headerWrapper.sticky({ topSpacing: 42,center:true, wrapperClassName:'sticky_header'});
                    headerTotalHeight = header.height() + headerWrapper.height();          
        <?php } ?>         

        /* ------- Main Menu Toggle ------- */    
        var $window = $(window);         
        navCheck();
        stickyNav();

        $('.mobileNavBtn').on('click',function(){ stickyNav(); });          

        $window.resize(function(){ 
          navCheck(); 
          stickyNav();
        }); 
        $window.scroll(stickyNav);
        
        <?php if($javascript_options->fields['main_menu_is_sticky'] == 1){ ?>
            $(".level1").addClass('stickylevel1');
        <?php } ?>


        function navCheck(){
          if($(window).innerWidth() < <?php echo $javascript_options->fields['main_menu_responsive_breakpoint']; ?>){
            $('nav').removeClass('nav').addClass('mobileNav');             
          }else{
            $('nav').removeClass('mobileNav').addClass('nav'); 
            $('.level1').removeAttr('style');
            $('.mobileNavBtn').removeClass('on');
          }          
        }
          
        function stickyNav(){
          var $navContainer = $('.navigation');
          var mainMenuHeight = finalHeight = 0;          

          navCssTop = parseInt($navContainer.css('top'));          
          windowHeight = $window.innerHeight()-50;

          if(navCssTop){
            mainMenuHeight = windowHeight-navCssTop;              
          }else{            
            mainMenuHeight = windowHeight;            
          }     

          mainMenuInnerHeight = $('.level1').innerHeight();
          if(mainMenuHeight < mainMenuInnerHeight){
            finalHeight = mainMenuHeight;
          }else{
            finalHeight = mainMenuInnerHeight;
          }        
          $(".mobileNav .stickylevel1").height(finalHeight);
        }


        /**
         * --------------------------------------------------------------------------------------------------
         * Home Button Display on Main Menu
         * --------------------------------------------------------------------------------------------------
         */  
        <?php if($javascript_options->fields['main_menu_home_button_display'] == 1){ ?>  
          var homeTitle = $("#homeTitle").val();
          var homeUrl = $("#homeUrl").val();    
          var isHomePage = $("#isHomePage").val();    
          $('<li><a href="index.php" class="home">Home</a></li>').insertBefore($('nav .level1 > li:first'));    
          $("nav .home").html(homeTitle);
          $("nav .home").attr('href',homeUrl);
          if(isHomePage != undefined){
            $("nav .home").addClass("on");
          }
        <?php } ?>

        /**
         * --------------------------------------------------------------------------------------------------
         * SideBlock
         * --------------------------------------------------------------------------------------------------
         */
        
        <?php 
          $sb_left_width = (!empty($javascript_options->fields['sideblock_left_width'])) ? $javascript_options->fields['sideblock_left_width'] : '250';
          $sb_right_width = (!empty($javascript_options->fields['sideblock_right_width'])) ? $javascript_options->fields['sideblock_right_width'] : '250';
        ?>

        $(".sideblockLeft").hoverIntent(
          function () {
            $(this).animate({ left: '+=<?php echo $sb_left_width; ?>' }, 450, 'easeOutCirc');
          },
          function () {
            $(this).animate({ left: '-=<?php echo $sb_left_width; ?>' }, 450, 'easeInCirc');
          }
        );

        $(".sideblockRight").hoverIntent(
          function () {
            $(this).animate({ right: '+=<?php echo $sb_right_width; ?>' }, 450, 'easeOutCirc');
          },
          function () {
            $(this).animate({ right: '-=<?php echo $sb_right_width; ?>' }, 450, 'easeInCirc');
          }
        );
        

        /**
         * --------------------------------------------------------------------------------------------------
         * Homepage
         * --------------------------------------------------------------------------------------------------
         */
                        
            /**
             * --------------------------------------------------------------------------------------------------
             * Homepage slider setup
             * --------------------------------------------------------------------------------------------------
             */
            
            <?php if($homepage_set_slider == 1){ ?>

              /* ----- Revolution Slider Setup ----- */ 
              revolution_api =  jQuery('.revolutionslider').revolution(
              {
                <?php foreach ($slider_opt_rev as $slider_rev) { echo $slider_rev; } ?>                

                delay:9000,
                hideThumbs:10,

                startheight:450,
                startwidth:1600,

                stopAtSlide:-1,
                stopAfterLoops:-1,
                
                shadow:0, 
                fullWidth:"on" 
              });

            <?php } else if($homepage_set_slider == 2){ ?>

              /* ----- FlexSlider Setup ----- */ 
              $('.flexslider').flexslider({              
                <?php foreach ($slider_opt_flex as $slider_flex) { echo $slider_flex; } ?>
                slideshowSpeed: 5000
              });

            <?php } ?>                        
            
        /**
         * --------------------------------------------------------------------------------------------------
         * Product Details
         * --------------------------------------------------------------------------------------------------
         */
        <?php /* Add here your custom info pages */
              if($current_page == "product_info" || 
                 $current_page == "product_music_info" ||
                 $current_page == "product_free_shipping_info" ||  
                 $current_page == "document_general_info" ||
                 $current_page == "document_product_info"){ ?>

                  /**
                   * --------------------------------------------------------------------------------------------------
                   * ElevateZoom
                   * --------------------------------------------------------------------------------------------------
                   */
                                    
                  elevate();                  
                  $(window).resize($.debounce( 250, elevate)); 

                  function elevate(){                    
                    $('.zoomContainer').remove();
                    $("#productImage").elevateZoom({
                      
                      <?php 
                      /* ----- Inner Zoom ----- */
                      if($product_info_images->fields['product_info_zoom_type'] == 1){ ?>
                        zoomType : "inner",                      
                        cursor: 'pointer',
                      <?php } ?>
                      
                      <?php 
                      /* ----- Easing ----- */
                      if($product_info_images->fields['product_info_zoom_easing'] == 1){ ?>
                        easing : true,
                      <?php } ?>                    
                      
                      /* ----- Zoom Window Width and Height ----- */
                      <?php 
                        if(!empty($product_info_images->fields['product_info_zoom_window_width'])){ 
                            echo 'zoomWindowWidth:'.$product_info_images->fields['product_info_zoom_window_width'].',';
                        } 
                        if(!empty($product_info_images->fields['product_info_zoom_window_height'])){
                            echo 'zoomWindowHeight:'.$product_info_images->fields['product_info_zoom_window_height'].',';
                        } 
                      ?>  

                      /* ----- Fade In/Out Effect ----- */
                      <?php 
                        if(!empty($product_info_images->fields['product_info_zoom_window_fade_in'])){ 
                            echo 'zoomWindowFadeIn:'.$product_info_images->fields['product_info_zoom_window_fade_in'].',';
                        } 
                        if(!empty($product_info_images->fields['product_info_zoom_window_fade_out'])){
                            echo 'zoomWindowFadeOut:'.$product_info_images->fields['product_info_zoom_window_fade_out'].',';
                        } 
                      ?>                                                                        
                      
                      <?php if($product_info_images->fields['product_info_zoom_type'] == 2){ ?>
                        /* ----- Other Options - Only work with outer zoom ----- */                      
                        <?php 
                        if(!empty($product_info_images->fields['product_info_zoom_level'])){ 
                            echo 'zoomLevel:'.$product_info_images->fields['product_info_zoom_level'].',';
                        } 
                        if(!empty($product_info_images->fields['product_info_zoom_border_size'])){
                            echo 'borderSize:'.$product_info_images->fields['product_info_zoom_border_size'].',';
                        }
                        if(!empty($product_info_images->fields['product_info_zoom_offset_x'])){ 
                            echo 'zoomWindowOffetx:'.$product_info_images->fields['product_info_zoom_offset_x'].',';
                        } 
                        if(!empty($product_info_images->fields['product_info_zoom_offset_y'])){
                            echo 'zoomWindowOffety:'.$product_info_images->fields['product_info_zoom_offset_y'].',';
                        } 

                        /* ----- Tint Options ----- */
                        if($product_info_images->fields['product_info_zoom_tint'] == 1){
                            echo 'tint:true,';

                            if(!empty($product_info_images->fields['product_info_zoom_tint_color'])){
                                echo "tintColour:'".$product_info_images->fields['product_info_zoom_tint_color']."',";
                            } 
                            if(!empty($product_info_images->fields['product_info_zoom_tint_opacity'])){
                                echo 'tintOpacity:'.$product_info_images->fields['product_info_zoom_tint_opacity'].',';
                            }
                        }                        
                      } ?>

                      gallery:'productSlider', 
                      galleryActiveClass: 'active'
                    });
                  }
                  
                  /* ----- Header reviews scroll to me ----- */
                  $('.nrRev').on('click',function(e){
                    e.preventDefault();                    
                    $('#tablist1-tab3').click(); // NOTE: if your reviews tab is not the third tab please change it to fit your settings
                    $.scrollTo('#tablist1-tab3', 800, { offset:{ top:-headerTotalHeight*2,left:0 } });                                        
                    
                    return false;
                  });
                  /**
                   * --------------------------------------------------------------------------------------------------
                   * Swipebox and prettyPhoto
                   * --------------------------------------------------------------------------------------------------
                   */
                  <?php 
                    switch ($product_info_images->fields['product_info_large_image_display']) {
                      case 1: ?> $(".swipebox").swipebox({ useCSS : true, hideBarsDelay : 0 }); <?php break;
                      case 2: ?> $("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',slideshow:3000}); <?php break;                      
                    }
                  ?>

                  $("#productImage").click(function(e){
                    e.preventDefault();
                    var url = $(this).attr('src');             
                    $('.swipeWrapper a[data-image-swipe="'+ url +'"] ').click();          
                  });

                  
                  /**
                   * --------------------------------------------------------------------------------------------------
                   * Product Info Slider
                   * --------------------------------------------------------------------------------------------------
                   */
                  $('.productslider').flexslider({
                    namespace: 'product-',
                    controlNav: false, 
                    animation: "slide",
                    animationLoop: false,
                    slideshow: false,
                    itemWidth: 210,
                    itemMargin: 10,
                    minItems: 3,
                    maxItems: 5
                  });



                  /* ------- Product Info Tabs ------- */ 
                  RESPONSIVEUI.responsiveTabs(); 
         
        <?php } ?>        

    });
  
    /**
     * --------------------------------------------------------------------------------------------------
     * Custom Javascript Call
     * --------------------------------------------------------------------------------------------------
     */ 
    <?php 
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_CUSTOM_JAVASCRIPT, 'false'));
    ?>

</script>