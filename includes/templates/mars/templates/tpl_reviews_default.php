<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_reviews_default.php 2905 2006-01-28 01:25:36Z birdbrain $
 */
?>
<div class="centerColumn" id="reviewsDefault">

<h1 id="reviewsDefaultHeading"><?php echo $breadcrumb->last();  ?></h1>

<?php
  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
<div class="navSplitPagesWrapper">
  <div id="reviewsDefaultListingTopNumber" class="navSplitPagesResult back"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
  <div id="reviewsDefaultListingTopLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'main_page'))); ?></div>
</div>
<ul class="reviewListingList">
<?php
    }

    $reviews = $db->Execute($reviews_split->sql_query);
    while (!$reviews->EOF) {
?>
<li class="reviewsDefaultItem section group">

  <div class="col col_1_of_4 smallProductImage"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews->fields['products_id'] . '&reviews_id=' . $reviews->fields['reviews_id']) . '">' . zen_image(DIR_WS_IMAGES . $reviews->fields['products_image'], $reviews->fields['products_name'], IMAGE_PRODUCT_LISTING_WIDTH, IMAGE_PRODUCT_LISTING_HEIGHT) . '</a>'; ?></div>

  <div class="col col_2_of_4 listing">
    <h2><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews->fields['products_id'] . '&reviews_id=' . $reviews->fields['reviews_id']) . '">'. $reviews->fields['products_name']. '</a>'; ?> </h2>
    
    <div class="rating"><?php echo zen_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $reviews->fields['reviews_rating'] . '.png', sprintf(TEXT_OF_5_STARS, $reviews->fields['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews->fields['reviews_rating']); ?></div>
    
    <div class="content"><?php echo zen_break_string(nl2br(zen_output_string_protected(stripslashes($reviews->fields['reviews_text']))), 60, '-<br />') . ((strlen($reviews->fields['reviews_text']) >= 100) ? '...' : ''); ?></div> 
    
    <div class="bold"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, zen_date_short($reviews->fields['date_added'])); ?>&nbsp;<?php echo sprintf(TEXT_REVIEW_BY, zen_output_string_protected($reviews->fields['customers_name'])); ?></div>
  </div>
  
  <div class="col col_1_of_4">
    <div class="buttonRow"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews->fields['products_id'] . '&reviews_id=' . $reviews->fields['reviews_id']) . '">' . zen_image_button(BUTTON_IMAGE_READ_REVIEWS , BUTTON_READ_REVIEWS_ALT) . '</a>'; ?></div>
    <div class="buttonRow"><?php echo '<a href="' . zen_href_link(zen_get_info_page($reviews->fields['products_id']), 'products_id=' . $reviews->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS , BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>'; ?></div>
  </div>

</li>
<?php
      $reviews->MoveNext();
    }
?>
</ul>
<?php
  } else {
?>
<div id="reviewsDefaultNoReviews" class="content"><?php echo TEXT_NO_REVIEWS; ?></div>
<?php
  }
?>
<?php
  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
<hr />
<div class="navSplitPagesWrapper">
  <div id="reviewsDefaultListingBottomNumber" class="navSplitPagesResult back"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></div>
  <div id="reviewsDefaultListingBottomLinks" class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'main_page'))); ?></div>
</div>
<br class="clearBoth" />
<?php
  }
?>

</div>
