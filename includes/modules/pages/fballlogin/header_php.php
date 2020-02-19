<?php
/**
 * Login Page
 *
 * @package page
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: header_php.php 18695 2011-05-04 05:24:19Z drbyte $
 */

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_LOGIN');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if ($session_started == false) {
  zen_redirect(zen_href_link(FILENAME_COOKIE_USAGE));
}

// if the customer is logged in already, redirect them to the My account page
if (isset($_SESSION['customer_id']) and $_SESSION['customer_id'] != '') {
  zen_redirect(zen_href_link(FILENAME_ACCOUNT, '', 'SSL'));
}

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_CREATE_ACCOUNT));
    
// Some intial variable.
  $process = false; $zone_name = ''; $entry_state_has_zones = ''; $error_state_input = false; $state = ''; $zone_id = 0;
  $error = false; $email_format = 'TEXT'; $newsletter =  '1';

  //Fetch data from configration table.
     $fbapikey_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_API_KEY'";
     $fbapikey_query = $db->Execute($fbapikey_query);
     $fbapikey = $fbapikey_query->fields['configuration_value'];
  
     $fbsecretkey_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_API_SECRET_KEY'";
     $fbsecretkey_query = $db->Execute($fbsecretkey_query);
     $fbsecretkey = $fbsecretkey_query->fields['configuration_value'];
 
     $useapi_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_USEAPI'";
     $useapi_query = $db->Execute($useapi_query);
     $useapi = $useapi_query->fields['configuration_value'];
	
	 $linkaccount_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_LINKACCOUNT'";
     $linkaccount_query = $db->Execute($linkaccount_query);
     $link_account = $linkaccount_query->fields['configuration_value'];
	 
  // Grab data for post wall.
     $post_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_POST'";
     $post_query = $db->Execute($post_query);
     $enablepost = $post_query->fields['configuration_value'];
	 
	 $posttitle_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_POST_TITLE'";
     $posttitle_query = $db->Execute($posttitle_query);
     $posttitle = $posttitle_query->fields['configuration_value'];
	 
	 $posturl_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_POST_URL'";
     $posturl_query = $db->Execute($posturl_query);
     $posturl = $posturl_query->fields['configuration_value'];
	 
	 $postmsg_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_POST_MSG'";
     $postmsg_query = $db->Execute($postmsg_query);
     $postmsg = $postmsg_query->fields['configuration_value'];
	 
	 $postpic_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_POST_PIC'";
     $postpic_query = $db->Execute($postpic_query);
     $postpic = $postpic_query->fields['configuration_value'];
	 
	 $postdesc_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_FBALL_LOGIN_POST_DESC'";
     $postdesc_query = $db->Execute($postdesc_query);
     $postdesc = $postdesc_query->fields['configuration_value'];

	 $redirect = zen_href_link('fballlogin', '', 'SSL');
     if(strpos($redirect, 'zenid') !== false) {
	   if(ENABLE_SSL == 'false') {
	     $redirect = HTTP_SERVER.DIR_WS_CATALOG.'index.php?main_page=fballlogin';
	   }
	   else {
	     $redirect = HTTPS_SERVER.DIR_WS_CATALOG.'index.php?main_page=fballlogin';
	   }
	 }
     $fbdata = array();
	 if (isset($_GET['code'])) {
       $code = $_GET['code'];
	   parse_str(get_fb_contents("https://graph.facebook.com/oauth/access_token?" . 'client_id=' . $fbapikey . '&redirect_uri=' . urlencode($redirect) .'&client_secret=' .  $fbsecretkey . '&code=' . urlencode($code), $useapi));
	   if (!empty($access_token)) {
	     $fbuser_info = json_decode(get_fb_contents("https://graph.facebook.com/me?access_token=" . $access_token, $useapi));
	     $process = true;
         $fbdata = getfbuser_data($fbuser_info);
	     if (!empty($fbdata['id']) AND !empty($fbdata['email'])) {
	     if (empty($fbdata['gender'])) {
           $fbdata['gender'] = 'm';
         }
         $email_format = zen_db_prepare_input($email_format);
         $customer_fbid = $fbdata['id'];
         // Check if id exists
		  $check_customer_query = "SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_password,
                                    c.customers_email_address, c.customers_default_address_id, f.customer_fb_avatar,
                                    c.customers_authorization, c.customers_referral FROM " . TABLE_CUSTOMERS . " AS c INNER JOIN ".DB_PREFIX.'fball_customer'." AS f ON f.customers_id = c.customers_id where f.customer_fbid = :customer_fbid";
         
         $check_customer_query = $db->bindVars($check_customer_query, ':customer_fbid', $customer_fbid, 'string');
         $check_customer = $db->Execute($check_customer_query);
		 if (!$check_customer->RecordCount()) {
		    // Check if email exists
		    $email_address = $fbdata['email'];
			 $check_customer_query = "SELECT customers_id, customers_firstname, customers_lastname, customers_password, customers_email_address, customers_default_address_id, customers_authorization, customers_referral FROM " . TABLE_CUSTOMERS . "  where customers_email_address = :email_address";
           
            $check_customer_query  =$db->bindVars($check_customer_query, ':email_address', $email_address, 'string');
            $check_customer = $db->Execute($check_customer_query);
			if ($link_account == 'True' && $check_customer->RecordCount()) {
			   $db->Execute("delete from " . DB_PREFIX.'fball_customer' . " where customer_fbid ='".$fbdata['id']."'");
			   $sql_data_array = array('customers_id' => $check_customer->fields['customers_id'],
                           'customer_fbid' => $fbdata['id'],
						   'customer_fb_avatar' => $fbdata['thumbnail'],
						   );
               zen_db_perform(DB_PREFIX.'fball_customer', $sql_data_array);
            }
		 }
         if ($check_customer->RecordCount()) {
		  if ($check_customer->fields['customers_authorization'] == '4') {
               // this account is banned
             $zco_notifier->notify('NOTIFY_LOGIN_BANNED');
             $messageStack->add('login', TEXT_LOGIN_BANNED);
           }
		   else {
             if (SESSION_RECREATE == 'True') {
               zen_session_recreate();
             }
			$check_country_query = "SELECT entry_country_id, entry_zone_id
                              FROM " . TABLE_ADDRESS_BOOK . "
                              WHERE customers_id = :customersID
                              AND address_book_id = :addressBookID";

        $check_country_query = $db->bindVars($check_country_query, ':customersID', $check_customer->fields['customers_id'], 'integer');
        $check_country_query = $db->bindVars($check_country_query, ':addressBookID', $check_customer->fields['customers_default_address_id'], 'integer');
        $check_country = $db->Execute($check_country_query);

        $_SESSION['customer_id'] = $check_customer->fields['customers_id'];
        $_SESSION['customer_default_address_id'] = $check_customer->fields['customers_default_address_id'];
        $_SESSION['customers_authorization'] = $check_customer->fields['customers_authorization'];
        $_SESSION['customer_first_name'] = $check_customer->fields['customers_firstname'];
        $_SESSION['customer_last_name'] = $check_customer->fields['customers_lastname'];
        $_SESSION['customer_country_id'] = $check_country->fields['entry_country_id'];
        $_SESSION['customer_zone_id'] = $check_country->fields['entry_zone_id'];
		
        $sql = "UPDATE " . TABLE_CUSTOMERS_INFO . "
              SET customers_info_date_of_last_logon = now(),
                  customers_info_number_of_logons = customers_info_number_of_logons+1
              WHERE customers_info_id = :customersID";

        $sql = $db->bindVars($sql, ':customersID',  $_SESSION['customer_id'], 'integer');
        $db->Execute($sql);
        $zco_notifier->notify('NOTIFY_LOGIN_SUCCESS');
		 // save current cart contents count if required
        if (SHOW_SHOPPING_CART_COMBINED > 0) {
          $zc_check_basket_before = $_SESSION['cart']->count_contents();
        }

        // restore cart contents
        $_SESSION['cart']->restore_contents();
        // check current cart contents count if required
        if (SHOW_SHOPPING_CART_COMBINED > 0 && $zc_check_basket_before > 0) {
          $zc_check_basket_after = $_SESSION['cart']->count_contents();
          if (($zc_check_basket_before != $zc_check_basket_after) && $_SESSION['cart']->count_contents() > 0 && SHOW_SHOPPING_CART_COMBINED > 0) {
            if (SHOW_SHOPPING_CART_COMBINED == 2) {
              // warning only do not send to cart
              $messageStack->add_session('header', WARNING_SHOPPING_CART_COMBINED, 'caution');
            }
            if (SHOW_SHOPPING_CART_COMBINED == 1) {
              // show warning and send to shopping cart for review
              $messageStack->add_session('shopping_cart', WARNING_SHOPPING_CART_COMBINED, 'caution');
              zen_redirect(zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'));
            }
          }
        }
        // eof: contents merge notice

        if (sizeof($_SESSION['navigation']->snapshot) > 0) {
          $origin_href = zen_href_link($_SESSION['navigation']->snapshot['page'], zen_array_to_string($_SESSION['navigation']->snapshot['get'], array(zen_session_name())), $_SESSION['navigation']->snapshot['mode']);
          $_SESSION['navigation']->clear_snapshot();
		  ?><script>window.opener.location="<?php echo $origin_href;?>";
		            window.close();
		</script><?php
          //zen_redirect($origin_href);
        } 
		else {
		 ?><script>window.opener.location="<?php echo zen_href_link(FILENAME_DEFAULT, '', $request_type);?>";
		           window.close();
		</script><?php
		  //zen_redirect(zen_href_link(FILENAME_DEFAULT, '', $request_type));
        }
		  $breadcrumb->add(NAVBAR_TITLE);

			// Check for PayPal express checkout button suitability:
			
			$paypalec_enabled = (defined('MODULE_PAYMENT_PAYPALWPP_STATUS') && MODULE_PAYMENT_PAYPALWPP_STATUS == 'True' && defined('MODULE_PAYMENT_PAYPALWPP_ECS_BUTTON') && MODULE_PAYMENT_PAYPALWPP_ECS_BUTTON == 'On');
			
			// Check for express checkout button suitability:
			
			$ec_button_enabled = ($paypalec_enabled && ($_SESSION['cart']->count_contents() > 0 && $_SESSION['cart']->total > 0));

         }
      }
         else {
		   if (!empty($fbdata['country'])) {
		      $country_query = "select countries_id from " . TABLE_COUNTRIES . " where countries_name = '".$fbdata['country']."'";
             $country_query = $db->Execute($country_query);
              $country = $country_query->fields['countries_id'];
			 $state = $fbdata['state'];
		   
             $check_query = "SELECT count(*) AS total
                    FROM " . TABLE_ZONES . "
                    WHERE zone_country_id = :zoneCountryID";
             $check_query = $db->bindVars($check_query, ':zoneCountryID', $country, 'integer');
             $check = $db->Execute($check_query);
             $entry_state_has_zones = ($check->fields['total'] > 0);
             if ($entry_state_has_zones == true) {
               $zone_query = "SELECT distinct zone_id, zone_name, zone_code
                     FROM " . TABLE_ZONES . "
                     WHERE zone_country_id = :zoneCountryID
                     AND " .
                     ((trim($state) != '' && $zone_id == 0) ? "(upper(zone_name) like ':zoneState%' OR upper(zone_code) like '%:zoneState%') OR " : "") .
                    "zone_id = :zoneID
                     ORDER BY zone_code ASC, zone_name";

               $zone_query = $db->bindVars($zone_query, ':zoneCountryID', $country, 'integer');
               $zone_query = $db->bindVars($zone_query, ':zoneState', strtoupper($state), 'noquotestring');
               $zone_query = $db->bindVars($zone_query, ':zoneID', $zone_id, 'integer');
               $zone = $db->Execute($zone_query);

               //look for an exact match on zone ISO code
               $found_exact_iso_match = ($zone->RecordCount() == 1);
               if ($zone->RecordCount() > 1) {
                 while (!$zone->EOF && !$found_exact_iso_match) {
                 if (strtoupper($zone->fields['zone_code']) == strtoupper($state) ) {
                   $found_exact_iso_match = true;
                   continue;
                 }
                 $zone->MoveNext();
               }
             }

             if ($found_exact_iso_match) {
               $zone_id = $zone->fields['zone_id'];
               $zone_name = $zone->fields['zone_name'];
             }      
		   } 
		  }
		else { 
		 // Enter deafutl values for country and zone.
		 $country_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'STORE_COUNTRY'";
         $country_query = $db->Execute($country_query);
         $country = $country_query->fields['configuration_value'];
	     $zoneid_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'STORE_ZONE'";
         $zoneid_query = $db->Execute($zoneid_query);
         $zone_id = $zoneid_query->fields['configuration_value'];
		} 
	    $sql_data_array = array('customers_firstname' => $fbdata['first_name'],
                          'customers_lastname' => $fbdata['last_name'],
						  'customers_email_address' => $fbdata['email'],
						  'customers_telephone' => $fbdata['telephone'],
						  'customers_email_format' => $email_format,
						  'customers_default_address_id' => 0,
						  'customers_password' => zen_encrypt_password($fbdata['password']),
						  'customers_gender' => $fbdata['gender'],
						  'customers_newsletter' => (int)$newsletter,
						  'customers_dob' => (empty($fbdata['dob']) || $dob_entered == '0001-01-01 00:00:00' ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_date_raw($fbdata['dob'])),
						  'customers_authorization' => (int)CUSTOMERS_APPROVAL_AUTHORIZATION,
                     );
	  if ((CUSTOMERS_REFERRAL_STATUS == '2' and $customers_referral != '')) $sql_data_array['customers_referral'] = $customers_referral;
        zen_db_perform(TABLE_CUSTOMERS, $sql_data_array);
        $_SESSION['customer_id'] = $db->Insert_ID();
		 $sql_fbdata_array = array('customers_id' => $_SESSION['customer_id'],
                           'customer_fbid' => $fbdata['id'],
						   'customer_fb_avatar' => $fbdata['thumbnail'],
						   );
        zen_db_perform(DB_PREFIX.'fball_customer', $sql_fbdata_array);
        $zco_notifier->notify('NOTIFY_MODULE_CREATE_ACCOUNT_ADDED_CUSTOMER_RECORD', array_merge(array('customer_id' => $_SESSION['customer_id']), $sql_data_array));
        $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                           'entry_firstname' => $fbdata['first_name'],
						   'entry_lastname' => $fbdata['last_name'],
						   'entry_street_address' => $fbdata['address'],
						   'entry_city' => $fbdata['city'],
						   'entry_gender' => $fbdata['gender'],
						   'entry_company' => $fbdata['company'],
						   'entry_country_id' => (int)$country,
						   );
		if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
        zen_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
        $address_id = $db->Insert_ID();
        $zco_notifier->notify('NOTIFY_MODULE_CREATE_ACCOUNT_ADDED_ADDRESS_BOOK_RECORD', array_merge(array('address_id' => $address_id), $sql_data_array));
        $sql = "update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
        $db->Execute($sql);
        $sql = "insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created, customers_info_date_of_last_logon) values ('" . (int)$_SESSION['customer_id'] . "', '1', now(), now())";
        $db->Execute($sql);
		
		//post on user wall.
		if ($enablepost == 'True') {
		  $attachment =  array(
            'access_token' => $access_token,
            'message' => trim($postmsg),
            'name' => trim($posttitle),
            'link' => trim($posturl),
            'description' => trim($postdesc),
            'picture'=>trim($postpic)
          );
		  facebookall_wallpost_curl ($attachment,$fbdata['id']);

		}
    
	    // phpBB create account
        if ($phpBB->phpBB['installed'] == true) {
      
	       $phpBB->phpbb_create_account($fbdata['first_name'], $fbdata['password'], $fbdata['email']);

        }
        // End phppBB create account

	    if (SESSION_RECREATE == 'True') {
	      zen_session_recreate();
        }
        $_SESSION['customer_first_name'] = $fbdata['first_name'];
		$_SESSION['customer_default_address_id'] = $address_id;
        $_SESSION['customer_country_id'] = $country;
        $_SESSION['customer_zone_id'] = $zone_id;
        $_SESSION['customers_authorization'] = $customers_authorization;
        // restore cart contents
        $_SESSION['cart']->restore_contents();
        // hook notifier class
        $zco_notifier->notify('NOTIFY_LOGIN_SUCCESS_VIA_CREATE_ACCOUNT');
        // build the message content

        $name = $fbdata['first_name'] . ' ' . $fbdata['last_name'];
        $email_text = sprintf(EMAIL_GREET_NONE, $fbdata['first_name']);
        $html_msg['EMAIL_GREETING'] = str_replace('\n','',$email_text);
        $html_msg['EMAIL_FIRST_NAME'] = $fbdata['first_name'];
        $html_msg['EMAIL_LAST_NAME']  = $fbdata['last_name'];
        // initial welcome
        $email_text .=  EMAIL_WELCOME;
        $html_msg['EMAIL_WELCOME'] = str_replace('\n','',EMAIL_WELCOME);
        if (NEW_SIGNUP_DISCOUNT_COUPON != '' and NEW_SIGNUP_DISCOUNT_COUPON != '0') {
          $coupon_id = NEW_SIGNUP_DISCOUNT_COUPON;
          $coupon = $db->Execute("select * from " . TABLE_COUPONS . " where coupon_id = '" . $coupon_id . "'");
          $coupon_desc = $db->Execute("select coupon_description from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . $_SESSION['languages_id'] . "'");
          $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . $fbdata['email'] . "', now() )");
          $text_coupon_help = sprintf(TEXT_COUPON_HELP_DATE, zen_date_short($coupon->fields['coupon_start_date']),zen_date_short($coupon->fields['coupon_expire_date']));
          // if on, add in Discount Coupon explanation
          $email_text .= "\n" . EMAIL_COUPON_INCENTIVE_HEADER . (!empty($coupon_desc->fields['coupon_description']) ? $coupon_desc->fields['coupon_description'] . "\n\n" : '') . $text_coupon_help  . "\n\n" . strip_tags(sprintf(EMAIL_COUPON_REDEEM, ' ' . $coupon->fields['coupon_code'])) . EMAIL_SEPARATOR;
          $html_msg['COUPON_TEXT_VOUCHER_IS'] = EMAIL_COUPON_INCENTIVE_HEADER ;
          $html_msg['COUPON_DESCRIPTION']     = (!empty($coupon_desc->fields['coupon_description']) ? '<strong>' . $coupon_desc->fields['coupon_description'] . '</strong>' : '');

          $html_msg['COUPON_TEXT_TO_REDEEM']  = str_replace("\n", '', sprintf(EMAIL_COUPON_REDEEM, ''));
          $html_msg['COUPON_CODE']  = $coupon->fields['coupon_code'] . $text_coupon_help;
        } //endif coupon
        if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
          $coupon_code = zen_create_coupon_code();
          $insert_query = $db->Execute("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
          $insert_id = $db->Insert_ID();
          $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $fbdata['email'] . "', now() )");
          // if on, add in GV explanation
          $email_text .= "\n\n" . sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) .
          sprintf(EMAIL_GV_REDEEM, $coupon_code) .
          EMAIL_GV_LINK . zen_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code, 'NONSSL', false) . "\n\n" .
          EMAIL_GV_LINK_OTHER . EMAIL_SEPARATOR;
          $html_msg['GV_WORTH'] = str_replace('\n','',sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) );
          $html_msg['GV_REDEEM'] = str_replace('\n','',str_replace('\n\n','<br />',sprintf(EMAIL_GV_REDEEM, '<strong>' . $coupon_code . '</strong>')));
          $html_msg['GV_CODE_NUM'] = $coupon_code;
          $html_msg['GV_CODE_URL'] = str_replace('\n','',EMAIL_GV_LINK . '<a href="' . zen_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code, 'NONSSL', false) . '">' . TEXT_GV_NAME . ': ' . $coupon_code . '</a>');
          $html_msg['GV_LINK_OTHER'] = EMAIL_GV_LINK_OTHER;
        } // endif voucher

