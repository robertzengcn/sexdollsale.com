<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=product_music_info.<br />
 * Displays details of a music product
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_product_music_info_display.php 19690 2011-10-04 16:41:45Z drbyte $
 */
?>
<?php require_once($language_page_directory .FILENAME_PRODUCT_REVIEWS.'.php'); ?>
<div class="centerColumn" id="productMusicDisplay">

<!--bof Form start-->
<?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product', $request_type), 'post', 'enctype="multipart/form-data"') . "\n"; ?>
<!--eof Form start-->

<?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>


<?php if ($module_show_categories != 0) {?>
<!--bof Category Icon -->
<?php
/**
 * display the category icons
 */
require($template->get_template_dir('/tpl_modules_category_icon_display.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_category_icon_display.php'); ?>
<!--eof Category Icon -->
<?php } ?>



<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 1 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<!--bof Prev/Next top position -->
<?php
/**
 * display the product previous/next helper
 */
require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<!--eof Prev/Next top position-->
<?php } ?>


<!--bof Product Name-->
<h1 id="productName" class="productGeneral"><?php echo $products_name; ?></h1>
<!--eof Product Name-->

<?php 
  $smallContentClass="col_1_of_3";
  $bigContentClass="col_2_of_3";
  if(($product_info_options->fields['product_info_custom_content_1'] == 1) || ($product_info_options->fields['product_info_custom_content_2'] == 1) || ($product_info_options->fields['product_info_custom_content_3'] == 1)){
    // 2 Column Layout
    $smallContentClass="col_1_of_4";
    $bigContentClass="col_2_of_4";  
  }
?>

