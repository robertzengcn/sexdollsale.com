<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_languages.php 2982 2006-02-07 07:56:41Z birdbrain $
 */  

  $sql = "select 
            c_1.configuration_value as layout,
            c_2.configuration_value as display          
          from " . TABLE_CONFIGURATION . "       as c_1
          inner join " . TABLE_CONFIGURATION . " as c_2 ON c_2.configuration_group_id = c_1.configuration_group_id
          where   
                  c_1.configuration_key = 'HEADER_LANGUAGE_LAYOUT_STYLE'
              and c_2.configuration_key = 'HEADER_LANGUAGE_DISPLAY'";

  $language_header_style = $db->Execute($sql); 
  $content  = "";
  if($language_header_style->fields['display'] == 1){

    $isImage = "";
    if($language_header_style->fields['layout'] == 'image'){
      $isImage = " languageImage";
    }

    $content  = '<li class="languageHeader'.$isImage.'">';
    $content .= '<span>'.HEADER_LANGUAGES.' &nbsp;</span>';;  
    $lng_cnt  = 0;  

    if ($language_header_style->RecordCount() != 0) {

      while (list($key, $value) = each($lng->catalog_languages)) {
        switch($language_header_style->fields['layout']){

          case 'text': $content .= '<a '. checkCurrentLanguage($key) .' href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . $key . '</a>&nbsp;&nbsp;'; break;
          //Uncomment the line below if you want to use the original flag icons
          // case 'image': $content .= '<a '. checkCurrentLanguage($key) .' href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . zen_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a>'; break;
          case 'image': $content .= '<a '. checkCurrentLanguage($key) .' href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . zen_image($template->get_template_dir(HEADER_LOGO_IMAGE, DIR_WS_TEMPLATE, $current_page_base,'images'). '/flags/flag_' . $key.'.png', $value['directory']) . '</a>'; break; 
        }

        $lng_cnt ++;
        if ($lng_cnt >= MAX_LANGUAGE_FLAGS_COLUMNS) {
          $lng_cnt = 0;
          $content .= '<br />';
        }
      }

      $content .= '</li>';

    }
  }

  function checkCurrentLanguage($language_code){    
    if($language_code == $_SESSION['languages_code']){
      return ' class="akt" ';
    } 
  }
?>