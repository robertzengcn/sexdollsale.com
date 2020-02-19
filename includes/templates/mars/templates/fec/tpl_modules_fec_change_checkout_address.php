<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=checkout_shipping_adresss.<br />
 * Allows customer to change the shipping address.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_checkout_shipping_address_default.php 4852 2006-10-28 06:47:45Z drbyte $
 */
?>
<div id="mainCheckoutShippingAddress">
<?php if ($messageStack->size('checkout_address') > 0) echo $messageStack->output('checkout_address'); ?>
<?php
  if ($process == false || $error == true) {
    if ($addresses_count > 0) {
?>
  <div class="chooseAddress">
    <fieldset>
    <legend><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></legend>
    <?php
          require($template->get_template_dir('tpl_modules_fec_change_checkout_address_book.php', DIR_WS_TEMPLATE, $current_page_base,'templates/fec'). '/' . 'tpl_modules_fec_change_checkout_address_book.php');
    ?>
      <br class="clearBoth" />
      <div class="buttonRow forward"><?php echo zen_draw_hidden_field('fec_change_address', 'submit') . zen_image_submit(BUTTON_IMAGE_SUBMIT, BUTTON_CONTINUE_ALT); ?></div>
      <br class="clearBoth" />
    </fieldset>
  </div>
<?php
    }
    if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
?>
  <div class="changeAddress">
<?php
		/**
		* require template to display new address form
		*/
		  require($template->get_template_dir('tpl_modules_fec_change_checkout_new_address.php', DIR_WS_TEMPLATE, $current_page_base,'templates/fec'). '/' . 'tpl_modules_fec_change_checkout_new_address.php');
?>
  </div> 
<?php	
    } else {
      echo TEXT_ADDRESS_BOOK_FULL;
    }
  }

?>
<?php
  if ($process == true) {
?>
  <div class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>

<?php
  }
?>
</div>