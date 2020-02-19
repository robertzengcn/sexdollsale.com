<?php
/**
 * Common Template - tpl_main_page.php
 *
 * Governs the overall layout of an entire page<br />
 * Normally consisting of a header, left side column. center column. right side column and footer<br />
 * For customizing, this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * - make a directory /templates/my_template/privacy<br />
 * - copy /templates/templates_defaults/common/tpl_main_page.php to /templates/my_template/privacy/tpl_main_page.php<br />
 * <br />
 * to override the global settings and turn off columns un-comment the lines below for the correct column to turn off<br />
 * to turn off the header and/or footer uncomment the lines below<br />
 * Note: header can be disabled in the tpl_header.php<br />
 * Note: footer can be disabled in the tpl_footer.php<br />
 * <br />
 * $flag_disable_header = true;<br />
 * $flag_disable_left = true;<br />
 * $flag_disable_right = true;<br />
 * $flag_disable_footer = true;<br />
 * <br />
 * // example to not display right column on main page when Always Show Categories is OFF<br />
 * <br />
 * if ($current_page_base == 'index' and $cPath == '') {<br />
 *  $flag_disable_right = true;<br />
 * }<br />
 * <br />
 * example to not display right column on main page when Always Show Categories is ON and set to categories_id 3<br />
 * <br />
 * if ($current_page_base == 'index' and $cPath == '' or $cPath == '3') {<br />
 *  $flag_disable_right = true;<br />
 * }<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_main_page.php 7085 2007-09-22 04:56:31Z ajeh $
 */



// the following IF statement can be duplicated/modified as needed to set additional flags
  if (in_array($current_page_base,explode(",",'list_pages_to_skip_all_right_sideboxes_on_here,separated_by_commas,and_no_spaces')) ) {
    $flag_disable_right = true;
  }

  $header_template = 'tpl_header.php';
  $footer_template = 'tpl_footer.php';
  $left_column_file = 'column_left.php';
  $right_column_file = 'column_right.php';
  $body_id = ($this_is_home_page) ? 'indexHome' : str_replace('_', '', $_GET['main_page']);

  $gsl = $general_site_options->fields['general_site_layout'];
  $gstl = $general_site_options->fields['general_site_text_style'];

  //Button style  
  $gbs = $general_site_options->fields['general_button_style'];
  // $gbs = 2;

  //Item hover style  
  $gihs = $general_site_options->fields['general_item_hover_style'];
  // $gihs = 3;

  $body_class = "";
  if($gsl == 2){ $body_class .= 'boxed '; }
  if($gstl == 2) { $body_class .= 'uppercase '; }

  if($gbs == 1) { $body_class .= 'btn_st_1 '; }
  if($gbs == 2) { $body_class .= 'btn_st_2 '; }
  if($gbs == 3) { $body_class .= 'btn_st_3 '; }
  if($gbs == 4) { $body_class .= 'btn_st_4 '; }


  if($gihs == 2) { $body_class .= 'item_hst_2 '; }  
  if($gihs == 3) { $body_class .= 'item_hst_3 '; }  
  if($gihs == 4) { $body_class .= 'item_hst_4 '; }  

  $body_class = !empty($body_class) ? 'class="'.$body_class.'"' : "";

  // $body_class = ($general_site_options->fields['general_site_layout'] == 2) ? 'class="boxed"' : "";
