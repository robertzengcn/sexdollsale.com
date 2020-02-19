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

  class dsf {
    var $code, $title, $description, $icon, $enabled, $num_zones, $num_tables, $delivery_geozone, $order_total;

    function dsf() {
      global $db, $order;

      $this->code = 'dsf';
      $this->title = MODULE_SHIPPING_DSF_TEXT_TITLE;

      if (MODULE_SHIPPING_DSF_MODE == 'Demo') {
  	    //在线订单操作
        define('MODULE_SHIPPING_DSF_ORDERS_OPERATION_URLS', 'http://apisandbox.4pxtech.com:8090/OrderOnline/ws/OrderOnlineService.dll?wsdl');
      	//在线订单工具
        define('MODULE_SHIPPING_DSF_ORDERS_TOOLS_URLS', 'http://apisandbox.4pxtech.com:8090/OrderOnlineTool/ws/OrderOnlineToolService.dll?wsdl');
      } else {
  	    //在线订单操作
        define('MODULE_SHIPPING_DSF_ORDERS_OPERATION_URLS', 'http://api.4px.com/OrderOnlineService.dll?wsdl');
      	//在线订单工具
        define('MODULE_SHIPPING_DSF_ORDERS_TOOLS_URLS', 'http://api.4px.com/OrderOnlineToolService.dll?wsdl');
      }

      @ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
      set_time_limit(600);
      include 'dsf/OrderOnlineTools.php';

      $check_cny = $db->Execute("SELECT code FROM " . TABLE_CURRENCIES . " WHERE code = 'CNY'");
      if (!$check_cny->RecordCount()) {
        $this->title .=  '<span class="alert"> (缺少货币定义: 人民币/CNY)</span>';
      }

      $this->description = MODULE_SHIPPING_DSF_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_DSF_SORT_ORDER;
      $this->tax_class = MODULE_SHIPPING_DSF_TAX_CLASS;
      $this->tax_basis = MODULE_SHIPPING_DSF_TAX_BASIS;

      // disable only when entire cart is free shipping
      if (zen_get_shipping_enabled($this->code)) {
        $this->enabled = ((MODULE_SHIPPING_DSF_STATUS == 'True') ? true : false);
      }

      if ($this->enabled == true) {
        $this->enabled = false;

        foreach($this->keys() as $key) {
          if (constant($key) == 'True' ) {
            $this->enabled = true;
            break;
          }
        }
      }
    }

// class methods
    function quote($method = '') {
      global $db, $order, $shipping_weight, $shipping_num_boxes, $template, $currencies;

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_DSF_TEXT_TITLE,
                            'methods' => array());

      if ((TEXT_SHIPPING_WEIGHT == '克') || (TEXT_SHIPPING_WEIGHT == 'g')) {
        $dsf_weight = $shipping_weight / 100 * $shipping_num_boxes;
      } else {
        $dsf_weight = $shipping_weight * $shipping_num_boxes;
      }

      $dsf_code_query = $db->Execute("SELECT configuration_value, configuration_key FROM " . TABLE_CONFIGURATION . " WHERE configuration_key like 'MODULE_SHIPPING_DSF_PRODUCT_CODE_%' AND configuration_value = 'True'");

      while (!$dsf_code_query->EOF) {
        $dsf_product_code_config = substr($dsf_code_query->fields['configuration_key'],33);
        $dsf_product_code = array($dsf_product_code_config);
        $dsf_code[$dsf_product_code_config] = $dsf_code_query->fields['configuration_value'];

        $dsf_discount_query = $db->Execute("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_SHIPPING_DSF_DISCOUNT_" . $dsf_product_code_config . "'");
        $dsf_discount[$dsf_product_code_config] = $dsf_discount_query->fields['configuration_value'];
        $dsf_name_query = $db->Execute("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_SHIPPING_DSF_NAME_" . $dsf_product_code_config . "'");
        $dsf_name[$dsf_product_code_config] = $dsf_name_query->fields['configuration_value'];

        //运费试算
        $profile = array(
      	  	//货物类型(默认：P)(Length = 1)
      		  "cargoCode" => MODULE_SHIPPING_DSF_CARGO_CODE,
      		  //目的国家二字代码，参照国家代码表(Length = 2)
      		  "countryCode" => get_4px_country_code($order->delivery['country']['id']),
      		  //产品代码,该属性不为空，只返回该产品计费结果，参照产品代码表(Length = 2)
      		  "productCode" => $dsf_product_code,
      		  //计费结果产品显示级别(默认：1)(Length = 1)
      		  "displayOrder" => '1',
      		  //邮编(Length <= 10)
      		  "postCode" => SHIPPING_ORIGIN_ZIP,
      		  //起运地ID，参照起运地ID表(Length <= 4)
      		  "startShipmentId" => MODULE_SHIPPING_DSF_START_ID,
      		  //计费重量，单位(kg)(0 < Amount <= [3,3])
      		  "weight" => strval($dsf_weight),
      		  //高(0 < Amount <= [3,3])
      		  "height" => '',
      		  //长(0 < Amount <= [3,3])
      		  "length" => '',
      		  //宽(0 < Amount <= [3,3])
      		  "width" => ''
        );

        $doc = new OrderOnlineTools();
        $dataset = $doc->chargeCalculateService($profile);

      // Check to see if the response was loaded, else print an error
      if ( $dataset['ack'] == 'Success') {

          $totalfee = $dataset['totalRmbAmount'];
          $iftracking = $dataset['tracking'];
          $shipnote = $dataset['note'];

          if (substr_count($dsf_discount[$dataset['productCode']], '%') > 0) {
            $totalfee = ROUND($totalfee / $currencies->get_value('CNY') * (str_replace('%', '', $dsf_discount[$dataset['productCode']])) / 100, 2);
          } elseif (substr_count($dsf_discount[$dataset['productCode']], '％') > 0) {
            $totalfee = ROUND($totalfee / $currencies->get_value('CNY') * (str_replace('％', '', $dsf_discount[$dataset['productCode']])) / 100, 2);
          } else {
            $totalfee = ROUND($totalfee / $currencies->get_value('CNY') + str_replace('$', '', $dsf_discount[$dataset['productCode']]), 2);
          }

          $dsf_search_pos = strpos($dsf_name[$dataset['productCode']], '::');
          if ( ($_SESSION['language'] == 'schinese') and $dsf_search_pos > 0) {
            $shipname = substr($dsf_name[$dataset['productCode']], $dsf_search_pos + 2) . ' ' . $dataset['deliveryperiod'] . ' 天';
          } else {
            $shipname = substr($dsf_name[$dataset['productCode']], 0, $dsf_search_pos) . ' ' . $dataset['deliveryperiod'] . ' days';
          }

          if ($totalfee < 0) { $totalfee = 0; }

          if ($dsf_code[$dsf_product_code_config] == 'True') {
            $this->quotes['methods'][] = array('id' => $dataset['productCode'],
                                             'title' => $shipname,
                                              'cost' => $totalfee);
          }
      } else {
        // retrieve error message
        $error = $dataset['cnMessage'];
        // echo $error;
      }

        $dsf_code_query->MoveNext();
      }

      // show boxes if weight
      switch (SHIPPING_BOX_WEIGHT_DISPLAY) {
        case (0):
          $show_box_weight = '';
          break;
        case (1):
          $show_box_weight = ' (' . $shipping_num_boxes . ' ' . TEXT_SHIPPING_BOXES . ')';
          break;
        case (2):
          $show_box_weight = ' (' . number_format($shipping_weight,2) . TEXT_SHIPPING_WEIGHT . ')';
          break;
        default:
          $show_box_weight = ' (' . $shipping_num_boxes . ' x ' . number_format($shipping_weight,2) . TEXT_SHIPPING_WEIGHT . ')';
          break;
      }

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      return $this->quotes;
    }  

    function check() {
      global $db;

      if (!isset($this->_check)) {
        $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_DSF_STATUS'");
        $this->_check = $check_query->RecordCount();
      }
      return $this->_check;
    }

    function install() {
      global $db;

      if (!defined('MODULE_SHIPPING_DSF_TEXT_CONFIG_1_1')) include(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/shipping/' . $this->code . '.php');

    $db->Execute('DROP TABLE IF EXISTS ' . TABLE_DSF_COUNTRY);
		$db->Execute(
			'CREATE TABLE ' . TABLE_DSF_COUNTRY . ' ( ' .
				'countries_id int(11) NOT NULL auto_increment, ' .
				'country_code varchar(2) NOT NULL default \'\', ' .
				'countries_name varchar(100) NOT NULL default \'\', ' .
				'countries_cn_name varchar(100) NOT NULL default \'\', ' .
				'created DATETIME NULL default \'0000-00-00 00:00:00\', ' .
				'modified DATETIME NULL default \'0000-00-00 00:00:00\', ' .
				'status varchar(2) default \'\', ' .
				'PRIMARY KEY (countries_id) ' .
			') CHARACTER SET utf8 COLLATE utf8_general_ci'
		);

        $db->Execute("INSERT INTO " . TABLE_DSF_COUNTRY . " (countries_id, country_code, countries_name, countries_cn_name, created, modified, status) VALUES 
          ('1', 'AD', 'ANDORRA', '安道尔', NULL, NULL, NULL), 
          ('2', 'AE', 'UNITED ARAB EMIRATES', '阿拉伯联合酋长国', NULL, NULL, NULL), 
          ('3', 'AF', 'AFGHANISTAN', '阿富汗', NULL, NULL, NULL), 
          ('4', 'AG', 'ANTIGUA AND BARBUDA', '安提瓜及巴布达', NULL, NULL, NULL), 
          ('5', 'AI', 'ANGUILLA', '安圭拉岛', NULL, NULL, NULL), 
          ('6', 'AL', 'ALBANIA', '阿尔巴尼亚', NULL, NULL, NULL), 
          ('7', 'AM', 'ARMENIA', '亚美尼亚', NULL, NULL, NULL), 
          ('8', 'AN', 'NETHERLANDS ANTILLES', '荷属安的列斯群岛', NULL, NULL, NULL), 
          ('9', 'AO', 'ANGOLA', '安哥拉', NULL, NULL, NULL), 
          ('10', 'AR', 'ARGENTINA', '阿根廷', NULL, NULL, NULL), 
          ('11', 'AS', 'AMERICAN SAMOA', '美属萨摩亚群岛', NULL, NULL, NULL), 
          ('12', 'AT', 'AUSTRIA', '奥地利', NULL, NULL, NULL), 
          ('13', 'AU', 'AUSTRALIA', '澳大利亚', NULL, NULL, NULL), 
          ('14', 'AW', 'ARUBA', '阿鲁巴岛', NULL, NULL, NULL), 
          ('15', 'AZ', 'AZERBAIJAN', '阿塞拜疆(独联体)', NULL, NULL, NULL), 
          ('16', 'BA', 'BOSNIA AND HERZEGOVINA', '波斯尼亚-黑塞哥维那共和国', NULL, NULL, NULL), 
          ('17', 'BB', 'BARBADOS', '巴巴多斯', NULL, NULL, NULL), 
          ('18', 'BD', 'BANGLADESH', '孟加拉国', NULL, NULL, NULL), 
          ('19', 'BE', 'BELGIUM', '比利时', NULL, NULL, NULL), 
          ('20', 'BF', 'BURKINA FASO', '布基纳法索', NULL, NULL, NULL), 
          ('21', 'BG', 'BULGARIA', '保加利亚', NULL, NULL, NULL), 
          ('22', 'BH', 'BAHRAIN', '巴林', NULL, NULL, NULL), 
          ('23', 'BI', 'BURUNDI', '布隆迪', NULL, NULL, NULL), 
          ('24', 'BJ', 'BENIN', '贝宁', NULL, NULL, NULL), 
          ('25', 'BM', 'BERMUDA', '百慕大', NULL, NULL, NULL), 
          ('26', 'BN', 'BRUNEI', '文莱', NULL, NULL, NULL), 
          ('27', 'BO', 'BOLIVIA', '波利维亚', NULL, NULL, NULL), 
          ('28', 'BR', 'BRAZIL', '巴西', NULL, NULL, NULL), 
          ('29', 'BS', 'BAHAMAS', '巴哈马', NULL, NULL, NULL), 
          ('30', 'BT', 'BHUTAN', '不丹', NULL, NULL, NULL), 
          ('31', 'BV', 'BOUVET ISLAND', '布维岛', NULL, NULL, NULL), 
          ('32', 'BW', 'BOTSWANA', '博茨瓦纳', NULL, NULL, NULL), 
          ('33', 'BY', 'BELARUS', '白俄罗斯(独联体)', NULL, NULL, NULL), 
          ('34', 'BZ', 'BELIZE', '伯利兹', NULL, NULL, NULL), 
          ('35', 'CA', 'CANADA', '加拿大', NULL, NULL, NULL), 
          ('36', 'CC', 'COCOS(KEELING)ISLANDS', '科科斯群岛', NULL, NULL, NULL), 
          ('37', 'CD', 'CONGO REPUBLIC', '刚果民主共和国', NULL, NULL, NULL), 
          ('38', 'CF', 'CENTRAL REPUBLIC', '中非共和国', NULL, NULL, NULL), 
          ('39', 'CG', 'CONGO', '刚果', NULL, NULL, NULL), 
          ('40', 'CH', 'SWITZERLAND', '瑞士', NULL, NULL, NULL), 
          ('41', 'CI', 'COTE DLVOIRE(IVORY)', '科特迪瓦(象牙海岸)', NULL, NULL, NULL), 
          ('42', 'CK', 'COOK ISLANDS', '库克群岛', NULL, NULL, NULL), 
          ('43', 'CL', 'CHILE', '智利', NULL, NULL, NULL), 
          ('44', 'CM', 'CAMEROON', '喀麦隆', NULL, NULL, NULL), 
          ('45', 'CN', 'CHINA', '中国', NULL, NULL, NULL), 
          ('46', 'CO', 'COLOMBIA', '哥伦比亚', NULL, NULL, NULL), 
          ('47', 'CR', 'COSTA RICA', '哥斯达黎加', NULL, NULL, NULL), 
          ('48', 'CU', 'CUBA', '古巴', NULL, NULL, NULL), 
          ('49', 'CV', 'CAPE VERDE', '佛得角群岛', NULL, NULL, NULL), 
          ('50', 'CX', 'CHRISTMAS ISLAND', '圣诞岛', NULL, NULL, NULL), 
          ('51', 'CY', 'CYPRUS', '塞浦路斯', NULL, NULL, NULL), 
          ('52', 'CZ', 'CZECH REPUBLIC', '捷克共和国', NULL, NULL, NULL), 
          ('53', 'DE', 'GERMANY', '德国', NULL, NULL, NULL), 
          ('54', 'DJ', 'DJIBOUTI', '吉布提', NULL, NULL, NULL), 
          ('55', 'DK', 'DENMARK', '丹麦', NULL, NULL, NULL), 
          ('56', 'DM', 'DOMINICA', '多米尼克', NULL, NULL, NULL), 
          ('57', 'DO', 'DOMINICAN REPUBLIC', '多米尼加共合国', NULL, NULL, NULL), 
          ('58', 'DZ', 'ALGERIA', '阿尔及利亚', NULL, NULL, NULL), 
          ('59', 'EC', 'ECUADOR', '厄瓜多尔', NULL, NULL, NULL), 
          ('60', 'EE', 'ESTONIA', '爱沙尼亚', NULL, NULL, NULL), 
          ('61', 'EG', 'EGYPT', '埃及', NULL, NULL, NULL), 
          ('62', 'EH', 'WESTERN SAHARA', '西撒哈拉', NULL, NULL, NULL), 
          ('63', 'ER', 'ERITREA', '厄里特立亚', NULL, NULL, NULL), 
          ('64', 'ES', 'SPAIN', '西班牙', NULL, NULL, NULL), 
          ('65', 'ET', 'ETHIOPIA', '埃塞俄比亚', NULL, NULL, NULL), 
          ('66', 'FI', 'FINLAND', '芬兰', NULL, NULL, NULL), 
          ('67', 'FJ', 'FIJI', '斐济', NULL, NULL, NULL), 
          ('68', 'FK', 'FALKLAND ISLAND', '福克兰群岛', NULL, NULL, NULL), 
          ('69', 'FM', 'MICRONESIA', '密克罗尼西亚', NULL, NULL, NULL), 
          ('70', 'FO', 'FAROE ISLANDS', '法鲁群岛', NULL, NULL, NULL), 
          ('71', 'FR', 'FRANCE', '法国', NULL, NULL, NULL), 
          ('72', 'FX', 'FRANCE, METROPOLITAN', '法属美特罗波利坦', NULL, NULL, NULL), 
          ('73', 'GA', 'GABON', '加蓬', NULL, NULL, NULL), 
          ('74', 'GB', 'UNITED KINGDOM', '英国', NULL, NULL, NULL), 
          ('75', 'GD', 'GRENADA', '格林纳达', NULL, NULL, NULL), 
          ('76', 'GE', 'GEORGIA', '格鲁吉亚', NULL, NULL, NULL), 
          ('77', 'GF', 'FRENCH GUIANA', '法属圭亚那', NULL, NULL, NULL), 
          ('78', 'GG', 'GUERNSEY', '根西岛', NULL, NULL, NULL), 
          ('79', 'GH', 'GHANA', '加纳', NULL, NULL, NULL), 
          ('80', 'GI', 'GIBRALTAR', '直布罗陀', NULL, NULL, NULL), 
          ('81', 'GL', 'GREENLAND', '格陵兰', NULL, NULL, NULL), 
          ('82', 'GM', 'GAMBIA', '冈比亚', NULL, NULL, NULL), 
          ('83', 'GN', 'GUINEA', '几内亚', NULL, NULL, NULL), 
          ('84', 'GP', 'GUADELOUPE', '瓜德罗普', NULL, NULL, NULL), 
          ('85', 'GQ', 'EQUATORIAL GUINEA', '赤道几内亚', NULL, NULL, NULL), 
          ('86', 'GR', 'GREECE', '希腊', NULL, NULL, NULL), 
          ('87', 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISL', '南乔治亚岛和南桑威奇群岛', NULL, NULL, NULL), 
          ('88', 'GT', 'GUATEMALA', '危地马拉', NULL, NULL, NULL), 
          ('89', 'GU', 'GUAM', '关岛', NULL, NULL, NULL), 
          ('90', 'GW', 'GUINEA BISSAU', '几内亚比绍', NULL, NULL, NULL), 
          ('91', 'GY', 'GUYANA (BRITISH)', '圭亚那', NULL, NULL, NULL), 
          ('92', 'HK', 'HONG KONG', '香港', NULL, NULL, NULL), 
          ('93', 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', '赫德岛和麦克唐纳岛', NULL, NULL, NULL), 
          ('94', 'HN', 'HONDURAS', '洪都拉斯', NULL, NULL, NULL), 
          ('95', 'HR', 'CROATIA', '克罗地亚', NULL, NULL, NULL), 
          ('96', 'HT', 'HAITI', '海地', NULL, NULL, NULL), 
          ('97', 'HU', 'HUNGARY', '匈牙利', NULL, NULL, NULL), 
          ('98', 'IC', 'CANARY ISLANDS', '加那利群岛', NULL, NULL, NULL), 
          ('99', 'ID', 'INDONESIA', '印度尼西亚', NULL, NULL, NULL), 
          ('100', 'IE', 'IRELAND', '爱尔兰', NULL, NULL, NULL), 
          ('101', 'IL', 'ISRAEL', '以色列', NULL, NULL, NULL), 
          ('102', 'IN', 'INDIA', '印度', NULL, NULL, NULL), 
          ('103', 'IO', 'BRITISH INDIAN OCEAN TERRITORY', '英属印度洋地区(查各群岛)', NULL, NULL, NULL), 
          ('104', 'IQ', 'IRAQ', '伊拉克', NULL, NULL, NULL), 
          ('105', 'IR', 'IRAN (ISLAMIC REPUBLIC OF)', '伊朗', NULL, NULL, NULL), 
          ('106', 'IS', 'ICELAND', '冰岛', NULL, NULL, NULL), 
          ('107', 'IT', 'ITALY', '意大利', NULL, NULL, NULL), 
          ('108', 'JE', 'JERSEY', '泽西岛(英属)', NULL, NULL, NULL), 
          ('109', 'JM', 'JAMAICA', '牙买加', NULL, NULL, NULL), 
          ('110', 'JO', 'JORDAN', '约旦', NULL, NULL, NULL), 
          ('111', 'JP', 'JAPAN', '日本', NULL, NULL, NULL), 
          ('112', 'JU', 'YUGOSLAVIA', '南斯拉夫', NULL, NULL, NULL), 
          ('113', 'KE', 'KENYA', '肯尼亚', NULL, NULL, NULL), 
          ('114', 'KG', 'KYRGYZSTAN', '吉尔吉斯斯坦', NULL, NULL, NULL), 
          ('115', 'KH', 'CAMBODIA', '柬埔寨', NULL, NULL, NULL), 
          ('116', 'KI', 'KIRIBATI REPUBILC', '基利巴斯共和国', NULL, NULL, NULL), 
          ('117', 'KM', 'COMOROS', '科摩罗', NULL, NULL, NULL), 
          ('118', 'KN', 'SAINT KITTS', '圣基茨', NULL, NULL, NULL), 
          ('119', 'KP', 'NORTH KOREA', '朝鲜', NULL, NULL, NULL), 
          ('120', 'KR', 'SOUTH KOREA', '韩国', NULL, NULL, NULL), 
          ('121', 'KV', 'KOSOVO', '科索沃', NULL, NULL, NULL), 
          ('122', 'KW', 'KUWAIT', '科威特', NULL, NULL, NULL), 
          ('123', 'KY', 'CAYMAN ISLANDS', '开曼群岛', NULL, NULL, NULL), 
          ('124', 'KZ', 'KAZAKHSTAN', '哈萨克斯坦', NULL, NULL, NULL), 
          ('125', 'LA', 'LAOS', '老挝', NULL, NULL, NULL), 
          ('126', 'LB', 'LEBANON', '黎巴嫩', NULL, NULL, NULL), 
          ('127', 'LC', 'ST. LUCIA', '圣卢西亚', NULL, NULL, NULL), 
          ('128', 'LI', 'LIECHTENSTEIN', '列支敦士登', NULL, NULL, NULL), 
          ('129', 'LK', 'SRI LANKA', '斯里兰卡', NULL, NULL, NULL), 
          ('130', 'LR', 'LIBERIA', '利比里亚', NULL, NULL, NULL), 
          ('131', 'LS', 'LESOTHO', '莱索托', NULL, NULL, NULL), 
          ('132', 'LT', 'LITHUANIA', '立陶宛', NULL, NULL, NULL), 
          ('133', 'LU', 'LUXEMBOURG', '卢森堡', NULL, NULL, NULL), 
          ('134', 'LV', 'LATVIA', '拉脱维亚', NULL, NULL, NULL), 
          ('135', 'LY', 'LIBYA', '利比亚', NULL, NULL, NULL), 
          ('136', 'MA', 'MOROCCO', '摩洛哥', NULL, NULL, NULL), 
          ('137', 'MC', 'MONACO', '摩纳哥', NULL, NULL, NULL), 
          ('138', 'MD', 'MOLDOVA', '摩尔多瓦', NULL, NULL, NULL), 
          ('139', 'ME', 'MONTENEGRO', '黑山共和国', NULL, NULL, NULL), 
          ('140', 'MG', 'MADAGASCAR', '马达加斯加', NULL, NULL, NULL), 
          ('141', 'MH', 'MARSHALL ISLANDS', '马绍尔群岛', NULL, NULL, NULL), 
          ('142', 'MK', 'MACEDONIA', '马其顿', NULL, NULL, NULL), 
          ('143', 'ML', 'MALI', '马里', NULL, NULL, NULL), 
          ('144', 'MM', 'MYANMAR', '缅甸', NULL, NULL, NULL), 
          ('145', 'MN', 'MONGOLIA', '蒙古', NULL, NULL, NULL), 
          ('146', 'MO', 'MACAU', '澳门', NULL, NULL, NULL), 
          ('147', 'MP', 'SAIPAN', '塞班岛', NULL, NULL, NULL), 
          ('148', 'MQ', 'MARTINIQUE', '马提尼克岛', NULL, NULL, NULL), 
          ('149', 'MR', 'MAURITANIA', '毛里塔尼亚', NULL, NULL, NULL), 
          ('150', 'MS', 'MONTSERRAT', '蒙特塞拉岛', NULL, NULL, NULL), 
          ('151', 'MT', 'MALTA', '马尔他', NULL, NULL, NULL), 
          ('152', 'MU', 'MAURITIUS', '毛里求斯', NULL, NULL, NULL), 
          ('153', 'MV', 'MALDIVES', '马尔代夫', NULL, NULL, NULL), 
          ('154', 'MW', 'MALAWI', '马拉维', NULL, NULL, NULL), 
          ('155', 'MX', 'MEXICO', '墨西哥', NULL, NULL, NULL), 
          ('156', 'MY', 'MALAYSIA', '马来西亚', NULL, NULL, NULL), 
          ('157', 'MZ', 'MOZAMBIQUE', '莫桑比克', NULL, NULL, NULL), 
          ('158', 'NA', 'NAMIBIA', '纳米比亚', NULL, NULL, NULL), 
          ('159', 'NC', 'NEW CALEDONIA', '新喀里多尼亚', NULL, NULL, NULL), 
          ('160', 'NE', 'NIGER', '尼日尔', NULL, NULL, NULL), 
          ('161', 'NF', 'NORFOLK ISLAND', '诺褔克岛', NULL, NULL, NULL), 
          ('162', 'NG', 'NIGERIA', '尼日利亚', NULL, NULL, NULL), 
          ('163', 'NI', 'NICARAGUA', '尼加拉瓜', NULL, NULL, NULL), 
          ('164', 'NL', 'NETHERLANDS', '荷兰', NULL, NULL, NULL), 
          ('165', 'NO', 'NORWAY', '挪威', NULL, NULL, NULL), 
          ('166', 'NP', 'NEPAL', '尼泊尔', NULL, NULL, NULL), 
          ('167', 'NR', 'NAURU REPUBLIC', '瑙鲁共和国', NULL, NULL, NULL), 
          ('168', 'NU', 'NIUE', '纽埃岛', NULL, NULL, NULL), 
          ('169', 'NZ', 'NEW ZEALAND', '新西兰', NULL, NULL, NULL), 
          ('170', 'OM', 'OMAN', '阿曼', NULL, NULL, NULL), 
          ('171', 'PA', 'PANAMA', '巴拿马', NULL, NULL, NULL), 
          ('172', 'PE', 'PERU', '秘鲁', NULL, NULL, NULL), 
          ('173', 'PF', 'FRENCH POLYNESIA', '塔希堤岛(法属波利尼西亚)', NULL, NULL, NULL), 
          ('174', 'PG', 'PAPUA NEW GUINEA', '巴布亚新几内亚', NULL, NULL, NULL), 
          ('175', 'PH', 'PHILIPPINES', '菲律宾', NULL, NULL, NULL), 
          ('176', 'PK', 'PAKISTAN', '巴基斯坦', NULL, NULL, NULL), 
          ('177', 'PL', 'POLAND', '波兰', NULL, NULL, NULL), 
          ('178', 'PM', 'SAINT PIERRE AND MIQUELON', '圣皮埃尔和密克隆群岛', NULL, NULL, NULL), 
          ('179', 'PN', 'PITCAIRN ISLANDS', '皮特凯恩群岛', NULL, NULL, NULL), 
          ('180', 'PR', 'PUERTO RICO', '波多黎各', NULL, NULL, NULL), 
          ('181', 'PT', 'PORTUGAL', '葡萄牙', NULL, NULL, NULL), 
          ('182', 'PW', 'PALAU', '帕劳', NULL, NULL, NULL), 
          ('183', 'PY', 'PARAGUAY', '巴拉圭', NULL, NULL, NULL), 
          ('184', 'QA', 'QATAR', '卡塔尔', NULL, NULL, NULL), 
          ('185', 'RE', 'REUNION ISLAND', '留尼汪岛', NULL, NULL, NULL), 
          ('186', 'RO', 'ROMANIA', '罗马尼亚', NULL, NULL, NULL), 
          ('187', 'RS', 'SERBIA, REPUBLIC OF', '塞尔维亚共和国', NULL, NULL, NULL), 
          ('188', 'RU', 'RUSSIA', '俄罗斯', NULL, NULL, NULL), 
          ('189', 'RW', 'RWANDA', '卢旺达', NULL, NULL, NULL), 
          ('190', 'SA', 'SAUDI ARABIA', '沙特阿拉伯', NULL, NULL, NULL), 
          ('191', 'SB', 'SOLOMON ISLANDS', '所罗门群岛', NULL, NULL, NULL), 
          ('192', 'SC', 'SEYCHELLES', '塞舌尔', NULL, NULL, NULL), 
          ('193', 'SD', 'SUDAN', '苏丹', NULL, NULL, NULL), 
          ('194', 'SE', 'SWEDEN', '瑞典', NULL, NULL, NULL), 
          ('195', 'SG', 'SINGAPORE', '新加坡', NULL, NULL, NULL), 
          ('196', 'SH', 'ST HELENA', '圣赫勒拿岛', NULL, NULL, NULL), 
          ('197', 'SI', 'SLOVENIA', '斯洛文尼亚', NULL, NULL, NULL), 
          ('198', 'SJ', 'SVALBARD AND JAN MAYEN', '斯瓦尔巴岛和扬马延岛', NULL, NULL, NULL), 
          ('199', 'SK', 'SLOVAKIA REPUBLIC', '斯洛伐克共和国', NULL, NULL, NULL), 
          ('200', 'SL', 'SIERRA LEONE', '塞拉里昂', NULL, NULL, NULL), 
          ('201', 'SM', 'SAN MARINO', '圣马力诺', NULL, NULL, NULL), 
          ('202', 'SN', 'SENEGAL', '塞内加尔', NULL, NULL, NULL), 
          ('203', 'SO', 'SOMALIA', '索马里', NULL, NULL, NULL), 
          ('204', 'SR', 'SURINAME', '苏里南', NULL, NULL, NULL), 
          ('205', 'SS', 'SOUTH SUDAN', '南苏丹共和国', NULL, NULL, NULL), 
          ('206', 'ST', 'SAO TOME AND PRINCIPE', '圣多美和普林西比', NULL, NULL, NULL), 
          ('207', 'SV', 'EL SALVADOR', '萨尔瓦多', NULL, NULL, NULL), 
          ('208', 'SY', 'SYRIA', '叙利亚', NULL, NULL, NULL), 
          ('209', 'SZ', 'SWAZILAND', '斯威士兰', NULL, NULL, NULL), 
          ('210', 'TA', 'TRISTAN DA CUNBA', '特里斯坦', NULL, NULL, NULL), 
          ('211', 'TC', 'TURKS AND CAICOS ISLANDS', '特克斯和凯科斯群岛', NULL, NULL, NULL), 
          ('212', 'TD', 'CHAD', '乍得', NULL, NULL, NULL), 
          ('213', 'TF', 'FRENCH SOUTHERN TERRITORIES', '法属南部领土', NULL, NULL, NULL), 
          ('214', 'TG', 'TOGO', '多哥', NULL, NULL, NULL), 
          ('215', 'TH', 'THAILAND', '泰国', NULL, NULL, NULL), 
          ('216', 'TJ', 'TAJIKISTAN', '塔吉克斯坦', NULL, NULL, NULL), 
          ('217', 'TK', 'TOKELAU', '托克劳', NULL, NULL, NULL), 
          ('218', 'TL', 'EAST TIMOR', '东帝汶', NULL, NULL, NULL), 
          ('219', 'TM', 'TURKMENISTAN', '土库曼斯坦', NULL, NULL, NULL), 
          ('220', 'TN', 'TUNISIA', '突尼斯', NULL, NULL, NULL), 
          ('221', 'TO', 'TONGA', '汤加', NULL, NULL, NULL), 
          ('222', 'TR', 'TURKEY', '土耳其', NULL, NULL, NULL), 
          ('223', 'TT', 'TRINIDAD AND TOBAGO', '特立尼达和多巴哥', NULL, NULL, NULL), 
          ('224', 'TV', 'TUVALU', '图瓦卢', NULL, NULL, NULL), 
          ('225', 'TW', 'TAIWAN', '台湾', NULL, NULL, NULL), 
          ('226', 'TZ', 'TANZANIA', '坦桑尼亚', NULL, NULL, NULL), 
          ('227', 'UA', 'UKRAINE', '乌克兰', NULL, NULL, NULL), 
          ('228', 'UG', 'UGANDA', '乌干达', NULL, NULL, NULL), 
          ('229', 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', '美国本土外小岛屿', NULL, NULL, NULL), 
          ('230', 'US', 'UNITED STATES', '美国', NULL, NULL, NULL), 
          ('231', 'UY', 'URUGUAY', '乌拉圭', NULL, NULL, NULL), 
          ('232', 'UZ', 'UZBEKISTAN', '乌兹别克斯坦', NULL, NULL, NULL), 
          ('233', 'VA', 'VATICAN CITY', '梵蒂冈', NULL, NULL, NULL), 
          ('234', 'VC', 'SAINT VINCENT AND THE GRENADINES', '圣文森特和格林纳丁斯岛', NULL, NULL, NULL), 
          ('235', 'VE', 'VENEZUELA', '委内瑞拉', NULL, NULL, NULL), 
          ('236', 'VG', 'VIRGIN ISLAND (GB)', '英属维尔京群岛', NULL, NULL, NULL), 
          ('237', 'VI', 'VIRGIN ISLAND (US)', '美属维尔京群岛', NULL, NULL, NULL), 
          ('238', 'VN', 'VIETNAM', '越南', NULL, NULL, NULL), 
          ('239', 'VU', 'VANUATU', '瓦努阿图', NULL, NULL, NULL), 
          ('240', 'WF', 'WALLIS AND FUTUNA ISLANDS', '瓦利斯群岛和富图纳群岛', NULL, NULL, NULL), 
          ('241', 'WS', 'WESTERN SAMOA', '西萨摩亚', NULL, NULL, NULL), 
          ('242', 'XB', 'BONAIRE', '伯奈尔岛', NULL, NULL, NULL), 
          ('243', 'XC', 'CURACAO', '库拉索岛(荷兰)', NULL, NULL, NULL), 
          ('244', 'XD', 'ASCENSION', '阿森松', NULL, NULL, NULL), 
          ('245', 'XE', 'ST. EUSTATIUS', '圣尤斯塔提马斯岛', NULL, NULL, NULL), 
          ('246', 'XG', 'SPANISH TERRITORIES OF N.AFRICA', '北非西班牙属土', NULL, NULL, NULL), 
          ('247', 'XH', 'AZORES', '亚速尔群岛', NULL, NULL, NULL), 
          ('248', 'XI', 'MADEIRA', '马德拉岛', NULL, NULL, NULL), 
          ('249', 'XJ', 'BALEARIC ISLANDS', '巴利阿里群岛', NULL, NULL, NULL), 
          ('250', 'XK', 'CAROLINE ISLANDS', '加罗林群岛', NULL, NULL, NULL), 
          ('251', 'XM', 'ST. MAARTEN', '圣马腾岛', NULL, NULL, NULL), 
          ('252', 'XN', 'NEVIS', '尼维斯岛', NULL, NULL, NULL), 
          ('253', 'XS', 'SOMALILAND', '索马里共和国', NULL, NULL, NULL), 
          ('254', 'XY', 'ST. BARTHELEMY', '圣巴特勒米岛', NULL, NULL, NULL), 
          ('255', 'YE', 'YEMEN, REPUBLIC OF', '也门阿拉伯共合国', NULL, NULL, NULL), 
          ('256', 'YT', 'MAYOTTE', '马约特', NULL, NULL, NULL), 
          ('257', 'ZA', 'SOUTH AFRICA', '南非', NULL, NULL, NULL), 
          ('258', 'ZM', 'ZAMBIA', '赞比亚', NULL, NULL, NULL), 
          ('259', 'ZR', 'ZAIRE', '扎伊尔', NULL, NULL, NULL), 
          ('260', 'ZW', 'ZIMBABWE', '津巴布韦', NULL, NULL, NULL) 
          ");

        $db->Execute('DROP TABLE IF EXISTS ' . TABLE_DSF_PRODUCT_CODE);
        $db->Execute(
          'CREATE TABLE ' . TABLE_DSF_PRODUCT_CODE . ' ( ' .
            'product_code varchar(2) NOT NULL default \'\', ' .
            'product_name varchar(100) NOT NULL default \'\', ' .
            'product_cn_name varchar(100) NOT NULL default \'\', ' .
            'PRIMARY KEY (product_code) ' .
          ') CHARACTER SET utf8 COLLATE utf8_general_ci'
        );

        $db->Execute("INSERT INTO " . TABLE_DSF_PRODUCT_CODE . " (product_code, product_name, product_cn_name) VALUES 
          ('S1', 'CNEMSWW', '中国EMS外围'), 
          ('S2', 'CNPTGHHD', '中国小包挂号华东外围'), 
          ('D6', 'CNFDIP', '大陆联邦快递优先型服务IP'), 
          ('D7', 'CNFDIE', '大陆联邦快递经济型服务IE'), 
          ('C7', 'OCEAN SHIPPING', '国际海运出口'), 
          ('H3', '4PXABKR', '4PX香港件'), 
          ('H4', 'TNT', '香港TNT出口'), 
          ('D4', 'SPFEDIEDC', 'DC香港联邦IE'), 
          ('D3', 'SPFEDIPDC', 'DC香港联邦IP'), 
          ('D1', 'UPS HK', 'UPS特惠'), 
          ('D2', 'TNT HK', '香港TNT特惠'), 
          ('E2', 'LYT GSS', '联邮通空邮包裹服务'), 
          ('D5', 'UPS Export HK', '香港UPS'), 
          ('A1', 'HK DHL', 'DHL出口'), 
          ('A2', '4PX', '4PX标准'), 
          ('A4', '4PX ARMX', '4PX专线ARMX'), 
          ('A6', 'LYT GH', '4PX联邮通挂号'), 
          ('A7', 'LYT PY', '4PX联邮通平邮'), 
          ('B1', 'Singapo SPack IMAIR', '新加坡小包挂号'), 
          ('B2', 'Singapore PY', '新加坡小包平邮'), 
          ('B3', 'IINTL IMAIR', '香港小包挂号'), 
          ('B4', 'INTL PY', '香港小包平邮'), 
          ('B9', 'SHANGH IM', '中国邮政小包(上海)'), 
          ('C1', 'CHINA EMS', '中国EMS国际'), 
          ('C2', 'HK EMS', '香港邮政EMS'), 
          ('C3', 'SG EMS', '新加坡EMS'), 
          ('C4', 'HK AIR POST', '香港空邮包裹'), 
          ('C5', 'HK GSS', '香港邮政美国专线'), 
          ('R1', 'AIR－FRE', '订单宝普货空运'), 
          ('R2', 'OCEAN－FRE', '订单宝海运'), 
          ('R3', 'OST', '海外仓储中转'), 
          ('E9', 'BJXBGH', '北京小包挂号'), 
          ('F5', 'SGGHTH', '新加坡小包挂号特惠'), 
          ('C8', 'DHL Small package preferential', 'DHL小包裹特惠'), 
          ('E4', 'HKFEDIP', '香港联邦IP'), 
          ('E5', 'HKFEDIE', '香港联邦IE'), 
          ('F3', 'HNXBPY', '华南小包平邮'), 
          ('F9', 'HQFEDIE', '联邦欧美小包裹IE优惠'), 
          ('F4', 'HNXBGH', '华南小包挂号'), 
          ('E8', 'BJXBPY', '北京小包平邮'), 
          ('F8', 'HQFEDIP', '联邦欧美速递袋IP优惠')
          ");

      // 启用模块
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_1_1 . "', 'MODULE_SHIPPING_DSF_STATUS', 'True', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_1_2 . "', '6', '1', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      // 税率种类
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_2_1 . "', 'MODULE_SHIPPING_DSF_TAX_CLASS', '0', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_2_2 . "', '6', '2', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
      // 税率基准
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_3_1 . "', 'MODULE_SHIPPING_DSF_TAX_BASIS', 'Shipping', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_3_2 . "', '6', '3', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\', \'Store\'), ', now())");
      // 排序顺序
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_4_1 . "', 'MODULE_SHIPPING_DSF_SORT_ORDER', '0', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_4_2 . "', '6', '4', now())");
      // 发货的国家或地区
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_5_1 . "', 'MODULE_SHIPPING_DSF_SKIPPED', '', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_5_2 . "', '6', '5', 'zen_cfg_textarea(', now())");
      // 折扣
      // $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_6_1 . "', 'MODULE_SHIPPING_DSF_DISCOUNT', '100%', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_6_2 . "', '6', '6', now())");
      // 授权 Token
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_7_1 . "', 'MODULE_SHIPPING_DSF_AUTHTOKEN', 'CD29AD1E6703C0DB57271CA42B87A7D9', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_7_2 . "', '6', '7', now())");
      // 接口模式
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_10_1 . "', 'MODULE_SHIPPING_DSF_MODE', 'Demo', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_10_2 . "', '6', '10', 'zen_cfg_select_option(array(\'Demo\', \'Live\'), ', now())");
      // 代理功能
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_11_1 . "', 'MODULE_SHIPPING_DSF_PROXY_REQUIRED', 'False', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_11_2 . "', '6', '11', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
      // 代理参数
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_12_1 . "', 'MODULE_SHIPPING_DSF_PROXY_SERVER', '114.113.147.143', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_12_2 . "', '6', '12', '', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_13_1 . "', 'MODULE_SHIPPING_DSF_PROXY_PORT', '3128', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_13_2 . "', '6', '13', '', now())");

      // 起运地
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_8_1 . "', 'MODULE_SHIPPING_DSF_START_ID', '10', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_8_2 . "', '6', '9', 'zen_get_start_id_name', 'zen_cfg_pull_down_start_id(', now())");
      // 货物类型
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_9_1 . "', 'MODULE_SHIPPING_DSF_CARGO_CODE', 'P', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_9_2 . "', '6', '10', 'zen_cfg_select_option(array(\'P\', \'D\'), ', now())");

      // 启用配送方式
      $dsf_product_code_query = $db->Execute("SELECT product_code, product_name, product_cn_name FROM " . TABLE_DSF_PRODUCT_CODE);

      $s_order = 20;
      while (!$dsf_product_code_query->EOF) {
        // 详细的配送方式
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_SHIPPING_DSF_TEXT_CONFIG_20_1 . "{$dsf_product_code_query->fields['product_cn_name']}', 'MODULE_SHIPPING_DSF_PRODUCT_CODE_{$dsf_product_code_query->fields['product_code']}', 'False', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_20_2 . "{$dsf_product_code_query->fields['product_name']}', '6', '{$s_order}', 'zen_cfg_select_option(array(\'False\', \'True\'), ', now())");
        // 折扣
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('{$dsf_product_code_query->fields['product_cn_name']}" . MODULE_SHIPPING_DSF_TEXT_CONFIG_21_1 . "', 'MODULE_SHIPPING_DSF_DISCOUNT_{$dsf_product_code_query->fields['product_code']}', '100%', '{$dsf_product_code_query->fields['product_cn_name']}" . MODULE_SHIPPING_DSF_TEXT_CONFIG_21_2 . "', '6', '{$s_order}', now())");
        // 自定义名称
        $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('{$dsf_product_code_query->fields['product_cn_name']}" . MODULE_SHIPPING_DSF_TEXT_CONFIG_22_1 . "', 'MODULE_SHIPPING_DSF_NAME_{$dsf_product_code_query->fields['product_code']}', '{$dsf_product_code_query->fields['product_name']}::{$dsf_product_code_query->fields['product_cn_name']}', '" . MODULE_SHIPPING_DSF_TEXT_CONFIG_22_2 . "{$dsf_product_code_query->fields['product_name']}::{$dsf_product_code_query->fields['product_cn_name']}', '6', '{$s_order}', now())");
        $s_order ++;
        $dsf_product_code_query->MoveNext();
      }

    }

    function remove() {
      global $db;

      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
      $db->Execute('DROP TABLE IF EXISTS ' . TABLE_DSF_COUNTRY);
      $db->Execute('DROP TABLE IF EXISTS ' . TABLE_DSF_PRODUCT_CODE);

    }

    function keys() {
      $keys = array('MODULE_SHIPPING_DSF_STATUS', 'MODULE_SHIPPING_DSF_TAX_CLASS', 'MODULE_SHIPPING_DSF_TAX_BASIS', 'MODULE_SHIPPING_DSF_SORT_ORDER', 'MODULE_SHIPPING_DSF_SKIPPED', 'MODULE_SHIPPING_DSF_AUTHTOKEN', 'MODULE_SHIPPING_DSF_MODE', 'MODULE_SHIPPING_DSF_PROXY_REQUIRED', 'MODULE_SHIPPING_DSF_PROXY_SERVER', 'MODULE_SHIPPING_DSF_PROXY_PORT', 'MODULE_SHIPPING_DSF_START_ID', 'MODULE_SHIPPING_DSF_CARGO_CODE');

      $query = "SELECT configuration_key FROM " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE_SHIPPING_DSF_PRODUCT_CODE_%' or configuration_key like 'MODULE_SHIPPING_DSF_DISCOUNT_%' or configuration_key like 'MODULE_SHIPPING_DSF_NAME_%' ORDER BY sort_order, configuration_key DESC";
      $result = mysql_query($query);
      // sort ($result, SORT_NUMERIC); 

      while ( $row = mysql_fetch_array($result, MYSQL_NUM) ) {
        $keys[] = $row[0];
      } 
      return $keys;
    }
  }

    /**
    *获得递四方国家ID 
    *function :get_4px_country_id()
    *参数说明：(zen-cart国家ID，zen-cart国家代码可不填);
    *返回递四方国家ID
    */
    function get_4px_country_code($zen_country_id, $zen_code=null) {
    	global $db;
    	 $sql_zen = "SELECT countries_id, countries_iso_code_2 FROM " . TABLE_COUNTRIES . " WHERE countries_id='" . $zen_country_id . "'";
    	 $result_zen = $db->Execute($sql_zen);
    	 $zen_code = $result_zen->fields['countries_iso_code_2'];
    	 // if($zen_code){
    	 //   $sql_statement  =  "SELECT countries_id FROM " . TABLE_DSF_COUNTRY . " WHERE country_code='" . $zen_code . "'";
    	 //   $get_result     =  $db->Execute($sql_statement);
    	 // }
       // return $get_result->fields['countries_id'];
       return $zen_code;
    }

  function zen_get_start_id_name($start_id) {
    global $db;

      switch ($start_id) {
        case '10':
            $start_id_name = '深圳一部';
            break;
        case '13':
            $start_id_name = '深圳钟屋';
            break;
        case '22':
            $start_id_name = '广州白云';
            break;
        case '30':
            $start_id_name = '汕头';
            break;
        case '32':
            $start_id_name = '上海虹桥';
            break;
        case '44':
            $start_id_name = '北京';
            break;
        case '47':
            $start_id_name = '大连';
            break;
        case '48':
            $start_id_name = '青岛';
            break;
        case '49':
            $start_id_name = '济南';
            break;
        case '50':
            $start_id_name = '烟台';
            break;
        case '52':
            $start_id_name = '厦门';
            break;
        case '53':
            $start_id_name = '福州';
            break;
        case '54':
            $start_id_name = '泉州';
            break;
        case '55':
            $start_id_name = '莆田';
            break;
        case '57':
            $start_id_name = '香港一部';
            break;
        case '58':
            $start_id_name = '香港二部';
            break;
        case '59':
            $start_id_name = '英国仓储';
            break;
        case '60':
            $start_id_name = '澳洲仓储';
            break;
        case '61':
            $start_id_name = '美国仓储';
            break;
        case '62':
            $start_id_name = '德国仓储';
            break;
        case '63':
            $start_id_name = 'VPOST';
            break;
      }

      return $start_id_name;
  }

  function zen_cfg_pull_down_start_id($start_id, $key = '') {
    global $db;
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $start_id_array = array(
                         array('id' => '10', 'text' => '深圳一部'),
                         array('id' => '13', 'text' => '深圳钟屋'),
                         array('id' => '22', 'text' => '广州白云'),
                         array('id' => '30', 'text' => '汕头'),
                         array('id' => '32', 'text' => '上海虹桥'),
                         array('id' => '44', 'text' => '北京'),
                         array('id' => '47', 'text' => '大连'),
                         array('id' => '48', 'text' => '青岛'),
                         array('id' => '49', 'text' => '济南'),
                         array('id' => '50', 'text' => '烟台'),
                         array('id' => '52', 'text' => '厦门'),
                         array('id' => '53', 'text' => '福州'),
                         array('id' => '54', 'text' => '泉州'),
                         array('id' => '55', 'text' => '莆田'),
                         array('id' => '57', 'text' => '香港一部'),
                         array('id' => '58', 'text' => '香港二部'),
                         array('id' => '59', 'text' => '英国仓储'),
                         array('id' => '60', 'text' => '澳洲仓储'),
                         array('id' => '61', 'text' => '美国仓储'),
                         array('id' => '62', 'text' => '德国仓储'),
                         array('id' => '63', 'text' => 'VPOST'),
                       );

    return zen_draw_pull_down_menu($name, $start_id_array, $start_id);
  }

?>
