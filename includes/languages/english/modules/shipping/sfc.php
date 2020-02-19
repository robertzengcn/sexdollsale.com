<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Josh Dechant                                      |
// |                                                                      |
// | Portions Copyright (c) 2004 The zen-cart developers                  |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: sfc.php, v1.3 2011-08-10 Jack Huang $
//

  define('MODULE_SHIPPING_SFC_TEXT_TITLE', 'SFC Shipping');
  define('MODULE_SHIPPING_SFC_TEXT_DESCRIPTION', 'SendFromChina.com');
  define('MODULE_SHIPPING_SFC_TEXT_ALERT_CNY', ' (Missing currency: CNY)');
  define('MODULE_SHIPPING_SFC_TEXT_ALERT_WEIGHT', 'Please set Maximum Package Weight in Configuration - Shipping/Packaging');

  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_1_1', 'Enable SFC Shipping?');  
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_1_2', 'Do you want to offer SFC shipping?<br />Discount example: 90%(10% off), 5($5 increase), -10($10 discount). Default: 100%');  
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_2_1', 'Tax Class');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_2_2', 'Use the following tax class on the shipping fee.');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_3_1', 'Tax Basis');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_3_2', 'On what basis is Shipping Tax calculated. Options are<br />Shipping - Based on customers Shipping Address<br />Billing Based on customers Billing address<br />Store - Based on Store address if Billing/Shipping Zone equals Store zone');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_4_1', 'Sort Order');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_4_2', 'Sort order of display.');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_5_1', 'Skip Countries, use a comma separated list of the two character ISO country codes', 'MODULE_SHIPPING_ZONES_SKIPPED');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_5_2', 'Disable for the following Countries:');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_6_1', 'Discount');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_6_2', 'SFC discount');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_7_1', 'Enable PROXY?');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_7_2', 'Do you want to enable proxy?');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_8_1', 'PROXY Details');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_8_2', 'Proxy details<br />Example: 114.113.147.143:3128');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_9_1', 'Enable ');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_9_2', 'Enable ');

  //SFT HTTP URL
  define('REQUIRESHIPTYPE','http://www.sendfromchina.com/shipfee/ship_type_list');
  // URL to call
  define('REQUIRERATE','http://www.sendfromchina.com/shipfee/out_rates');

?>