?>
<body id="<?php echo $body_id . 'Body'; ?>"<?php if($zv_onload !='') echo ' onload="'.$zv_onload.'"'; ?> <?php echo $body_class; ?> >
  <div class="boxedWrapper">
    <?php   
      if($javascript_options->fields['header_google_analytics_display'] == 1){
        /**
         * include google analytics if it´s enabled in admin
         */  
        require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HEADER_GOOGLE_ANALYTICS, 'false')); 
      }

      if($general_site_options->fields['general_loader_display'] == 1){  
    ?>  
    
      <!--[if !IE]> -->
        <div id="loader">
          <div id="css_loader">
            <div class="outer_circle"></div>
            <div class="innser_circle"></div>
          </div>
        </div>
      <!-- <![endif]-->
      <!--[if IE]>
        <div id="loader">  
          <?php echo zen_image($template->get_template_dir('loader.gif', DIR_WS_TEMPLATE, $current_page_base,'images'). '/loader.gif', 'Loader'); ?>
        </div>  
      <![endif]-->

    <?php } ?>
    <!--[if lt IE 8]>
        <p class="outdated">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
    <?php 
    /**
     * include the necessary fb files for the footer Facebook setup
     */      
      require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_FB_INCLUDE));

      if ($current_page_base == 'index' and $cPath == '') {
        echo '<input type="hidden" id="isHomePage" name="isHomePage" value="1">';
      }
      echo '<input type="hidden" id="homeTitle" name="homeTitle" value="'.HEADER_TITLE_CATALOG.'">';
      echo '<input type="hidden" id="homeUrl" name="homeUrl" value="'. HTTP_SERVER . DIR_WS_CATALOG .'">';
    ?>
      
    <?php
      if (SHOW_BANNERS_GROUP_SET1 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET1)) {
        if ($banner->RecordCount() > 0) {
    ?>
    <div id="bannerOne" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
    <?php
        }
      }
    ?>

    <?php
     /**
      * prepares and displays header output
      *
      */
      if (CUSTOMERS_APPROVAL_AUTHORIZATION == 1 && CUSTOMERS_AUTHORIZATION_HEADER_OFF == 'true' and ($_SESSION['customers_authorization'] != 0 or $_SESSION['customer_id'] == '')) {
        $flag_disable_header = true;
      }
      require($template->get_template_dir('tpl_header.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_header.php');?>

    <?php 
      /**
      * prepares and displays flexslider
      *
      */
      if ($current_page_base == 'index' and $cPath == '') {
        switch ($homepage_set_slider) {
          case 1: // Revolution Slider
            echo '<div class="revolutionsliderWrapper">
                    <div class="revolutionslider tp-simpleresponsive">
                 ';
              // include template specific file name defines  
              $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HOMESLIDER_REVOLUTION, 'false');      
              require($define_page);       
            echo '  </div>
                  </div>';
            break;
          
          case 2: // Flex Slider
            echo '<div class="flexslider">';
              // include template specific file name defines  
              $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_HOMESLIDER_FLEX, 'false');      
              require($define_page);       
            echo '</div>';
            break;
        }
        
        
      }
    ?>

    <div class="mainWrapper container">
    <div class="section group">
    <?php
    if (COLUMN_LEFT_STATUS == 0 || (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == '') || (CUSTOMERS_APPROVAL_AUTHORIZATION == 1 && CUSTOMERS_AUTHORIZATION_COLUMN_LEFT_OFF == 'true' and ($_SESSION['customers_authorization'] != 0 or $_SESSION['customer_id'] == ''))) {
      // global disable of column_left
      $flag_disable_left = true;
    }

    if (COLUMN_RIGHT_STATUS == 0 || (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == '') || (CUSTOMERS_APPROVAL_AUTHORIZATION == 1 && CUSTOMERS_AUTHORIZATION_COLUMN_RIGHT_OFF == 'true' and ($_SESSION['customers_authorization'] != 0 or $_SESSION['customer_id'] == ''))) {
      // global disable of column_right
      $flag_disable_right = true;
    }

    /**
      * homepage layout setup, overwrites global settings on homepage 
      *
      */
    if ($current_page_base == 'index' and $cPath == '') {    
      switch ($homepage_options->fields['homepage_layout']) {
        case 1: $flag_disable_left = true;  $flag_disable_right = true; break; // Full Width
        case 2: $flag_disable_right = true; $flag_disable_left = false; break;  // Left + Middle
        case 3: $flag_disable_left = true;  $flag_disable_right = false; break; // Middle + Right      
        case 4: $flag_disable_left = false; $flag_disable_right = false; break; // Left + Middle + Right   
      }    
    }

    /**
      * product info layout setup, overwrites global settings on product pages 
      *
      */
    if ($current_page == "product_info" || 
        $current_page == "product_music_info" ||
        $current_page == "product_free_shipping_info" ||  
        $current_page == "document_general_info" ||
        $current_page == "document_product_info") {    
      switch ($product_info_options->fields['product_info_layout']) {
        case 1: $flag_disable_left = true;  $flag_disable_right = true; break; // Full Width
        case 2: $flag_disable_right = true; $flag_disable_left = false; break;  // Left + Middle
        case 3: $flag_disable_left = true;  $flag_disable_right = false; break; // Middle + Right      
        case 4: $flag_disable_left = false; $flag_disable_right = false; break; // Left + Middle + Right   
      }    
    }

    // 3 Column Layout
    $asideClass="";
    $sectionClass="col_2_of_4";
    if($flag_disable_left && !$flag_disable_right){
      // 2 Column Layout - Left Active
      $asideClass="rightOn";
      $sectionClass="col_3_of_4 rightOn";  
    }else if($flag_disable_right && !$flag_disable_left){
      // 2 Column Layout - Right Active 
      $sectionClass="col_3_of_4";  
    }else if($flag_disable_left && $flag_disable_right){  
      // Fullwidth
      $sectionClass="col_12_of_12";  
    }

    ?>
    
        <?php
    if (!isset($flag_disable_left) || !$flag_disable_left) {
    ?>
    <aside class="columnLeft col col_1_of_4">
    <?php
     /**
      * prepares and displays right column sideboxes
      *
      */
    ?>
    <?php require(DIR_WS_MODULES . zen_get_module_directory('column_left.php')); ?>
    </aside>
    <?php
    } 
    ?>
    
    <section class="columnCenter col <?php echo $sectionClass; ?>">

    <?php
      if (SHOW_BANNERS_GROUP_SET3 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET3)) {
        if ($banner->RecordCount() > 0) {
    ?>
    <div id="bannerThree" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
    <?php
        }
      }
    ?>

    <!-- bof upload alerts -->
    <?php if ($messageStack->size('upload') > 0) echo $messageStack->output('upload'); ?>
    <!-- eof upload alerts -->

    <?php
     /**
      * prepares and displays center column
      *
      */
     require($body_code); ?>

    <?php
      if (SHOW_BANNERS_GROUP_SET4 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET4)) {
        if ($banner->RecordCount() > 0) {
    ?>
    <div id="bannerFour" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
    <?php
        }
      }
    ?>
    </section>


