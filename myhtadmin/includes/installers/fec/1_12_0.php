<?php
$db->Execute("REPLACE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
('Fast and Easy Checkout', 'FEC_STATUS', 'false', 'Activate Fast and Easy Checkout? (note: Easy Sign-up and Login must be disabled separately)', " . $configuration_group_id . ", 10, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('One Page Checkout', 'FEC_ONE_PAGE', 'true', 'Activate One Page Checkout?<br />Default = false (includes checkout_confirmation page)', " . $configuration_group_id . ", 11, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Checkout Confirmation Alternate Text', 'FEC_CHECKOUT_CONFIRMATION_TEXT', 'Your order is being processed, please wait...', 'Alternate text to be displayed on Checkout Confirmation page:', " . $configuration_group_id . ", 12, NOW(), NOW(), NULL, NULL),
('Display Checkout in Split Column', 'FEC_SPLIT_CHECKOUT', 'true', 'Display the checkout page in a split column format?', " . $configuration_group_id . ", 13, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Activate Drop Down List', 'FEC_DROP_DOWN', 'false', 'Activate drop down list to appear on checkout page?', " . $configuration_group_id . ", 14, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Gift Wrapping Module Switch', 'FEC_GIFT_WRAPPING_SWITCH', 'false', 'If the gift wrapping module is installed, set to true to activate', " . $configuration_group_id . ", 15, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Activate Gift Message Field', 'FEC_GIFT_MESSAGE', 'false', 'Activate gift message field to appear on checkout page?', " . $configuration_group_id . ", 16, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Drop Down List Options', 'FEC_DROP_DOWN_LIST', 'Option 1,Option 2,Option 3,Option 4,Option 5', 'Enter each option separated by commas:', " . $configuration_group_id . ", 17, NOW(), NOW(), NULL, NULL),
('Activate Checkbox Field', 'FEC_CHECKBOX', 'false', 'Activate checkbox field to appear on checkout page?', " . $configuration_group_id . ", 18, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

('Easy Sign-Up and Login', 'FEC_EASY_SIGNUP_STATUS', 'false', 'Activate Easy Sign-Up and Login?', " . $configuration_group_id . ", 20, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Display Order Total', 'FEC_ORDER_TOTAL', 'false', 'Display the Order Total sidebox on login?', " . $configuration_group_id . ", 21, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Display Confidence Box', 'FEC_CONFIDENCE', 'false', 'Display the \"Shop With Confidence\" sidebox on login?', " . $configuration_group_id . ", 22, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('COWOA Position', 'FEC_NOACCOUNT_POSITION', 'side', 'Display the COWOA fieldset above the registration form (top) or beneath the login (side)?', " . $configuration_group_id . ", 23, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'top\', \'side\'),'),
('Confirm Email', 'FEC_CONFIRM_EMAIL', 'false', 'Require user to enter email twice for confirmation?', " . $configuration_group_id . ", 24, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Shipping Address', 'FEC_SHIPPING_ADDRESS', 'true', 'Display the shipping address form on the login and COWOA pages?', " . $configuration_group_id . ", 25, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Copy Billing', 'FEC_COPYBILLING', 'true', 'If the shipping address form is enabled, should the copy billing address checkbox be checked by default?', " . $configuration_group_id . ", 26, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Master Password', 'FEC_MASTER_PASSWORD', 'false', 'Allow login to customer account using master password? (Must be using ZenCart v1.5.0 or higher)', " . $configuration_group_id . ", 27, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

('Checkout Without Account', 'FEC_NOACCOUNT_SWITCH', 'true', 'Activate Checkout Without an Account?', " . $configuration_group_id . ", 30, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Checkout Without Account Only', 'FEC_NOACCOUNT_ONLY_SWITCH', 'false', 'Disable regular login/registration and force Checkout Without an Account?', " . $configuration_group_id . ", 31, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Combine COWOA Accounts', 'FEC_NOACCOUNT_COMBINE', 'false', 'Combine COWOA accounts so that COWOA customers can access their orders and other account features (note this will only work on future registrations)?', " . $configuration_group_id . ", 31, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Hide Email Options For No Account', 'FEC_NOACCOUNT_HIDEEMAIL', 'true', 'Hide \"HTML/TEXT-Only\" for checkout without account?', " . $configuration_group_id . ", 32, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Automatic LogOff for No Account', 'FEC_NOACCOUNT_LOGOFF', 'true', 'Automatically logoff customers without accounts?', " . $configuration_group_id . ", 33, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Free/Virtual Checkout', 'FEC_FREE_VIRTUAL_CHECKOUT', 'false', 'Only require name and email address for products that are both free and virtual?', " . $configuration_group_id . ", 34, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');");

if (!nmx_check_field(TABLE_CUSTOMERS, 'COWOA_account')) $db->Execute("ALTER TABLE " . TABLE_CUSTOMERS . " ADD COWOA_account tinyint(1) NOT NULL default 0;");
if (!nmx_check_field(TABLE_ORDERS, 'COWOA_order')) $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD COWOA_order tinyint(1) NOT NULL default 0;");
$db->Execute("INSERT IGNORE INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Telephone Number', 'ACCOUNT_TELEPHONE', 'true', 'Display telephone number field during account creation and with account information', '5', '8', 'zen_cfg_select_option(array(\'true\', \'false\'), ', now());");
$db->Execute("INSERT IGNORE INTO " . TABLE_QUERY_BUILDER . " (query_id, query_category, query_name, query_description, query_string) VALUES ('', 'email,newsletters', 'Permanent Account Holders Only', 'Send email only to permanent account holders ', 'select customers_email_address, customers_firstname, customers_lastname from TABLE_CUSTOMERS where COWOA_account != 1 order by customers_lastname, customers_firstname, customers_email_address');");

$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
  // delete configuration menu
  $db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = 'configFastandEasyCheckout' LIMIT 1;");
  // add configuration menu
  if (!zen_page_key_exists('configFastandEasyCheckout')) {
    if ((int)$configuration_group_id > 0) {
      zen_register_admin_page('configFastandEasyCheckout',
                              'BOX_CONFIGURATION_FEC', 
                              'FILENAME_CONFIGURATION',
                              'gID=' . $configuration_group_id, 
                              'configuration', 
                              'Y',
                              $configuration_group_id);
        
      $messageStack->add('Enabled Fast and Easy Checkout Configuration menu.', 'success');
    }
  } 
}
