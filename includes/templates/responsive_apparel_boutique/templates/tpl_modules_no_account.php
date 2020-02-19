<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=create_account.<br />
 * Displays Create Account form.
 *
 * @package templateSystem - FEC
 * @copyright Copyright 2007-2008 Numinix Technology http://www.numinix.com
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2007 Joseph Schilz
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_no_account.php 97 2009-12-14 06:12:08Z numinix $
 */
?>

<?php if ($messageStack->size('no_account') > 0) echo $messageStack->output('no_account'); ?>

<?php 
    if (FEC_NOACCOUNT_ONLY_SWITCH != "true") {
?>
    <div class="fec-easy-sing-up">
<?php } else { ?>
    <div class="fec-easy-sing-up no-account-only">
<?php } ?>

    <div class="fec-col-left">
        <fieldset class="fec-fieldset">
            <span class="fec-fieldset-legend">Guest Checkout</span> 
    
            <!-- begin/collum left -->
            <div class="fec-cl-left">

                

                <?php
                  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
                ?>
                <fieldset class="fec-privacy">
                    <legend><?php echo TABLE_HEADING_PRIVACY_CONDITIONS; ?></legend>
                    <span class="fec-fieldset-legend"><?php echo TABLE_HEADING_PRIVACY_CONDITIONS; ?></span>

                    <div class="fec-information"><?php echo TEXT_PRIVACY_CONDITIONS_DESCRIPTION;?></div>
                    
                    <div class="fec-box-check-radio">
                        <?php echo zen_draw_checkbox_field('privacy_conditions', '1', false, 'id="privacy"');?>
                        <label class="checkboxLabel" for="privacy"><?php echo TEXT_PRIVACY_CONDITIONS_CONFIRM;?></label>
                    </div>

                </fieldset>
                <?php
                  }
                ?>


            <?php if (!$_SESSION['customer_id']) { ?>

                <?php if (!isset($_GET['type']) || $_GET['type'] != 'free_virtual') { ?>

                <fieldset class="fec-billing-address">
                    
                    <legend><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></legend>
                    <span class="fec-fieldset-legend"><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></span>
                    
                    <?php if (enable_shippingAddressCheckbox()) { ?>
                        <div class="fec-box-check-radio">
                            <?php echo zen_draw_checkbox_field('shippingAddress', '1', $shippingAddress, 'id="shippingAddress-checkbox"') . '<label style="" class="checkboxLabel" for="shippingAddress-checkbox">' . ENTRY_COPYBILLING . '</label>' . (zen_not_null(ENTRY_COPYBILLING_TEXT) ? '<span class="alert">' . ENTRY_COPYBILLING_TEXT . '</span>': ''); ?>  
                        </div>
                    <?php } ?>
                    <!-- end/shipping address -->

                    <?php
                        if (ACCOUNT_GENDER == 'true') {
                    ?>  
                        <div class="fec-box-check-radio">
                            <?php echo zen_draw_radio_field('gender', 'm', ($_SESSION['gender'] == 'm' ? true : false), 'id="gender-male"') . '<label class="radioButtonLabel" for="gender-male">' . MALE . '</label>' . zen_draw_radio_field('gender', 'f', ($_SESSION['gender'] == 'f' ? true : false), 'id="gender-female"') . '<label class="radioButtonLabel" for="gender-female">' . FEMALE . '</label>' . (zen_not_null(ENTRY_GENDER_TEXT) ? '<span class="alert">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
                        </div>
                    <?php
                        }
                    ?>
                    <!-- end/gender -->
                    
                    <div class="fec-field">
                        <label class="inputLabel" for="firstname"><?php echo ENTRY_FIRST_NAME; ?> <span class="alert"><?php echo ENTRY_FIRST_NAME_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('firstname', $_SESSION['firstname'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_firstname', '40') . ' id="firstname"') . (zen_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
                    </div> <!-- end/first name -->

                    <div class="fec-field">
                        <label class="inputLabel" for="lastname"><?php echo ENTRY_LAST_NAME; ?> <span class="alert"><?php echo ENTRY_LAST_NAME_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('lastname', $_SESSION['lastname'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_lastname', '40') . ' id="lastname"') . (zen_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?>
                    </div> <!-- end/last name -->

                    <div class="fec-field">
                        <label class="inputLabel" for="street-address"><?php echo ENTRY_STREET_ADDRESS; ?> <span class="alert"><?php echo ENTRY_STREET_ADDRESS_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('street_address', $_SESSION['street_address'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_street_address', '40') . ' id="street-address"') . (zen_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '': ''); ?>
                    </div> <!-- end/street name -->

                    <?php
                        if (ACCOUNT_SUBURB == 'true') {
                    ?>
                        <div class="fec-field">
                            <label class="inputLabel" for="suburb"><?php echo ENTRY_SUBURB; ?> <span class="alert"><?php echo ENTRY_SUBURB_TEXT; ?></span></label>
                            <?php echo zen_draw_input_field('suburb', $_SESSION['suburb'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_suburb', '40') . ' id="suburb"') . (zen_not_null(ENTRY_SUBURB_TEXT) ? '': ''); ?>
                        </div> 
                    <?php
                        }
                    ?>
                    <!-- end/suburb -->

                    <div class="fec-field">
                        <label class="inputLabel" for="city"><?php echo ENTRY_CITY; ?> <span class="alert"><?php echo ENTRY_CITY_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('city', $_SESSION['city'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_city', '40') . ' id="city"') . (zen_not_null(ENTRY_CITY_TEXT) ? '': ''); ?>
                    </div> <!-- end/city -->


                    <?php
                        if ($disable_country == true) {
                            $addclass = "hiddenField";
                        }
                    ?>
                    <div class="<?php echo $addclass; ?> fec-field">
                        <label class="inputLabel" for="country"><?php echo ENTRY_COUNTRY; ?> <span class="alert"><?php echo ENTRY_COUNTRY_TEXT; ?></span></label>
                        <?php echo zen_get_country_list('zone_country_id', $selected_country, 'id="country" ' . ($flag_show_pulldown_states == true ? 'onchange="update_zone(this.form);"' : '')) . (zen_not_null(ENTRY_COUNTRY_TEXT) ? '': ''); ?>
                    </div>
                    <!-- end/country -->

                    <?php
                        if (ACCOUNT_STATE == 'true') {
                            if ($flag_show_pulldown_states == true) {
                    ?>
                              
                                <div class="fec-field">
                                    <label class="inputLabel" for="stateZone" id="zoneLabel"><?php echo ENTRY_STATE; ?> <?php if (zen_not_null(ENTRY_STATE_TEXT)) echo '<span class="alert">' . ENTRY_STATE_TEXT . '</span>'; ?></label>
                                    <?php
                                        echo zen_draw_pull_down_menu('zone_id', zen_prepare_country_zones_pull_down($selected_country), $_SESSION['zone_id'], 'id="stateZone"');
                                    
                                    ?>
                                </div>
                                    <?php     
                                        }
                                    ?>

                                <div class="fec-field fec-state-2-field" 

                                    <?php if ($flag_show_pulldown_states == true) { ?> 
                                        id="fec-state-2-field"
                                    <? } ?>

                                >
                                    <label class="inputLabel" for="state" id="stateLabel"><?php echo ENTRY_STATE; ?> <?php if (zen_not_null(ENTRY_STATE_TEXT)) echo '<span class="alert" id="stText">' . ENTRY_STATE_TEXT . '</span>'; ?></label>
                                    <?php
                                        echo zen_draw_input_field('state', $_SESSION['state'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_state', '40') . ' id="state"');


                                        if ($flag_show_pulldown_states == false) {
                                            echo zen_draw_hidden_field('zone_id', $_SESSION['zone_id'], ' ');
                                        }
                                    ?>
                                </div>
                    <?php

                        }

                    ?>
                    <!-- end/state -->
                    
                    <div class="fec-field">
                        <label class="inputLabel" for="postcode"><?php echo ENTRY_POST_CODE; ?> <span class="alert"><?php echo ENTRY_POST_CODE_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('postcode', $_SESSION['postcode'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_postcode', '40') . ' id="postcode"') . (zen_not_null(ENTRY_POST_CODE_TEXT) ? '': ''); ?>
                    </div> <!-- end/post code -->
                        
                    <?php if (ACCOUNT_TELEPHONE == 'true' || ACCOUNT_FAX_NUMBER == 'true') { ?>

                        <?php /*if (ACCOUNT_TELEPHONE == 'true') { ?>
                            
                            <div class="fec-field">
                                <label class="inputLabel" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER; ?> <span class="alert"> <?php echo ENTRY_TELEPHONE_NUMBER_TEXT; ?></span></label>
                                <?php echo zen_draw_input_field('telephone', $_SESSION['telephone'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_telephone', '40') . ' id="telephone"') . (zen_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '': ''); ?>
                            </div>
                        <?php }*/ ?>
                        <!-- end/phone shipping -->

                        <?php if (ACCOUNT_FAX_NUMBER == 'true') { ?>

                            <div class="fec-field">
                                <label class="inputLabel" for="fax"><?php echo ENTRY_FAX_NUMBER; ?> <span class="alert"> <?php echo ENTRY_FAX_NUMBER_TEXT; ?></span></label>
                                <?php echo zen_draw_input_field('fax', $_SESSION['fax'], 'id="fax"') . (zen_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="alert">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?>
                            </div>

                        <?php } ?>
                        <!-- end/fax shipping -->

                    <?php } ?>
                    <!-- end/phone and fax shipping -->

                </fieldset>
                <!-- end/billing adress -->

            </div> <!-- end/collum left -->

            <!-- begin/collum left -->
            <div class="fec-cl-right">
              
                <!-- //for future releases -->
                <?php if(enable_shippingAddressCheckbox()) { ?>
                <fieldset id="shippingField" class="fec-fieldset fec-shipping-address">
                    <legend><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></legend>
                    <span class="fec-fieldset-legend"><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></span>
                    <?php
                        if (ACCOUNT_GENDER == 'true') {
                    ?>
                        <div class="fec-box-check-radio">
                            <?php echo zen_draw_radio_field('gender_shipping', 'm', ($_SESSION['gender_shipping'] == 'm' ? true : false), 'id="gender-male_shipping"') . '<label class="radioButtonLabel" for="gender-male">' . MALE . '</label>' . zen_draw_radio_field('gender_shipping', 'f', ($_SESSION['gender_shipping'] == 'f' ? true : false), 'id="gender-female_shipping"') . '<label class="radioButtonLabel" for="gender-female">' . FEMALE . '</label>' . (zen_not_null(ENTRY_GENDER_TEXT) ? '<span class="alert">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
                        </div>
                    <?php
                        }
                    ?>
                    <!-- end/gender shipping -->
                    
                    <div class="fec-field">
                        <label class="inputLabel" for="firstname_shipping"><?php echo ENTRY_FIRST_NAME; ?> <span class="alert"><?php echo ENTRY_FIRST_NAME_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('firstname_shipping', $_SESSION['firstname_shipping'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_firstname', '40') . ' id="firstname_shipping"') . (zen_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
                    </div> <!-- end/first name shipping -->

                    <div class="fec-field">
                        <label class="inputLabel" for="lastname_shipping"><?php echo ENTRY_LAST_NAME; ?> <span class="alert"><?php echo ENTRY_LAST_NAME_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('lastname_shipping', $_SESSION['lastname_shipping'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_lastname', '40') . ' id="lastname_shipping"') . (zen_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?>
                    </div> <!-- end/last name shipping -->

                    <?php
                        if (ACCOUNT_COMPANY == 'true') {
                    ?>
                            <div class="fec-field">
                                <label class="inputLabel" for="company_shipping"><?php echo ENTRY_COMPANY; ?> <span class="alert"><?php echo ENTRY_COMPANY_TEXT; ?></span></label>
                                <?php echo zen_draw_input_field('company_shipping', $_SESSION['company_shipping'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_company', '40') . ' id="company_shipping"') . (zen_not_null(ENTRY_COMPANY_TEXT) ? '': ''); ?>
                            </div> 
                    <?php
                        }
                    ?>
                    <!-- end/company shipping -->
                    
                    <div class="fec-field">
                        <label class="inputLabel" for="street-address_shipping"><?php echo ENTRY_STREET_ADDRESS; ?> <span class="alert"><?php echo ENTRY_STREET_ADDRESS_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('street_address_shipping', $_SESSION['street_address_shipping'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_street_address', '40') . ' id="street-address_shipping"') . (zen_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '': ''); ?>
                    </div> <!-- end/street shipping -->


                    <?php
                        if (ACCOUNT_SUBURB == 'true') {
                    ?>
                            <div class="fec-field">
                                <label class="inputLabel" for="suburb_shipping"><?php echo ENTRY_SUBURB; ?> <span class="alert"><?php echo ENTRY_SUBURB_TEXT; ?></span></label>
                                <?php echo zen_draw_input_field('suburb_shipping', $_SESSION['suburb_shipping'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_suburb', '40') . ' id="suburb_shipping"') . (zen_not_null(ENTRY_SUBURB_TEXT) ? '': ''); ?>
                            </div>

                    <?php
                        }
                    ?>
                    <!-- end/suburb shipping -->

                    <div class="fec-field">
                        <label class="inputLabel" for="city_shipping"><?php echo ENTRY_CITY; ?> <span class="alert"><?php echo ENTRY_CITY_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('city_shipping', $_SESSION['city_shipping'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_city', '40') . ' id="city_shipping"') . (zen_not_null(ENTRY_CITY_TEXT) ? '': ''); ?>
                    </div> <!-- end/city shipping -->

                    <?php
                        if ($disable_country == true) {
                            $addclass = "hiddenField";
                        }
                    ?>
                    <div class="<?php echo $addclass; ?> fec-field">
                        <label class="inputLabel" for="country_shipping"><?php echo ENTRY_COUNTRY; ?> <span class="alert"><?php echo ENTRY_COUNTRY_TEXT; ?></span></label>
                        <?php echo zen_get_country_list('zone_country_id_shipping', $selected_country_shipping, 'id="country_shipping" ' . ($flag_show_pulldown_states_shipping == true ? 'onchange="update_zone_shipping(this.form);"' : '')) . (zen_not_null(ENTRY_COUNTRY_TEXT) ? '': ''); ?>
                    </div>
                    <!-- end/country shipping -->
                    

                    <?php
                        if (ACCOUNT_STATE == 'true') {
                            if ($flag_show_pulldown_states_shipping == true) {
                    ?>
                              
                                <div class="fec-field">
                                    <label class="inputLabel" for="stateZoneShipping" id="zoneLabelShipping"><?php echo ENTRY_STATE; ?> <?php if (zen_not_null(ENTRY_STATE_TEXT)) echo '<span class="alert">' . ENTRY_STATE_TEXT . '</span>'; ?></label>
                                    <?php
                                        echo zen_draw_pull_down_menu('zone_id_shipping', zen_prepare_country_zones_pull_down($selected_country_shipping), $_SESSION['zone_id_shipping'], 'id="stateZoneShipping"');
                                    
                                    ?>
                                </div>
                                    <?php     
                                        }
                                    ?>

                                
                                <div class="fec-field fec-state-2-field" 

                                    <?php if ($flag_show_pulldown_states == true) { ?> 
                                        id="fec-state-2-field-shipping"
                                    <? } ?>

                                >
                                    <label class="inputLabel" for="state" id="stateLabelShipping"><?php echo ENTRY_STATE; ?> <?php if (zen_not_null(ENTRY_STATE_TEXT)) echo '<span class="alert" id="stTextShipping">' . ENTRY_STATE_TEXT . '</span>'; ?></label>
                                    <?php
                                        echo zen_draw_input_field('state_shipping', $_SESSION['state_shipping'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_state', '40') . ' id="state_shipping"');


                                        if ($flag_show_pulldown_states_shipping == false) {
                                            echo zen_draw_hidden_field('zone_id_shipping', $_SESSION['zone_id_shipping'], ' ');
                                        }
                                    ?>
                                </div>
                    <?php

                        }

                    ?>                    
                    <!-- end/state shipping -->
                    
                    

                    <div class="fec-field">
                        <label class="inputLabel" for="postcode_shipping"><?php echo ENTRY_POST_CODE; ?> <span class="alert"><?php echo ENTRY_POST_CODE_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('postcode_shipping', $_SESSION['postcode_shipping'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_postcode', '40') . ' id="postcode_shipping"') . (zen_not_null(ENTRY_POST_CODE_TEXT) ? '': ''); ?>
                    </div>
                    <!-- end/post code shipping -->


                </fieldset>
                <?php } ?>
                <!-- eof shipping -->
                
                <?php
                    }
                  } 
                ?>
                
                <!-- begin/contact details -->
                <fieldset id="contactDetails" class="fec-login-details">
                    <legend><?php echo TABLE_HEADING_CONTACT_DETAILS; ?></legend>
                    <span class="fec-fieldset-legend"><?php echo TABLE_HEADING_CONTACT_DETAILS; ?></span>
                    
                    <?php if (isset($_GET['type']) && $_GET['type'] == 'free_virtual') { ?>
                        <div class="fec-field">
                            <label class="inputLabel" for="firstname"><?php echo ENTRY_FIRST_NAME; ?> <span class="alert"><?php echo ENTRY_FIRST_NAME_TEXT; ?></span></label>
                            <?php echo zen_draw_input_field('firstname', $_SESSION['firstname'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_firstname', '40') . ' id="firstname"') . (zen_not_null(ENTRY_FIRST_NAME_TEXT) ? '': ''); ?>
                        </div> <!-- end/first-name -->
                        
                        <div class="fec-field">
                            <label class="inputLabel" for="lastname"><?php echo ENTRY_LAST_NAME; ?> <span class="alert"><?php echo ENTRY_LAST_NAME_TEXT; ?></span></label>
                            <?php echo zen_draw_input_field('lastname', $_SESSION['lastname'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_lastname', '40') . ' id="lastname"') . (zen_not_null(ENTRY_LAST_NAME_TEXT) ? '': ''); ?>
                        </div> <!-- end/last-name -->
                    
                    <?php } ?>

                    <div class="fec-field">
                        <label class="inputLabel" for="email-address"><?php echo ENTRY_EMAIL_ADDRESS; ?> <span class="alert"><?php echo ENTRY_EMAIL_ADDRESS_TEXT; ?></span></label>
                        <?php echo zen_draw_input_field('email_address', $_SESSION['email_address'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_email_address', '40') . ' id="email-address"') . (zen_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '': ''); ?>
                    </div> <!-- end/email-address -->

                    <?php if (FEC_CONFIRM_EMAIL == 'true') { ?>
                        <div class="fec-field">
                            <label class="inputLabel" for="email-address-confirm"><?php echo ENTRY_EMAIL_ADDRESS_CONFIRM; ?> <span class="alert"><?php echo ENTRY_EMAIL_ADDRESS_TEXT; ?></span></label>
                            <?php echo zen_draw_input_field('email_address_confirm', $_SESSION['email_address_confirm'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_email_address', '40') . ' id="email-address-confirm"') . (zen_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '': ''); ?>    
                        </div> <!-- end/confirm-email-address -->
                    <?php } ?>

                  
                    <!-- BEGIN CHECKOUT WITHOUT ACCOUNT -->
                    <?php if (FEC_NOACCOUNT_HIDEEMAIL == 'false') { ?>
                        <div class="fec-box-check-radio">
                            <?php echo zen_draw_radio_field('email_format', 'HTML', ($_SESSION['email_format'] == 'HTML' ? true : false),'id="email-format-html"') . '<label class="radioButtonLabel" for="email-format-html">' . ENTRY_EMAIL_HTML_DISPLAY . '</label>' .  zen_draw_radio_field('email_format', 'TEXT', ($_SESSION['email_format'] == 'TEXT' ? true : false), 'id="email-format-text"') . '<label class="radioButtonLabel" for="email-format-text">' . ENTRY_EMAIL_TEXT_DISPLAY . '</label>'; ?>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="email_format" value="<?php echo (ACCOUNT_EMAIL_PREFERENCE == '1' ? 'HTML' : 'TEXT'); ?>" />
                    <?php } ?>
                  <!-- END CHECKOUT WITHOUT ACCOUNT -->
                  <?php
                      if (ACCOUNT_NEWSLETTER_STATUS != 0) {
                  ?>
                      <div class="fec-box-check-radio">
                          <?php echo zen_draw_checkbox_field('newsletter', '1', $newsletter, 'id="newsletter-checkbox"') . '<label class="checkboxLabel" for="newsletter-checkbox">' . ENTRY_NEWSLETTER . '</label>' . (zen_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="alert">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?>
                      </div> <!-- end/newsletter -->
                  <?php } ?>

                  <?php if (!isset($_GET['type']) || $_GET['type'] != 'free_virtual') { ?>
                      <?php 
                          if (ACCOUNT_TELEPHONE == 'true') {
                      ?>
                          <div class="fec-field">
                              <label class="inputLabel" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER; ?> <span class="alert"><?php echo ENTRY_TELEPHONE_NUMBER_TEXT; ?></span></label>
                              <?php echo zen_draw_input_field('telephone', $_SESSION['telephone'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_telephone', '40') . ' id="telephone"') . (zen_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '': ''); ?>
                          </div> <!-- end/telephone -->
                      <?php } ?>
                  
                      <?php
                          if (ACCOUNT_FAX_NUMBER == 'true') {
                      ?>
                          <div class="fec-field">
                              <label class="inputLabel" for="fax"><?php echo ENTRY_FAX_NUMBER; ?> <span class="alert"><?php echo ENTRY_FAX_NUMBER_TEXT; ?></span></label>
                              <?php echo zen_draw_input_field('fax', $_SESSION['fax'], 'id="fax"') . (zen_not_null(ENTRY_FAX_NUMBER_TEXT) ? '': ''); ?>
                          </div> <!-- end/fax -->
                  <?php
                    }
                  ?>
                  </fieldset>
                  <!-- end/contact details -->

                  <?php
                      if (ACCOUNT_DOB == 'true') {
                  ?>
                          <fieldset id="noAccountDOB">
                              <legend><?php echo TABLE_HEADING_DATE_OF_BIRTH; ?></legend>
                              <span class="fec-fieldset-legend"><?php echo TABLE_HEADING_DATE_OF_BIRTH; ?></span>
                              
                              <div class="fec-field">
                                  <label class="inputLabel" for="dob"><?php echo ENTRY_DATE_OF_BIRTH; ?> <span class="alert"><?php echo ENTRY_DATE_OF_BIRTH_TEXT; ?></span></label>
                                  <?php echo zen_draw_input_field('dob', $_SESSION['dob'], 'id="dob"') . (zen_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '': ''); ?>
                              </div> <!-- end/dob -->

                          </fieldset>
                  <?php
                      }
                  ?>
                  <!-- end/account dob -->

                  <?php } ?>
                  
                  <?php
                      if (CUSTOMERS_REFERRAL_STATUS == 2) {
                  ?>
                          <fieldset id="noAccountReferral" class="fec-referral">
                              <legend><?php echo TABLE_HEADING_REFERRAL_DETAILS; ?></legend>
                              <span class="fec-fieldset-legend"><?php echo TABLE_HEADING_REFERRAL_DETAILS; ?></span>

                              <div class="fec-field">
                                  <label class="inputLabel" for="customers_referral"><?php echo ENTRY_CUSTOMERS_REFERRAL; ?></label>
                                  <?php echo zen_draw_input_field('customers_referral', $_SESSION['customers_referral'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_referral', '15') . ' id="customers_referral"'); ?>
                              </div> <!-- end/customer -->

                          </fieldset>
                  <?php } ?>
                  <!-- end/customer referral -->

             </div>
        </fieldset>
    </div>
</div>

  