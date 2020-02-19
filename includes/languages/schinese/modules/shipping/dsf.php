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
// $Id: dsf.php, v2.1 2013-07-01 Jack Huang $
//

  define('MODULE_SHIPPING_DSF_TEXT_TITLE', '递四方快递');
  define('MODULE_SHIPPING_DSF_TEXT_DESCRIPTION', '递四方快递 4px.com');

  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_1_1', '打开递四方快递模块');  
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_1_2', '您要启用递四方快递吗?');  
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_2_1', '税率种类');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_2_2', '计算运费使用的税率种类。');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_3_1', '税率基准');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_3_2', '计算运费税的基准。选项为<br />Shipping - 基于客户的交货人地址<br />Billing - 基于客户的帐单地址<br />Store - 如果帐单地址/送货地区和商店地区相同，则基于商店地址');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_4_1', '排序顺序');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_4_2', '显示的顺序。');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_5_1', '设置不能发货的国家或地区，请输入用逗号分隔的两位ISO国家或地区代码');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_5_2', '不适用以下国家或地区: ');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_6_1', '折扣');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_6_2', '递四方快递的报价折扣比例。可以输入百分比: 90%(所有运费九折)，或者折扣金额: 5 (加5元运费), -10 (减10元运费)。默认为: 100%');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_7_1', '授权 Token');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_7_2', '演示网站可使用默认的 Token: CD29AD1E6703C0DB57271CA42B87A7D9<br />要正式启用报价查询，请在 4PX.com 注册并取得您自己的 Token');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_8_1', '起运地');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_8_2', '请选择起运地');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_9_1', '货物类型');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_9_2', '货物类型: P - 包裹(默认) / D - 文件');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_10_1', '接口模式');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_10_2', '接口模式: <br />Demo - 演示模式 (默认)<br />Live - 正式网站(请在上面选项中填写4px给您的授权Token)');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_11_1', '使用代理服务器吗?');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_11_2', '需要通过代理服务器查询报价吗?');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_12_1', '代理服务器地址');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_12_2', '请输入代理服务器的地址<br />例如: 114.113.147.143');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_13_1', '代理服务器端口');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_13_2', '请输入代理服务器的端口<br />例如: 3128');

  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_20_1', '启用');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_20_2', '代码: ');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_21_1', '的折扣');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_21_2', '的报价折扣比例。');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_22_1', '的自定义名称');
  define('MODULE_SHIPPING_DSF_TEXT_CONFIG_22_2', '用两个冒号分隔英文和中文名称。默认: ');

?>