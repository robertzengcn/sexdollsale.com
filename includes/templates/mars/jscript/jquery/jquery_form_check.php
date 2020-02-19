<?php
/**
 * jscript_form_check
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: J_Schilz for Integrated COWOA - 14 April 2007
 */
?>
<script type="text/javascript"><!--
var form = "";
var submitted = false;
var error = false;
var error_message = "";

function check_input(field_name, field_size, message) {
  if (jQuery('[name=' + field_name + ']') && (jQuery('[name=' + field_name + ']').attr("visibility") != "hidden")) {
    if (field_size == 0) return;
    var field_value = jQuery('[name=' + field_name + ']').val();
    if (field_value == '' || field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true; 
      jQuery('[name=' + field_name + ']').addClass("missing");
    }
  }
}

function check_radio(field_name, message) {
  var isChecked = false;

  if (jQuery('[name=' + field_name + ']').val() && (jQuery('[name=' + field_name + ']').attr("visibility") != "hidden")) {
    var radio = jQuery('[name=' + field_name + ']');

    for (var i=0; i<radio.length; i++) {
      if (radio[i].checked == true) {
        isChecked = true;
        break;
      }
    }

    if (isChecked == false) {
      error_message = error_message + "* " + message + "\n";
      jQuery('[name=' + field_name + ']').addClass("missing");
      error = true;
    }
  }
}

function check_select(field_name, field_default, message) {
  if (jQuery('[name=' + field_name + ']') && (jQuery('[name=' + field_name + ']').attr("visibility") != "hidden")) {
    var field_value = jQuery('[name=' + field_name + ']').val();

    if (field_value == field_default) {
      error_message = error_message + "* " + message + "\n";
      jQuery('[name=' + field_name + ']').addClass("missing");
      error = true; 
    }
  }
}

function check_password(field_name_1, field_name_2, field_size, message_1, message_2) {
  if (jQuery('[name=' + field_name_1 + ']') && (jQuery('[name=' + field_name_1 + ']').attr("visibility") != "hidden")) {
    var password = jQuery('[name=' + field_name_1 + ']').val();
    var confirmation = jQuery('[name=' + field_name_2 + ']').val();

    if (password == '' || password.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      jQuery('[name=' + field_name_1 + ']').addClass("missing");
      error = true;
    } else if (password != confirmation) {
      error_message = error_message + "* " + message_2 + "\n";
      jQuery('[name=' + field_name_2 + ']').addClass("missing");
      error = true; 
    }
  }
}

function check_email(field_name_1, field_name_2, field_size, message_1, message_2) {
  if (jQuery('[name=' + field_name_1 + ']') && (jQuery('[name=' + field_name_1 + ']').attr("visibility") != "hidden")) {
    var email = jQuery('[name=' + field_name_1 + ']').val();
    var email_confirmation = jQuery('[name=' + field_name_2 + ']').val();
    if (email = '' || email.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      jQuery('[name=' + field_name_1 + ']').addClass("missing");
      error = true;    
    } else if (jQuery('[name=' + field_name_2 + ']').length > 0 && email != email_confirmation) {
      error_message = error_message + "* " + message_2 + "\n";
      jQuery('[name=' + field_name_2 + ']').addClass("missing");
      error = true; 
    }
  }
}

function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_1, message_2, message_3) {
  if (jQuery('[name=' + field_name_1 + ']') && (jQuery('[name=' + field_name_1 + ']').attr("visibility") != "hidden")) {
    var password_current = jQuery('[name=' + field_name_1 + ']').val();
    var password_new = jQuery('[name=' + field_name_2 + ']').val();
    var password_confirmation = jQuery('[name=' + field_name_3 + ']').val();

    if (password_current == '' || password_current.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      jQuery('[name=' + field_name_1 + ']').addClass("missing");
      error = true; 
    } else if (password_new == '' || password_new.length < field_size) {
      error_message = error_message + "* " + message_2 + "\n";
      jQuery('[name=' + field_name_2 + ']').addClass("missing");
      error = true; 
    } else if (password_new != password_confirmation) {
      error_message = error_message + "* " + message_3 + "\n";
      jQuery('[name=' + field_name_3 + ']').addClass("missing");
      error = true;
    }
  }
}