// add in regular email welcome text
        $email_text .= "\n\n" . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_GV_CLOSURE;
        $html_msg['EMAIL_MESSAGE_HTML']  = str_replace('\n','',EMAIL_TEXT);
        $html_msg['EMAIL_CONTACT_OWNER'] = str_replace('\n','',EMAIL_CONTACT);
        $html_msg['EMAIL_CLOSURE']       = nl2br(EMAIL_GV_CLOSURE);

// include create-account-specific disclaimer
        $email_text .= "\n\n" . sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, STORE_OWNER_EMAIL_ADDRESS). "\n\n";
        $html_msg['EMAIL_DISCLAIMER'] = sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS .' </a>');

// send welcome email
        if (trim(EMAIL_SUBJECT) != 'n/a') zen_mail($name, $fbdata['email'], EMAIL_SUBJECT, $email_text, STORE_NAME, EMAIL_FROM, $html_msg, 'welcome');
  // send additional emails
        if (SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO_STATUS == '1' and SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO !='') {
          if ($_SESSION['customer_id']) {
            $account_query = "select customers_firstname, customers_lastname, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
            $account = $db->Execute($account_query);
          }
	      $extra_info=email_collect_extra_info($name,$fbdata['email'], $account->fields['customers_firstname'] . ' ' . $account->fields['customers_lastname'], $account->fields['customers_email_address'], $account->fields['customers_telephone'], $account->fields['customers_fax']);
          $html_msg['EXTRA_INFO'] = $extra_info['HTML'];
          if (trim(SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO_SUBJECT) != 'n/a') zen_mail('', SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO, SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO_SUBJECT . ' ' . EMAIL_SUBJECT, $email_text . $extra_info['TEXT'], STORE_NAME, EMAIL_FROM, $html_msg, 'welcome_extra');
        } //endif send extra emails
		?><script>window.opener.location="<?php echo zen_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL');?>";
		window.close();
		</script> 
		<?php //zen_redirect(zen_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
      }
    }
	     else {?>
	       <script>window.opener.location="<?php echo zen_href_link(FILENAME_PAGE_NOT_FOUND, 'Error- Not getting facebook profile data', 'SSL');?>";
		     window.close();
		   </script> 
		<?php
        }
	  }
	  else {?>
	       <script>window.opener.location="<?php echo zen_href_link(FILENAME_PAGE_NOT_FOUND, 'Error- empty facebook access token', 'SSL');?>";
		     window.close();
		   </script> 
		<?php
     }
  }
      