<div class="productInfoWrapper section group">
  <div class="col <?php echo $smallContentClass; ?>">
    <!--bof Main Product Image and Additional Product Images -->
    <?php
      if (zen_not_null($products_image)) {
      ?>
    <?php
    /**
     * display the main product image and the additional product images
     */
       require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
    <?php
      }
    ?>
    <!--eof Main Product Image and Additional Product Images -->
    </div>
  <div class="col <?php echo $bigContentClass; ?>">
    <!--bof Product Price block -->
    <h2 id="productPrices" class="productGeneral">
      <?php
      // base price
        if ($show_onetime_charges_description == 'true') {
          $one_time = '<span >' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
        } else {
          $one_time = '';
        }
        echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and $flag_show_product_info_starting_at == 1) ? TEXT_BASE_PRICE : '') . zen_get_products_display_price((int)$_GET['products_id']);
      ?>
    </h2>
    <!--eof Product Price block -->
    
    <?php if($product_info_options->fields['product_info_rating_display'] == 1){ ?>
    <!--bof Average Rating block -->
    <ul class="averageRating">
      <li><?php
            if($reviews->fields['count'] > 0) {
              echo zen_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $averageRating . '.png', sprintf(TEXT_OF_5_STARS, $averageRating)), ''; 
          }?>
      </li>
      <li class="nrRev"><a href="#" title="<?php echo TEXT_CURRENT_REVIEWS; ?>"><?php echo TEXT_CURRENT_REVIEWS .($reviews->fields['count']) ?></a></li>
      <li><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' .BUTTON_WRITE_REVIEW_ALT. '</a>'; ?></li>
    </ul>        
    <!--eof Average Rating block -->
    <?php } ?>

    <!--bof Info block -->
    <?php if (($flag_show_product_info_model == 1 and $products_model != '') or ($flag_show_product_info_quantity == 1) or $flag_show_product_music_info_artist == 1 or $flag_show_product_music_info_genre == 1) { 
        $inStock = ($products_quantity > 0) ? 'class="inStock"' : 'class="notInStock"';
    ?>
        <ul id="productInfoList" class="group">
          <?php echo (($flag_show_product_info_model == 1 and $products_model !='') ? '<li><strong>' . TEXT_PRODUCT_MODEL .'</strong> '. $products_model . '</li>' : '') . "\n"; ?>          
          <?php echo (($flag_show_product_info_quantity == 1) ? '<li><strong '.$inStock.'>' . TEXT_PRODUCT_QUANTITY .':</strong> '. $products_quantity . '</li>'  : '') . "\n"; ?>          
  	      <?php echo (($flag_show_product_music_info_artist == 1 and !empty($products_artist_name)) ? '<li><strong>' . TEXT_PRODUCT_ARTIST .'</strong> '. $products_artist_name . '</li>' : '') . "\n"; ?>
    	    <?php echo (($flag_show_product_music_info_genre == 1 and !empty($products_music_genre_name)) ? '<li><strong>' . TEXT_PRODUCT_MUSIC_GENRE .'</strong> '. $products_music_genre_name . '</li>' : '') . "\n"; ?>
        </ul>
    <?php } ?>
    <!--eof Info block -->  
    <?php 
      if($product_info_options->fields['product_info_addthis'] == 1){
        $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_ADDTHIS, 'false');
        echo '<div class="socialWrapper">';      
          require($define_page); 
        echo '</div>';
      }          
      if($product_info_options->fields['product_info_sharethis'] == 1){
        $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_SHARETHIS, 'false');      
        echo '<div class="socialWrapper">';      
          require($define_page); 
        echo '</div>';
      }
    ?> 

    <!--bof free ship icon  -->
    <?php if(zen_get_product_is_always_free_shipping($products_id_current) && $flag_show_product_info_free_shipping) { ?>
    <div id="freeShippingIcon"><?php echo TEXT_PRODUCT_FREE_SHIPPING_ICON; ?></div>
    <?php } ?>
    <!--eof free ship icon  -->
    
    <!--bof Quantity Discounts table -->
    <?php
      if ($products_discount_type != 0) { ?>
    <?php
    /**
     * display the products quantity discount
     */
     require($template->get_template_dir('/tpl_modules_products_quantity_discounts.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_products_quantity_discounts.php'); ?>
    <?php
      }
    ?>
    <!--eof Quantity Discounts table -->

    <!--bof Attributes Module -->
    <?php
      if ($pr_attr->fields['total'] > 0) {
    ?>
    <?php
    /**
     * display the product atributes
     */
      require($template->get_template_dir('/tpl_modules_attributes.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_attributes.php'); ?>
    <?php
      }
    ?>
    <!--eof Attributes Module -->


    <?php 
      if($product_info_options->fields['product_info_addthis'] == 2){
        $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_ADDTHIS, 'false');
        echo '<div class="socialWrapper2">';      
          require($define_page); 
        echo '</div>';
      }          
      if($product_info_options->fields['product_info_sharethis'] == 2){
        $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_SHARETHIS, 'false');      
        echo '<div class="socialWrapper2">';      
          require($define_page); 
        echo '</div>';
      }
    ?>

    <!--bof Add to Cart Box -->
    <?php
    if (CUSTOMERS_APPROVAL == 3 and TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM == '') {
      // do nothing
    } else {
    ?>
                <?php
        $display_qty = (($flag_show_product_info_in_cart_qty == 1 and $_SESSION['cart']->in_cart($_GET['products_id'])) ? '<p>' . PRODUCTS_ORDER_QTY_TEXT_IN_CART . $_SESSION['cart']->get_quantity($_GET['products_id']) . '</p>' : '');
                if ($products_qty_box_status == 0 or $products_quantity_order_max== 1) {
                  // hide the quantity box and default to 1
                  $the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);                  
                } else {
                  // show the quantity box                
                  $the_button = '<ul>
                                  <li class="first">' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT).'</li>
                                  <li class="middle">'.PRODUCTS_ORDER_QTY_TEXT . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" /></li>
                                  <li class="last">' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . '</li>
                                </ul>';
                }
        $display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);
      ?>
      <?php if ($display_qty != '' or $display_button != '') { ?>
        <div id="cartAdd" class="group">
        <?php
          echo $display_qty;
          echo $display_button;
        ?>
        </div>
      <?php } // display qty and button ?>
    <?php } // CUSTOMERS_APPROVAL == 3 ?>
    <!--eof Add to Cart Box-->
    
          
      <?php 
        if($product_info_options->fields['product_info_addthis'] == 3){          
          echo '<div class="socialWrapper3">';      
            require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_ADDTHIS, 'false')); 
          echo '</div>';
        }          
        if($product_info_options->fields['product_info_sharethis'] == 3){          
          echo '<div class="socialWrapper3">';      
            require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_SHARETHIS, 'false')); 
          echo '</div>';
        }        
      ?>  
  </div> 
  
  <!--bof Custom Content position -->
  <?php 
    // Custom Content in Sidebox
    if(($product_info_options->fields['product_info_custom_content_1'] == 1) || ($product_info_options->fields['product_info_custom_content_2'] == 1) || ($product_info_options->fields['product_info_custom_content_3'] == 1)){
      echo '<div class="col '. $smallContentClass .' last">';
        if($product_info_options->fields['product_info_custom_content_1'] == 1){          
          echo '<div class="customWrapper">';      
            require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_1, 'false')); 
          echo '</div>';
        }

        if($product_info_options->fields['product_info_custom_content_2'] == 1){          
          echo '<div class="customWrapper">';      
            require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_2, 'false')); 
          echo '</div>';
        }

        if($product_info_options->fields['product_info_custom_content_3'] == 1){          
          echo '<div class="customWrapper">';      
            require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_3, 'false')); 
          echo '</div>';
        }
      echo '</div>';
    }
  ?>
  <!--eof Custom Content position -->