function check_form_registration(form_name) {
  if (submitted == true) {
    //alert("<?php echo JS_ERROR_SUBMITTED; ?>");
    //return false;
  }

  error = false;
  form = form_name;
  error_message = "<?php echo JS_ERROR; ?>";

<?php if (ACCOUNT_GENDER == 'true') echo '  check_radio("gender", "' . addslashes(ENTRY_GENDER_ERROR) . '");' . "\n"; ?>

<?php if ((int)ENTRY_FIRST_NAME_MIN_LENGTH > 0) { ?>
  check_input("firstname", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_FIRST_NAME_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_LAST_NAME_MIN_LENGTH > 0) { ?>
  check_input("lastname", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_LAST_NAME_ERROR); ?>");
<?php } ?>

<?php if (ACCOUNT_DOB == 'true' && (int)ENTRY_DOB_MIN_LENGTH != 0) echo '  check_input("dob", ' . ENTRY_DOB_MIN_LENGTH . ', "' . addslashes(ENTRY_DATE_OF_BIRTH_ERROR) . '");' . "\n"; ?>
<?php if (ACCOUNT_COMPANY == 'true' && (int)ENTRY_COMPANY_MIN_LENGTH != 0) echo '  check_input("company", ' . ENTRY_COMPANY_MIN_LENGTH . ', "' . addslashes(ENTRY_COMPANY_ERROR) . '");' . "\n"; ?>

<?php if ((int)ENTRY_EMAIL_ADDRESS_MIN_LENGTH > 0) { ?>
  check_input("email_address_register", <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_EMAIL_ADDRESS_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_STREET_ADDRESS_MIN_LENGTH > 0) { ?>
  check_input("street_address", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_STREET_ADDRESS_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_POSTCODE_MIN_LENGTH > 0) { ?>
  check_input("postcode", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_POST_CODE_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_CITY_MIN_LENGTH > 0) { ?>
  check_input("city", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_CITY_ERROR); ?>");
<?php } ?>

<?php if (ACCOUNT_STATE == 'true') echo '  if (!jQuery(\'[name="state"]\').attr("disabled") == "disabled" && jQuery(\'[name="zone_id"]\').val() == "") check_input("state", ' . ENTRY_STATE_MIN_LENGTH . ', "' . addslashes(ENTRY_STATE_ERROR) . '")' . "\n" . '  else if (jQuery(\'[name=state]\').attr("disabled") == "disabled") check_select("zone_id", "", "' . addslashes(ENTRY_STATE_ERROR_SELECT) . '");' . "\n"; ?>

  check_select("zone_country_id", "", "<?php echo addslashes(ENTRY_COUNTRY_ERROR); ?>");

<?php if (ACCOUNT_TELEPHONE == 'true' && (int)ENTRY_TELEPHONE_MIN_LENGTH > 0) { ?>
  check_input("telephone", <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_TELEPHONE_NUMBER_ERROR); ?>");
<?php } ?>

<?php if (FEC_SHIPPING_ADDRESS == 'true') { ?>
if (!jQuery('#shippingAddress-checkbox').is(':checked')) {
<?php if (ACCOUNT_GENDER == 'true') echo '  check_radio("gender_shipping", "' . addslashes(ENTRY_GENDER_ERROR) . '");' . "\n"; ?>

<?php if ((int)ENTRY_FIRST_NAME_MIN_LENGTH > 0) { ?>
  check_input("firstname_shipping", <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_FIRST_NAME_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_LAST_NAME_MIN_LENGTH > 0) { ?>
  check_input("lastname_shipping", <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_LAST_NAME_ERROR); ?>");
<?php } ?>

<?php if (ACCOUNT_COMPANY == 'true' && (int)ENTRY_COMPANY_MIN_LENGTH != 0) echo '  check_input("company_shipping", ' . ENTRY_COMPANY_MIN_LENGTH . ', "' . addslashes(ENTRY_COMPANY_ERROR) . '");' . "\n"; ?>

<?php if ((int)ENTRY_STREET_ADDRESS_MIN_LENGTH > 0) { ?>
  check_input("street_address_shipping", <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_STREET_ADDRESS_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_POSTCODE_MIN_LENGTH > 0) { ?>
  check_input("postcode_shipping", <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_POST_CODE_ERROR); ?>");
<?php } ?>
<?php if ((int)ENTRY_CITY_MIN_LENGTH > 0) { ?>
  check_input("city_shipping", <?php echo ENTRY_CITY_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_CITY_ERROR); ?>");
<?php } ?>

<?php if (ACCOUNT_STATE == 'true') echo '  if (!jQuery(\'[name="state_shipping"]\').attr("disabled") == "disabled" && jQuery(\'[name="zone_id_shipping"]\').val() == "") check_input("state_shipping", ' . ENTRY_STATE_MIN_LENGTH . ', "' . addslashes(ENTRY_STATE_ERROR) . '")' . "\n" . '  else if (jQuery(\'[name="state_shipping"]\').attr("disabled") == "disabled") check_select("zone_id_shipping", "", "' . addslashes(ENTRY_STATE_ERROR_SELECT) . '");' . "\n"; ?>

  check_select("zone_country_id_shipping", "", "<?php echo addslashes(ENTRY_COUNTRY_ERROR); ?>");

<?php if (ACCOUNT_TELEPHONE_SHIPPING == 'true' && (int)ENTRY_TELEPHONE_MIN_LENGTH > 0) { ?>
  check_input("telephone_shipping", <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>, "<?php echo addslashes(ENTRY_TELEPHONE_NUMBER_ERROR); ?>");
<?php } ?>
}
<?php } ?>
  
  if (error == true) {
    jAlert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>