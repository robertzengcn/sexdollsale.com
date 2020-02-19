<?php
	if (!nmx_check_field(TABLE_CONFIGURATION, 'configuration_tab')) $db->Execute("ALTER TABLE " . TABLE_CONFIGURATION . " ADD COLUMN configuration_tab varchar(32) NOT NULL DEFAULT 'General';");
      
  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " 
		SET configuration_tab = 'Layout' 
		WHERE configuration_key IN ('FEC_SPLIT_CHECKOUT',
  		'FEC_DROP_DOWN',
  		'FEC_GIFT_WRAPPING_SWITCH',
  		'FEC_GIFT_MESSAGE',
  		'FEC_DROP_DOWN_LIST',
  		'FEC_CHECKBOX',
  		'FEC_ORDER_TOTAL',
  		'FEC_CONFIDENCE');");
  		
  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " 
		SET configuration_tab = 'Guest Checkout' 
		WHERE configuration_key IN ('FEC_NOACCOUNT_SWITCH',
  		'FEC_NOACCOUNT_POSITION',
  		'FEC_NOACCOUNT_ONLY_SWITCH',
  		'FEC_NOACCOUNT_COMBINE',
  		'FEC_NOACCOUNT_HIDEEMAIL',
  		'FEC_NOACCOUNT_LOGOFF',
  		'FEC_FREE_VIRTUAL_CHECKOUT');");  		
  		
