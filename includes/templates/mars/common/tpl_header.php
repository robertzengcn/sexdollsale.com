<?php
/**
 * Common Template - tpl_header.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_header.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_header = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: Ian Wilson  Tue Aug 14 14:56:11 2012 +0100 Modified in v1.5.1 $
 */
require(DIR_WS_MODULES . zen_get_module_directory('header.php'));
?>

<?php
  // Display all header alerts via messageStack:
  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
  if (isset($_GET['error_message']) && zen_not_null($_GET['error_message'])) {
  echo htmlspecialchars(urldecode($_GET['error_message']), ENT_COMPAT, CHARSET, TRUE);
  }
  if (isset($_GET['info_message']) && zen_not_null($_GET['info_message'])) {
   echo htmlspecialchars($_GET['info_message'], ENT_COMPAT, CHARSET, TRUE);
} else {

}
?>

<!--bof-header logo and navigation display-->
<?php
if (!isset($flag_disable_header) || !$flag_disable_header) {
?>

<!--bof-navigation display-->
  <div id="mapWrapper"><div id="map">&nbsp;</div></div>
  <header>
    <div class="container">       
      <div class="headerLinksCont"> 
        <ul id="headerNav" class="headerLinks">
           <?php 
             foreach ($header_links->fields as $key => $value) {          
               if(isset($value) and $value > 0 and $key == 'header_home'){              echo '<li><a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'.HEADER_TITLE_CATALOG.'</a></li>';  }
               if(isset($value) and $value > 0 and $key == 'header_specials'){          echo '<li><a href="'. zen_href_link(FILENAME_SPECIALS) .'">'.HEADER_SPECIALS.'</a></li>'; }
               if(isset($value) and $value > 0 and $key == 'header_new_products'){      echo '<li><a href="'. zen_href_link(FILENAME_PRODUCTS_NEW) .'">'.HEADER_NEW_PRODUCTS.'</a></li>'; }
               if(isset($value) and $value > 0 and $key == 'header_featured_products'){ echo '<li><a href="'. zen_href_link(FILENAME_FEATURED_PRODUCTS) .'">'.HEADER_FEATURED_PRODUCTS.'</a></li>'; }
               if(isset($value) and $value > 0 and $key == 'header_all_products'){      echo '<li><a href="'. zen_href_link(FILENAME_PRODUCTS_ALL) .'">'.HEADER_ALL_PRODUCTS.'</a></li>'; }
               if(isset($value) and $value > 0 and $key == 'header_reviews'){           echo '<li><a href="'. zen_href_link(FILENAME_REVIEWS) .'">'.HEADER_REVIEWS.'</a></li>'; }
               if(isset($value) and $value > 0 and $key == 'header_contact_us'){        echo '<li><a href="'. zen_href_link(FILENAME_CONTACT_US) .'">'.HEADER_CONTACT_US.'</a></li>'; }
               if(isset($value) and $value > 0 and $key == 'header_faqs'){              echo '<li><a href="'. zen_href_link(FILENAME_GV_FAQ) .'">'.HEADER_FAQS.'</a></li>'; }     
             }
           ?>
         </ul> 
      </div>
      
      <?php if($javascript_options->fields['header_google_maps_display'] == 1){ ?>
        <a class="mapBtn tooltip" title="Map" href="#"><div><span>Maps</span></div></a>
      <?php } ?>

      <ul class="headerInfo">
              
              <?php if ($_SESSION['customer_id']) { ?>
                      <li class="user"><a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo ($_SESSION['customer_first_name'].' '.$_SESSION['customer_last_name']);?></a></li>                  
              <?php } ?>
                               
              <?php require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_LANGUAGES_HEADER)); ?>               
              <?php require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CURRENCIES_HEADER)); ?>

              <?php if ($_SESSION['customer_id']) { ?>              
                      <li class="login"><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>              
              <?php } else { if (STORE_STATUS == '0') { ?>
                      <li class="login"><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a></li>
              <?php         } 
                    } 
              ?>    
  

      </ul>
      <!-- <br class="clearBoth" /> -->
   </div>
  </header>
<!--eof-navigation display-->