</div>

<?php 
  if($product_info_options->fields['product_info_custom_content_1'] == 2){          
    echo '<div class="customWrapper2">';      
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_1, 'false')); 
    echo '</div>';
  }

  if($product_info_options->fields['product_info_custom_content_2'] == 2){          
    echo '<div class="customWrapper2">';      
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_2, 'false')); 
    echo '</div>';
  }

  if($product_info_options->fields['product_info_custom_content_3'] == 2){          
    echo '<div class="customWrapper2">';      
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_3, 'false')); 
    echo '</div>';
  }
?>

<div class="responsive-tabs">
  <h2><?php echo TEXT_DESCRIPTION ?></h2>
  <div>
      <!--bof Product description -->
      <?php if ($products_description != '') { ?>
      <div id="productDescription" class="group"><?php echo stripslashes($products_description); ?></div>
      <?php } ?>
      <!--eof Product description -->
  </div>

  <!--bof Product details list  -->
  <?php if ( (($flag_show_product_info_model == 1 and $products_model != '') or ($flag_show_product_info_weight == 1 and $products_weight !=0) or ($flag_show_product_info_quantity == 1) or ($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) or $flag_show_product_music_info_artist == 1 or $flag_show_product_music_info_genre == 1 ) ) { ?>
  <h2><?php echo TEXT_ADDITIONAL_INFORMATION ?></h2>
  <div>
    <ul id="productDetailsList" class="group">
      <?php echo (($flag_show_product_info_model == 1 and $products_model !='') ? '<li>' . TEXT_PRODUCT_MODEL . $products_model . '</li>' : '') . "\n"; ?>
      <?php echo (($flag_show_product_info_weight == 1 and $products_weight !=0) ? '<li>' . TEXT_PRODUCT_WEIGHT .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT . '</li>'  : '') . "\n"; ?>
      <?php echo (($flag_show_product_info_quantity == 1) ? '<li>' . $products_quantity . TEXT_PRODUCT_QUANTITY . '</li>'  : '') . "\n"; ?>
      <?php echo (($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) ? '<li>' . TEXT_PRODUCT_MANUFACTURER . $manufacturers_name . '</li>' : '') . "\n"; ?>
      <?php echo (($flag_show_product_music_info_artist == 1 and !empty($products_artist_name)) ? '<li>' . TEXT_PRODUCT_ARTIST . $products_artist_name . '</li>' : '') . "\n"; ?>
      <?php echo (($flag_show_product_music_info_genre == 1 and !empty($products_music_genre_name)) ? '<li>' . TEXT_PRODUCT_MUSIC_GENRE . $products_music_genre_name . '</li>' : '') . "\n"; ?>
    </ul>   
  </div>
  <?php } ?>

