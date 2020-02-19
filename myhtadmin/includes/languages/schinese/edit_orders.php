<?php
/**
 * @package edit_orders
 * @copyright Copyright (C) 2008 office andplus
 * @copyright Portions Copyright (C) 2008 Zen Cart.JP
 * @copyright Portions Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @author mmochi
 * @version $Id: edit_orders.php $
 */

define('HEADING_TITLE', '订单修改');//Editing Order
define('HEADING_TITLE_SEARCH', '订单号:');//Order ID:
define('HEADING_TITLE_STATUS', '订单状态:');//Status:
define('ADDING_TITLE', '增加商品');//Adding a Product to Order

define('ENTRY_UPDATE_TO_CC', '修改时请慎重');//(Update to <b>Credit Card</b> to view CC fields.)
define('TABLE_HEADING_COMMENTS', '客户备注');//Comments
define('TABLE_HEADING_CUSTOMERS', '客户');//Customers
define('TABLE_HEADING_ORDER_TOTAL', '总额');//Order Total
define('TABLE_HEADING_DATE_PURCHASED', '购买日期');//Date Purchased
define('TABLE_HEADING_STATUS', '状态');//Status
define('TABLE_HEADING_ACTION', '操作');//Action
define('TABLE_HEADING_QUANTITY', '数量.');//Qty.
define('TABLE_HEADING_PRODUCTS_MODEL', '型号');//Model
define('TABLE_HEADING_PRODUCTS', '商品');//Products
define('TABLE_HEADING_TAX', '税');//Tax
define('TABLE_HEADING_TOTAL', '总额');//Total
define('TABLE_HEADING_UNIT_PRICE', '单价');//Unit Price
define('TABLE_HEADING_TOTAL_PRICE', '总额');//Total Price

define('TABLE_HEADING_PRICE_EXCLUDING_TAX', '单价 (不含税)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', '单价 (含税)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', '总额 (不含税)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', '总额 (含税)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', '通知客户');
define('TABLE_HEADING_DATE_ADDED', '加入日期');

define('ENTRY_CUSTOMER', '客户:');
define('ENTRY_CUSTOMER_NAME', '姓名');
define('ENTRY_CUSTOMER_COMPANY', '公司');
define('ENTRY_CUSTOMER_ADDRESS', '详细地址');
define('ENTRY_CUSTOMER_SUBURB', '地址2');
define('ENTRY_CUSTOMER_CITY', '城市');
define('ENTRY_CUSTOMER_STATE', '省份');
define('ENTRY_CUSTOMER_POSTCODE', '邮编');
define('ENTRY_CUSTOMER_COUNTRY', '国:');

define('ENTRY_SOLD_TO', '客户:');
define('ENTRY_DELIVERY_TO', '送货地址:');
define('ENTRY_SHIP_TO', '送货地址:');
define('ENTRY_SHIPPING_ADDRESS', '送货地址:');
define('ENTRY_BILLING_ADDRESS', '帐单地址:');
define('ENTRY_PAYMENT_METHOD', '支付方式:');
define('ENTRY_CREDIT_CARD_TYPE', '信用卡类型:');
define('ENTRY_CREDIT_CARD_OWNER', '信用卡所有者:');
define('ENTRY_CREDIT_CARD_NUMBER', '信用卡号:');
define('ENTRY_CREDIT_CARD_EXPIRES', '信用卡有效期:');
define('ENTRY_SUB_TOTAL', '小计:');
define('ENTRY_TAX', '税金:');
define('ENTRY_SHIPPING', '发货:');
define('ENTRY_TOTAL', '总额:');
define('ENTRY_DATE_PURCHASED', '订货日:');
define('ENTRY_STATUS', '状态:');
define('ENTRY_DATE_LAST_UPDATED', '更新日:');
define('ENTRY_NOTIFY_CUSTOMER', '通知客户:');
define('ENTRY_NOTIFY_COMMENTS', '增加备注:');
define('ENTRY_PRINTABLE', '订单印刷');

define('TEXT_INFO_HEADING_DELETE_ORDER', '删除订单');
define('TEXT_INFO_DELETE_INTRO', '真的要删除这个订单吗？');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', '恢复数量');
define('TEXT_DATE_ORDER_CREATED', '日期:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', '更新日:');
define('TEXT_DATE_ORDER_ADDNEW', '增加新商品.');
define('TEXT_INFO_PAYMENT_METHOD', '支付方式:');

define('TEXT_ALL_ORDERS', '全订单');
define('TEXT_NO_ORDER_HISTORY', '没有有效的订单');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', '订单处理情况的通知');
define('EMAIL_TEXT_ORDER_NUMBER', '订单号:');
define('EMAIL_TEXT_INVOICE_URL', '关于订单的详细请参照下記URLで。:');
define('EMAIL_TEXT_DATE_ORDERED', '订单日:');
define('EMAIL_TEXT_COMMENTS_UPDATE', '<em>[联系事项]: </em>');
define('EMAIL_TEXT_STATUS_UPDATED', '订单状况:' . "\n");
define('EMAIL_TEXT_STATUS_LABEL', '<strong>现在的订单状况:</strong> %s' . "\n\n");
define('EMAIL_TEXT_STATUS_PLEASE_REPLY', '有什么问题请联系。' . "\n");

define('ERROR_ORDER_DOES_NOT_EXIST', '问题: 没有订单。');
define('SUCCESS_ORDER_UPDATED', '成功: 更新了订单状态。');
define('WARNING_ORDER_NOT_UPDATED', '警告:订单没有更新。');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', '请选择商品');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', '请选择选项');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', '没有选项..');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', '数量.');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', '增加商品！');

define('SUCCESS_ORDER_UPDATED_BY_POINTS', '购物返点更新成功');


define('EO_PAYMOD_GIFT_COUPON', '礼卷/优惠卷');
define('EO_PAYMOD_POINTS', '购物返点');

?>