<!--bof-branding display-->
<div id="headerWrapper">
  <div class="container">
    <div id="logoWrapper">
        <h1 id="logo"><?php echo '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">' . zen_image($template->get_template_dir(HEADER_LOGO_IMAGE, DIR_WS_TEMPLATE, $current_page_base,'images'). '/' . HEADER_LOGO_IMAGE, HEADER_ALT_TEXT) . '</a>'; ?></h1>
    <?php if (HEADER_SALES_TEXT != '' || (SHOW_BANNERS_GROUP_SET2 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET2))) { ?>
        <div id="taglineWrapper">
    <?php
      if (HEADER_SALES_TEXT != '') {
    ?>
          <div id="tagline"><?php echo HEADER_SALES_TEXT;?></div>
    <?php
      }  
    ?>          
            <ul class="mainSearchWrapper">
               <li class="mainSearch"><?php require(DIR_WS_MODULES . 'sideboxes/search_header.php'); ?></li>
               <li class="cartContainer">                              
                  <div>
                    <?php                    
                      if ($_SESSION['cart']->count_contents() != 0) { ?>
                        <div class="items"><strong>Cart:</strong> <?php echo $_SESSION['cart']->count_contents(); ?> <?php echo HEADER_CART_ITEMS; ?></div>
                        <div class="productWrapper">
                          <div class="productWrapperInner">
                            <div class="products">
                              <?php
                                $productsArray = $_SESSION['cart']->get_products();
                                $products = "";
                                foreach ($productsArray as $product) {

                                  $productImage = (IMAGE_SHOPPING_CART_STATUS == 1 ? zen_image(DIR_WS_IMAGES . $product['image'], $product['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '');
                                  $linkProductName = zen_href_link(zen_get_info_page($product['id']), 'products_id=' . $product['id']);  
                                  $linkProductDelete = zen_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&product_id=' . $product['id']);

                                  $products.= '
                                    <ul class="product">
                                      <li class="img"><a href="'.$linkProductName.'">'. $productImage .'</a></li>
                                      <li class="name"><strong>'. $product['quantity'] .''. BOX_SHOPPING_CART_DIVIDER .'</strong> <a href="'.$linkProductName.'">'. $product['name'] .'</a></li>
                                      <li class="amount">'. $currencies->format($product['final_price']) .'</li>                                  
                                      <li class="delete"><a href="'.$linkProductDelete.'">delete</a></li>
                                    </ul>    
                                  ';
                                } 

                                echo $products; 
                              ?>
                            </div>
                            <p><strong> <?php echo HEADER_CART_SUBTOTAL .''. $currencies->format($_SESSION['cart']->show_total()); ?></strong></p>
                            <ul class="optionsWrapper group">
                              <li><a class="btn btn_2" href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a></li>
                              <li><a class="btn" href="<?php echo zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CHECKOUT; ?></a></li>
                            </ul>
                          </div>                      
                        </div>
                    <?php }else{ ?>
                        <div class="items empty"><strong>Cart:</strong> <?php echo $_SESSION['cart']->count_contents(); ?> <?php echo HEADER_CART_ITEMS; ?></div>                      
                    <?php } ?>
                  </div>


               </li>
            </ul>           

    <?php 
                  if (SHOW_BANNERS_GROUP_SET2 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET2)) {
                    if ($banner->RecordCount() > 0) {
    ?>
          <div id="bannerTwo" class="banners"><?php echo zen_display_banner('static', $banner);?></div>
    <?php
                    }
                  }
    ?>
        </div>
    <?php } // no HEADER_SALES_TEXT or SHOW_BANNERS_GROUP_SET2 ?>
    </div>
  </div>
</div>
<!--eof-branding display-->

<!--eof-header logo and navigation display-->

<!--bof-optional categories tabs navigation display-->
<?php require($template->get_template_dir('tpl_modules_categories_tabs.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_categories_tabs.php'); ?>
<!--eof-optional categories tabs navigation display-->

<!--bof-header ezpage links-->
<?php if (EZPAGES_STATUS_HEADER == '1' or (EZPAGES_STATUS_HEADER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
<?php require($template->get_template_dir('tpl_ezpages_bar_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_header.php'); ?>
<?php } ?>
<!--eof-header ezpage links-->

<!-- bof  breadcrumb -->
<?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
<div class="breadCrumbWrapper">
    <div id="navBreadCrumb" class="container"><?php echo $breadcrumb->trail(BREAD_CRUMBS_SEPARATOR); ?></div>
</div>  
<?php } ?>
<!-- eof breadcrumb -->

<?php } ?>