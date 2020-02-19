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
// $Id: dsf.php, v2.1 2013-07-01 Jack Huang $
//

  define('MODULE_SHIPPING_DSF_TEXT_TITLE', '4PX Shipping');
  define('MODULE_SHIPPING_DSF_TEXT_DESCRIPTION', '4PX Shipping');

  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_1_1', 'Enable 4PX Shipping?');  
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_1_2', 'Do you want to offer 4PX shipping?');  
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_2_1', 'Tax Class');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_2_2', 'Use the following tax class on the shipping fee.');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_3_1', 'Tax Basis');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_3_2', 'On what basis is Shipping Tax calculated. Options are<br />Shipping - Based on customers Shipping Address<br />Billing Based on customers Billing address<br />Store - Based on Store address if Billing/Shipping Zone equals Store zone');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_4_1', 'Sort Order');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_4_2', 'Sort order of display.');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_5_1', 'Skip Countries, use a comma separated list of the two character ISO country codes', 'MODULE_SHIPPING_ZONES_SKIPPED');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_5_2', 'Disable for the following Countries:');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_6_1', 'Discount');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_6_2', '4PX discount. Example: 90%(10% off), 5($5 increase), -10($10 discount). Default: 100%');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_7_1', 'Token');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_7_2', 'Default token for testing: CD29AD1E6703C0DB57271CA42B87A7D9<br />To go live, please register your own account and get the token at 4PX.com.');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_8_1', 'Start Shipment ID');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_8_2', 'Please select place of shipment');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_9_1', 'Cargo Code');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_9_2', 'Cargo Code: P - Package(Default) / D - Document');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_10_1', 'Module Mode');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_10_2', 'Module mode: <br />Demo - For testing (Default)<br />Live - Production website (please update your token above)');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_11_1', 'Enable PROXY?');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_11_2', 'Do you want to enable proxy?');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_12_1', 'PROXY Server');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_12_2', 'Proxy server<br />Example: 114.113.147.143');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_13_1', 'PROXY Port');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_13_2', 'Proxy port<br />Example: 3128');

  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_20_1', 'Enable ');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_20_2', 'Code: ');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_21_1', 'discount');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_21_2', 'SFC discount');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_22_1', 'customized Name');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_22_2', 'Use two colon to separate English name and Chinese name. Default: ');

?>