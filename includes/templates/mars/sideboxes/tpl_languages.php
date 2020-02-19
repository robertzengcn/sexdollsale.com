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
  $content = "";
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent centeredContent">';

  $lng_cnt = 0;
  $content .= "<ul>";  
  while (list($key, $value) = each($lng->catalog_languages)) {
    $content .= '<li><a '. checkCurrentSideboxLanguage($key) .' href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . zen_image(DIR_WS_LANGUAGES .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a></li>';
    $lng_cnt ++;
    if ($lng_cnt >= MAX_LANGUAGE_FLAGS_COLUMNS) {
      $lng_cnt = 0;      
    }
  }
  $content .= "</ul>";
$content .= '</div>';

function checkCurrentSideboxLanguage($language_code){    
  if($language_code == $_SESSION['languages_code']){
    return ' class="akt" ';
  } 
}

?>