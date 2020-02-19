<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=product_info.<br />
 * Displays details of a typical product
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_product_info_display.php 16242 2010-05-08 16:05:40Z ajeh $
 */
 //require(DIR_WS_MODULES . '/debug_blocks/product_info_prices.php');
?>


<script type="text/javascript">
    $(document).ready(function() {
        jQuery(".content").hide();
        //toggle the componenet with class msg_body
        jQuery(".heading").click(function()
                                 {
                                   jQuery(this).next(".content").slideToggle(500);
                                   jQuery(this).toggleClass("minus");
                                 });
      });


</script>


<div itemscope itemtype="http://schema.org/Product" class="centerColumn" id="productGeneral">

<!--bof Form start-->
<?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product', $request_type), 'post', 'enctype="multipart/form-data"') . "\n"; ?>
<!--eof Form start-->

<?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>

<!--bof Category Icon -->
<?php if ($module_show_categories != 0) {?>
<?php
/**
 * display the category icons
 */
require($template->get_template_dir('/tpl_modules_category_icon_display.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_category_icon_display.php'); ?>
<?php } ?>
<!--eof Category Icon -->

<!--bof Prev/Next top position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 1 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next top position-->

<div id="p-left">
<!--bof Main Product Image -->
<?php
  if (zen_not_null($products_image)) {
  ?>
<?php
/**
 * display the main product image
 */
   require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
<?php
  }
?>
<!--eof Main Product Image-->

<link rel="image_src" href="<?php echo "http://" . $_SERVER['HTTP_HOST'] . "/images/" . $products_image; ?>" />
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>

</div>
<!--<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>-->
<script type="text/javascript" rel="nofollow" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51d820a75878ded1"></script>
<!-- AddThis Button END -->
</div>

<div id="p-right">
<!--bof Product Name-->
<h1 id="productName" class="productGeneral"><span itemprop="name"><?php echo $products_name; ?></span></h1>
<!--eof Product Name-->

<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" id="cart">


<!--bof free ship icon  -->

<div id="freeShippingIcon"><?php echo TEXT_PRODUCT_FREE_SHIPPING_ICON; ?></div>

<!--eof free ship icon  -->

<?php // 2P modified BOF - Average Product Rating
  echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><p class="reviewCount">';
  $flag_show_product_info_reviews_count=1;
  if ($flag_show_product_info_reviews_count == 1) {
    echo TEXT_CURRENT_REVIEWS . ' <strong><span itemprop="reviewCount">' . $reviews->fields['count'] . '</span></strong><br />';
    $stars_image_suffix = str_replace('.', '_', zen_round($reviews_average_rating->fields['average_rating'] * 2, 0) / 2); // for stars_0_5.gif, stars_1.gif, stars_1_5.gif etc.
    $average_rating = zen_round($reviews_average_rating->fields['average_rating'], 2);
    echo TEXT_CURRENT_REVIEWS_RATING . ' <strong><span itemprop="ratingValue">' . $average_rating . '</span></strong> ' . zen_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $stars_image_suffix . '.gif', sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $average_rating));
  } else {
    echo '';
  }
  echo '</p></div>';
// 2P modified EOF - Average Product Rating ?>

<!--bof Product Price block -->
<h2 id="productPrices" class="productGeneral">
Price:
<?php
// base price
  if ($show_onetime_charges_description == 'true') {
    $one_time = '<span >' . TEXT_ONETIME_CHARGE_SYMBOL . TEXT_ONETIME_CHARGE_DESCRIPTION . '</span><br />';
  } else {
    $one_time = '';
  }
  echo $one_time . ((zen_has_product_attributes_values((int)$_GET['products_id']) and $flag_show_product_info_starting_at == 1) ? TEXT_BASE_PRICE : '') . zen_get_products_display_price((int)$_GET['products_id']);
?></h2>
<br class="clearBoth">
<!--eof Product Price block -->


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
    $the_button = PRODUCTS_ORDER_QTY_TEXT . '<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" /><br />' . zen_get_products_quantity_min_units_display((int)$_GET['products_id']) . '<br />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id']) . zen_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
            }
    $display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);
  ?>
  <?php if ($display_qty != '' or $display_button != '') { ?>
    <div id="cartAdd">
    <?php
      echo $display_qty;
      echo $display_button;
            ?>
          </div>
  <?php } // display qty and button ?>
<?php } // CUSTOMERS_APPROVAL == 3 ?>
<!--eof Add to Cart Box-->
</div>
</div>

