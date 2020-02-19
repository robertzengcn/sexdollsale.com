<?php

function zen_get_customer_address($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n")
{
    global $db;
	
	$address_query = "select addrbk.entry_firstname as firstname, addrbk.entry_lastname as lastname,
    						addrbk.entry_company as company, addrbk.entry_street_address as street_address, addrbk.entry_suburb as suburb,
							 addrbk.entry_city as city, addrbk.entry_postcode as postcode, addrbk.entry_state as state,
							  addrbk.entry_country_id as country_id, cust.customers_default_address_id as address_id, addrbk.address_book_id as address_book_id
                      from " . TABLE_ADDRESS_BOOK . " addrbk 
					  JOIN " . TABLE_CUSTOMERS . " cust ON cust.customers_id=addrbk.customers_id
                      where cust.customers_id = '" . (int)$customers_id . "'";
    
	$address = $db->Execute($address_query);
	
	while (!$address->EOF) {
		$addresses_array[] = array(
			'id' => $address->fields['address_book_id'],
			'text' => zen_address_format_for_ship_dropdown(
				zen_get_address_format_id($address->fields['country_id']), $address->fields, 0, ' ', ' ')
			);
		
		if ($address_id == $address->fields['address_book_id']) {
			$selected_address = $address->fields;
			
		} else if ($address->fields['address_book_id'] == $address->fields['address_id']) {
				$selected_address = $address->fields;
				
				if (!isset($address_id)) {
					$address_id = $address->fields['address_book_id'];
				}
		}
		
		$address->MoveNext();
	}
	
	$content = zen_draw_pull_down_menu('address_id', $addresses_array, $address_id,
		'onchange="this.form.submit();" name="seAddressPulldown"');
	
	$format_id = zen_get_address_format_id($selected_address['country_id']);
	$content .= zen_address_format($format_id, $selected_address, $html, $boln, $eoln);
	
	return $content;
}


function zen_address_format_for_ship_dropdown($address_format_id, $address, $html, $boln, $eoln)
{
	if (isset($address['country_id']) && zen_not_null($address['country_id'])) {
		$country = zen_get_country_name($address['country_id']);
		
	} else if (isset($address['country']) && zen_not_null($address['country'])) {
		if (is_array($address['country'])) {
			$country = zen_output_string_protected($address['country']['countries_name']);
		} else {
			$country = zen_output_string_protected($address['country']);
		}
	} else {
		$country = '';
	}
	
	$postcode = zen_output_string_protected($address['postcode']);
	$zip = $postcode;
	
	if ($country == '') {
		if (is_array($address['country'])) {
			$country = zen_output_string_protected($address['country']['countries_name']);
		} else {
			$country = zen_output_string_protected($address['country']);
		}
	}
	
	$address_out = $country;
	
	if (isset($zip)) {
		$address_out .= ', ' . $zip;
	}
	
	return $address_out;
}
