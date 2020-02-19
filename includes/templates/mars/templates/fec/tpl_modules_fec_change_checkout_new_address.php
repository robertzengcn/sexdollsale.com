<?php
/**
 * Module Template
 *
 * Allows entry of new addresses during checkout stages
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_checkout_new_address.php 4683 2006-10-07 06:11:53Z drbyte $
 */
?>
<div class="centerColumnModule checkoutNewAddress">
<fieldset>
<legend><?php echo TITLE_PLEASE_SELECT; ?></legend>
<div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
<br class="clearBoth" />

<?php
  if (ACCOUNT_GENDER == 'true') {
?>
<?php echo zen_draw_radio_field('gender', 'm', '', 'class="gender-male"') . '<label class="radioButtonLabel" title="gender-male">' . MALE . '</label>' . zen_draw_radio_field('gender', 'f', '', 'class="gender-female"') . '<label class="radioButtonLabel" title="gender-female">' . FEMALE . '</label>' . (zen_not_null(ENTRY_GENDER_TEXT) ? '<span class="alert">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
<br class="clearBoth" />
<?php
  }
?>

<label class="inputLabel" title="firstname"><?php echo ENTRY_FIRST_NAME; ?></label>
<?php echo zen_draw_input_field('firstname', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_firstname', '40') . ' class="firstname"') . (zen_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="alert">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?>
<br class="clearBoth" />

<label class="inputLabel" title="lastname"><?php echo ENTRY_LAST_NAME; ?></label>
<?php echo zen_draw_input_field('lastname', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_lastname', '40') . ' class="lastname"') . (zen_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="alert">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?>
<br class="clearBoth" />


<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
<label class="inputLabel" title="company"><?php echo ENTRY_COMPANY; ?></label>
<?php echo zen_draw_input_field('company', '', 'size="33" class="company"') . (zen_not_null(ENTRY_COMPANY_TEXT) ? '<span class="alert">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?>
<br class="clearBoth" />
<?php
  }
?>

<label class="inputLabel" title="street-address"><?php echo ENTRY_STREET_ADDRESS; ?></label>
  <?php echo zen_draw_input_field('street_address', '', 'size="33" class="street-address"') . (zen_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="alert">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?>
<br class="clearBoth" />

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<label class="inputLabel" title="suburb"><?php echo ENTRY_SUBURB; ?></label>
<?php echo zen_draw_input_field('suburb', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_suburb', '40') . ' class="suburb"') . (zen_not_null(ENTRY_SUBURB_TEXT) ? '<span class="alert">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?>
<br class="clearBoth" />
<?php
  }
?>

<label class="inputLabel" title="city"><?php echo ENTRY_CITY; ?></label>
<?php echo zen_draw_input_field('city', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_city', '40') . ' class="city"') . (zen_not_null(ENTRY_CITY_TEXT) ? '<span class="alert">' . ENTRY_CITY_TEXT . '</span>': ''); ?>
<br class="clearBoth" />

<label class="inputLabel" title="country"><?php echo ENTRY_COUNTRY; ?></label>
<?php echo zen_get_country_list('zone_country_id', $_SESSION['customer_country_id'], 'class="country" ' . ($flag_show_pulldown_states == true ? 'onchange="update_zone(this.form);"' : '')) . (zen_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="alert">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
<br class="clearBoth" />

<?php
  if (ACCOUNT_STATE == 'true') {
    if ($flag_show_pulldown_states == true) {
?>
<label class="inputLabel" for="stateZone" id="zoneLabel"><?php echo ENTRY_STATE; ?></label>
<?php
      echo zen_draw_pull_down_menu('zone_id', zen_prepare_country_zones_pull_down($selected_country), $zone_id, 'id="stateZone" class="hiddenField"');
    }
?>

<?php if ($flag_show_pulldown_states == true) { ?>
<br class="clearBoth" id="stBreak" />
<?php } ?>
<label class="inputLabel" for="state" id="stateLabel"><?php echo ENTRY_STATE; ?></label>
<?php
    echo zen_draw_input_field('state', $state, zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_state', '40') . ' id="state"') . (zen_not_null(ENTRY_STATE_TEXT) ? '<span class="alert">' . ENTRY_STATE_TEXT . '</span>': '');
    echo '<br class="clearBoth" />';
    if ($flag_show_pulldown_states == false) {
      echo zen_draw_hidden_field('zone_id', $zone_name, ' ');
    }
?>
<?php
  }
?>

<label class="inputLabel" title="postcode"><?php echo ENTRY_POST_CODE; ?></label>
<?php echo zen_draw_input_field('postcode', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_postcode', '40') . ' class="postcode"') . (zen_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="alert">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?>
<br class="clearBoth" />

<?php
if (!$_SESSION['COWOA']) { 
  echo zen_draw_checkbox_field('address_new', '1', false, 'class="address_new"') . TEXT_ADDRESS_NEW; 
}
?>
  <div class="buttonRow forward"><?php echo zen_draw_hidden_field('fec_change_address', 'submit') . zen_image_submit(BUTTON_IMAGE_SUBMIT, BUTTON_CONTINUE_ALT); ?></div>
  <br class="clearBoth" />   
</fieldset>
</div>