<!--bof Form close-->
</form>
<!--bof Form close-->
<br class="clearBoth" /> 
<hr id="product-divider" />

<div class="layer1">
    <p class="heading"><?php echo PRODUCT_DETAILS; ?></p>
<div class="content">
<!--bof Product details list  -->
<?php if ( (($flag_show_product_info_model == 1 and $products_model != '') or ($flag_show_product_info_weight == 1 and $products_weight !=0) or ($flag_show_product_info_quantity == 1) or ($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name))) ) { ?>
<ul id="productDetailsList" class="floatingBox back">
  <?php echo (($flag_show_product_info_model == 1 and $products_model !='') ? '<li>' . TEXT_PRODUCT_MODEL . $products_model . '</li>' : '') . "\n"; ?>
  <?php echo (($flag_show_product_info_weight == 1 and $products_weight !=0) ? '<li>' . TEXT_PRODUCT_WEIGHT .  $products_weight . TEXT_PRODUCT_WEIGHT_UNIT . '</li>'  : '') . "\n"; ?>

  <?php echo (($flag_show_product_info_manufacturer == 1 and !empty($manufacturers_name)) ? '<li>' . TEXT_PRODUCT_MANUFACTURER . $manufacturers_name . '</li>' : '') . "\n"; ?>
</ul>
<br class="clearBoth" />
<?php
  }
?>
<!--eof Product details list -->
</div>
</div>

<div class="layer1"> 
    <p class="heading"><?php echo PRODUCT_DESCRIPTION; ?></p>
<div class="content">
<!--bof Product description -->
<?php if ($products_description != '') { ?>
<div itemprop="description" id="productDescription" class="productGeneral biggerText"><?php echo stripslashes($products_description); ?></div>
<?php } ?>
<!--eof Product description -->
</div>
</div>



<br class="clearBoth" />



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

<!--bof Additional Product Images -->
<?php
/**
 * display the products additional images
 */
  require($template->get_template_dir('/tpl_modules_additional_images.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_additional_images.php'); ?>
<!--eof Additional Product Images -->

<!--bof Prev/Next bottom position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
 require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next bottom position -->

<hr id="product-divider" />

<!--bof Tell a Friend button -->
<?php
  if ($flag_show_product_info_tell_a_friend == 1) { ?>
<div id="productTellFriendLink" class="buttonRow forward"><?php echo ($flag_show_product_info_tell_a_friend == 1 ? '<a href="' . zen_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $_GET['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_TELLAFRIEND, BUTTON_TELLAFRIEND_ALT) . '</a>' : ''); ?></div>
<?php
  }
?>
<!--eof Tell a Friend button -->

<!-- added for dgReview on product page -->

	<?php require($template->get_template_dir('tpl_dgReview.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_dgReview.php');?>
	
<!-- added for dgReview on product page -->

<!--bof show review in product page -->
<?php require($template->get_template_dir('tpl_product_reviews_write_default.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_product_reviews_write_default.php');?> 
<!--eof show review in product page -->

<!--bof Reviews button and count-->
<?php
$flag_show_product_info_reviews = 1;
  if ($flag_show_product_info_reviews == 1) {
    // if more than 0 reviews, then show reviews button; otherwise, show the "write review" button
    if ($reviews->fields['count'] > 0 ) { ?>
<div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>'; ?></div>
<br class="clearBoth" />
<p class="reviewCount"><?php echo ($flag_show_product_info_reviews_count == 1 ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : ''); ?></p>

<?php } else { ?>
<div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?></div>
<br class="clearBoth" />
<?php
  }
}
?>
<!--eof Reviews button and count -->

    <?php
	   require($template->get_template_dir('tpl_product_info_similar.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_product_info_similar.php');
	?>




<!--bof Product date added/available-->
<?php
  if ($products_date_available > date('Y-m-d H:i:s')) {
    if ($flag_show_product_info_date_available == 1) {
?>
  <p id="productDateAvailable" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
<?php
    }
  } else {
    if ($flag_show_product_info_date_added == 1) {
?>
      <p id="productDateAdded" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
<?php
    } // $flag_show_product_info_date_added
  }
?>
<!--eof Product date added/available -->

<!--bof Product URL -->
<?php
  if (zen_not_null($products_url)) {
    if ($flag_show_product_info_url == 1) {
?>
    <p id="productInfoLink" class="productGeneral centeredContent"><?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?></p>
<?php
    } // $flag_show_product_info_url
  }
?>
<!--eof Product URL -->



<!--bof also purchased products module-->
<?php require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
<!--eof also purchased products module-->




</div>