<?php 
    if (!isset($flag_disable_right) || !$flag_disable_right) {
    ?>

    <aside class="columnRight col col_1_of_4 <?php echo $asideClass; ?>">
    <?php
     /**
      * prepares and displays left column sideboxes
      *
      */
    ?>
    <?php require(DIR_WS_MODULES . zen_get_module_directory('column_right.php')); ?>
    </aside>
    <?php
    }
    ?>

    </div>
    </div>


    <footer>
        <?php
         /**
          * prepares and displays footer output
          *
          */
          if (CUSTOMERS_APPROVAL_AUTHORIZATION == 1 && CUSTOMERS_AUTHORIZATION_FOOTER_OFF == 'true' and ($_SESSION['customers_authorization'] != 0 or $_SESSION['customer_id'] == '')) {
            $flag_disable_footer = true;
          }
          require($template->get_template_dir('tpl_footer.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_footer.php');
        ?>

        <!--bof- parse time display -->
        <?php
          if (DISPLAY_PAGE_PARSE_TIME == 'true') {
        ?>
        <div class="smallText center">Parse Time: <?php echo $parse_time; ?> - Number of Queries: <?php echo $db->queryCount(); ?> - Query Time: <?php echo $db->queryTime(); ?></div>
        <?php
          }
        ?>
        <!--eof- parse time display -->
        <!--bof- banner #6 display -->
        <?php
          if (SHOW_BANNERS_GROUP_SET6 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET6)) {
            if ($banner->RecordCount() > 0) {
        ?>
        <hr class="sep">
        <div class="container">
          <div id="bannerSix" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
        </div>
        <?php
            }
          }
        ?>
        <!--eof- banner #6 display -->

    </footer>
  </div>
  
  <!-- $body_class = ($general_site_options->fields['sideblock_left_display'] == 1) ? 'class="boxed"' : ""; -->
  <?php if($general_site_options->fields['sideblock_left_display'] == 1){ 
          $sideblock_left_width = (!empty($general_site_options->fields['sideblock_left_width'])) ? 'style="width:'. $general_site_options->fields['sideblock_left_width'] .'px; left:-'. $general_site_options->fields['sideblock_left_width'] .'px;"' : '';
  ?>
          <div class="sideblockLeft" <?php echo $sideblock_left_width; ?> >
            <div class="sideblockIcon"><span>Left Sideblock</span></div>
            <div class="sideblockContent">
              <?php require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_CUSTOM_SIDEBLOCK_LEFT, 'false'));  ?>
            </div>
          </div>
  <?php }
        if($general_site_options->fields['sideblock_right_display'] == 1){
          $sideblock_right_width = (!empty($general_site_options->fields['sideblock_right_width'])) ? 'style="width:'. $general_site_options->fields['sideblock_right_width'] .'px; right:-'. $general_site_options->fields['sideblock_right_width'] .'px;"' : '';
  ?>
          <div class="sideblockRight" <?php echo $sideblock_right_width; ?> >
            <div class="sideblockIcon"><span>Right Sideblock</span></div>
            <div class="sideblockContent">
              <?php require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_CUSTOM_SIDEBLOCK_RIGHT, 'false'));  ?>
            </div>
          </div>
  <?php } ?>
      
  <?php echo '<script type="text/javascript" src="' .  $template->get_template_dir('.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.retina.js"></script>'."\n";     ?>

</body>