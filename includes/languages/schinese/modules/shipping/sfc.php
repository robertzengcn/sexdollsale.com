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
// | Simplified Chinese version   http://www.zen-cart.cn                  |
// +----------------------------------------------------------------------+
// $Id: sfc.php, v1.3 2011-08-10 Jack Huang $
//

  define('MODULE_SHIPPING_SFC_TEXT_TITLE', '三态速递');
  define('MODULE_SHIPPING_SFC_TEXT_DESCRIPTION', '三态速递 SendFromChina.com');
  define('MODULE_SHIPPING_SFC_TEXT_ALERT_CNY', ' (缺少货币定义: 人民币/CNY)');
  define('MODULE_SHIPPING_SFC_TEXT_ALERT_WEIGHT', '请在商店设置-配送参数中设定最大包裹重量');

  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_1_1', '打开三态速递模块');  
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_1_2', '您要启用三态速递吗?<br />在下面的报价折扣中，可以输入百分比: 90%(所有运费九折)，或者折扣金额: 5 (加5元运费), -10 (减10元运费)。默认为: 100%');  
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_2_1', '税率种类');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_2_2', '计算运费使用的税率种类。');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_3_1', '税率基准');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_3_2', '计算运费税的基准。选项为<br />Shipping - 基于客户的交货人地址<br />Billing - 基于客户的帐单地址<br />Store - 如果帐单地址/送货地区和商店地区相同，则基于商店地址');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_4_1', '排序顺序');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_4_2', '显示的顺序。');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_5_1', '设置不能发货的国家或地区，请输入用逗号分隔的两位ISO国家或地区代码');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_5_2', '不适用以下国家或地区: ');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_6_1', ' 的折扣');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_6_2', ' 的报价折扣比例。');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_7_1', '使用代理服务器吗?');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_7_2', '需要通过代理服务器查询报价吗?');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_8_1', '代理服务器');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_8_2', '请输入代理服务器的地址:端口<br />例如: 114.113.147.143:3128');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_9_1', '启用');
  define('MODULE_SHIPPING_SFC_TEXT_CONFIG_9_2', '是否启用');

  //SFT HTTP URL
  define('REQUIRESHIPTYPE','http://www.sendfromchina.com/shipfee/ship_type_list');
  // URL to call
  define('REQUIRERATE','http://www.sendfromchina.com/shipfee/out_rates');

?>