/*
 * Get the wall post settings.
 */
	function facebookall_wallpost_curl ($attachment,$fbid) {
	  if (function_exists('curl_init')) {
	    $url = "https://graph.facebook.com/".$fbid."/feed";
        // set the target url
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
        $result = curl_exec($ch);
        curl_close ($ch);
      }
    }

	
/**
 * Function for choose api.
 */
  function get_fb_contents( $url, $useapi) {
    if ($useapi == 'CURL') {
	  if (in_array('curl', get_loaded_extensions ()) AND function_exists('curl_exec')) {
        $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
		curl_close($curl);
        return $response;
	  }
	  else {
	    zen_redirect(zen_href_link(FILENAME_PAGE_NOT_FOUND, 'Error- cURL not working on your server', 'SSL'));
	  }
    }
	else {
	   $response = file_get_contents($url);
	   if (!empty($response)) {
	     return $response;
	   }
	   else {
	     zen_redirect(zen_href_link(FILENAME_PAGE_NOT_FOUND, 'Error- fopen not working on your server', 'SSL'));
	   }
	}
  }
  
/**
 * Function getting facebook user profile data.
 */
  function getfbuser_data($fbuser_info) {
    $fbdata['id'] = (!empty($fbuser_info->id) ? $fbuser_info->id : '');
	$fbdata['first_name'] = (!empty($fbuser_info->first_name) ? $fbuser_info->first_name : '');
    $fbdata['last_name'] = (!empty($fbuser_info->last_name) ? $fbuser_info->last_name : '');
	$fbdata['dob'] = (!empty($fbuser_info->birthday) ? $fbuser_info->birthday : '');
    $fbdata['gender'] = (!empty($fbuser_info->gender) ? $fbuser_info->gender : '');
	if ($fbdata['gender'] == 'male') {
	  $fbdata['gender'] ='m';
	}
	if ($fbdata['gender'] == 'female') {
	  $fbdata['gender'] ='f';
	}
    $fbdata['company'] =  (!empty( $fbuser_info->work[0]->employer->name) ?  $fbuser_info->work[0]->employer->name : '');
	$location = (!empty($fbuser_info->hometown->name) ? $fbuser_info->hometown->name : '');
	if (!empty($location)) {
	  $location = explode(',', $location);
	}				
	$fbdata['city'] = (!empty($location[0]) ? $location[0] : $fbuser_info->location->name);
    $fbdata['state'] = (!empty($location[1]) ? trim($location[1]) : $fbuser_info->location->name);
	$fbdata['country'] = (!empty($location[2]) ? trim($location[2]) : '');
    $fbdata['address'] = (!empty($fbuser_info->hometown->name) ? $fbuser_info->hometown->name : '');
	if (empty($fbdata['address'])) {
	  $fbdata['address'] = (!empty($fbuser_info->hometown->name) ? $fbuser_info->hometown->name : $fbuser_info->location->name);
	}
	$fbdata['telephone'] = 'enter ph number';
    $fbdata['password'] = mt_rand(8, 15);
    $fbdata['email'] = (!empty($fbuser_info->email) ? $fbuser_info->email : '');
	if (empty($fbdata['first_name'])) {
	  $user_emailname = explode('@', $fbdata['email']);
      $fbdata['first_name'] = $user_emailname[0];
      $fbdata['last_name'] = $user_emailname[0];
    }
    $fbdata['thumbnail'] = "https://graph.facebook.com/" . $fbdata['id'] . "/picture?type=large";
    return $fbdata;
  }
 // This should be last line of the script:

$zco_notifier->notify('NOTIFY_HEADER_END_LOGIN');