<?php
  if ($flag_show_product_info_reviews == 1) { 
?>
  <h2><?php echo TEXT_CURRENT_REVIEWS .($flag_show_product_info_reviews_count == 1 ? $reviews->fields['count'] : '')  ?></h2>
  <div class="reviews_wrapper">
    
      <!-- bof - Product reviews -->
      <?php
        if($product_info_options->fields['product_info_reviews_display_tabs'] == 1){          
          if ($reviews_split->number_of_rows > 0) {
            if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
      ?>
          <div class="navSplitPagesWrapper"> 
            <div id="productReviewsDefaultListingTopNumber" class="navSplitPagesResult back"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
            <div id="productReviewsDefaultListingTopLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'main_page'))); ?></div>
          </div>
          <?php
              }
              foreach ($reviewsArray as $reviews) {
          ?>
          <hr />

          <div class="buttonRow forward"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . (int)$_GET['products_id'] . '&reviews_id=' . $reviews['id']) . '">' . zen_image_button(BUTTON_IMAGE_READ_REVIEWS , BUTTON_READ_REVIEWS_ALT) . '</a>'; ?></div>

          <div class="productReviewsDefaultReviewer bold"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, zen_date_short($reviews['dateAdded'])); ?>&nbsp;<?php echo sprintf(TEXT_REVIEW_BY, zen_output_string_protected($reviews['customersName'])); ?></div>

          <div class="rating"><?php echo zen_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $reviews['reviewsRating'] . '.png', sprintf(TEXT_OF_5_STARS, $reviews['reviewsRating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviewsRating']); ?></div>

          <div class="productReviewsDefaultProductMainContent content"><?php echo zen_break_string(zen_output_string_protected(stripslashes($reviews['reviewsText'])), 60, '-<br />') . ((strlen($reviews['reviewsText']) >= 100) ? '...' : ''); ?></div>


          <br class="clearBoth" />
          <?php
              }
          ?>
          <?php
            } else {
          ?>
          <hr />
          <div id="productReviewsDefaultNoReviews" class="content"><?php echo TEXT_NO_REVIEWS . (REVIEWS_APPROVAL == '1' ? '<br />' . TEXT_APPROVAL_REQUIRED: ''); ?></div>
          <br class="clearBoth" />
          <?php
            }

            if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
          ?>
          <hr />
          <div class="navSplitPagesWrapper"> 
            <div id="productReviewsDefaultListingBottomNumber" class="navSplitPagesResult back"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
            <div id="productReviewsDefaultListingBottomLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'main_page'))); ?></div>
          </div>
      <?php 
          }
        } 
      ?>

      <!-- eof - Product reviews -->
    <ul id="productReviewLink" class="group">
      <?php if ($reviews->fields['count'] > 0 ) { ?>
      <li><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>'; ?></li>
      <li class="last"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?></li>
      <?php }else{ ?>
      <li><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?></li>
      <?php } ?>
    </ul>

  </div>
<?php }
  // Custom Tabs Display 
  if($product_info_options->fields['product_info_custom_tab_1'] == 1){
?>
  <h2><?php echo TEXT_CUSTOM_TAB_1 ?></h2>
  <div>
    <?php 
      $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_TAB_1, 'false');          
      require($define_page); 
    ?>
  </div>
<?php } if($product_info_options->fields['product_info_custom_tab_2'] == 1){ ?>
  <h2><?php echo TEXT_CUSTOM_TAB_2 ?></h2>
  <div>
    <?php 
      $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_TAB_2, 'false');          
      require($define_page); 
    ?>
  </div>
<?php } if($product_info_options->fields['product_info_custom_tab_3'] == 1){ ?>  
  <h2><?php echo TEXT_CUSTOM_TAB_3 ?></h2>
  <div>
    <?php 
      $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_TAB_3, 'false');          
      require($define_page); 
    ?>
  </div>
<?php } ?>  
</div>
<!--eof Product details list -->

<!--bof Custom Content position -->
<?php 
  if($product_info_options->fields['product_info_custom_content_1'] == 3){          
    echo '<div class="customWrapper3">';      
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_1, 'false')); 
    echo '</div>';
  }

  if($product_info_options->fields['product_info_custom_content_2'] == 3){          
    echo '<div class="customWrapper3">';      
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_2, 'false')); 
    echo '</div>';
  }

  if($product_info_options->fields['product_info_custom_content_3'] == 3){          
    echo '<div class="customWrapper3">';      
      require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_PRODUCT_INFO_CUSTOM_CONTENT_3, 'false')); 
    echo '</div>';
  }
?>
<!--eof Custom Content position -->

<!--bof Prev/Next bottom position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
 require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next bottom position -->


<!--bof Product date added/available-->
<?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if ($flag_show_product_info_date_available == 1) {
?>
  <p id="productDateAvailable" class="productMusic centeredContent"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
<?php
    }
  } else {
    if ($flag_show_product_info_date_added == 1) {
?>
      <p id="productDateAdded" class="productMusic centeredContent"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
<?php
    } // $flag_show_product_info_date_added
  }
?>
<!--eof Product date added/available -->

<!--bof Product URL -->
<?php
  if (zen_not_null($products_record_company_url)) {
    if ($flag_show_product_music_info_record_company == 1) {
?>
    <p id="productInfoLink" class="productMusic centeredContent"><?php echo sprintf(TEXT_RECORD_COMPANY_URL, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_record_company_url), 'NONSSL', true, false)); ?></p>
<?php
    } // $flag_show_product_info_record_company
  }
?>
<!--eof Product URL -->

<!--bof also purchased products module-->
<?php require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
<!--eof also purchased products module-->

<!--bof Form close-->
</form>
<!--bof Form close-->
</div>
