<?php
class zen_categories_ul_generator {
  var $root_category_id = 0,
      $max_level = 6,
      $data = array(),
      $root_start_string = '',
      $root_end_string = '',
      $parent_start_string = '',
      $parent_end_string = '',
      $parent_group_start_string = '<ul%s>',
      $parent_group_end_string = '%s</ul>',
      $child_start_string = '%s<li>',
      $child_end_string = '%s</li>',
      $spacer_string = '',
      $spacer_multiplier = 1;
  var $document_types_list = ' (3) ';  // acceptable format example: ' (3, 4, 9, 22, 18) '

  function zen_categories_ul_generator() {
    global $languages_id, $db, $request_type;
    $this->server    = ((ENABLE_SSL == true && $request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER);
    $this->base_href = ((ENABLE_SSL == true && $request_type == 'SSL') ? DIR_WS_HTTPS_CATALOG : DIR_WS_CATALOG);
    $this->data = array();
    $categories_query = "select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd " .
                        "where c.categories_id = cd.categories_id and c.categories_status=1  and cd.language_id = '" . (int)$_SESSION['languages_id']  . "'" .
                        "order by c.parent_id, c.sort_order, cd.categories_name";
    $categories = $db->Execute($categories_query);
    while (!$categories->EOF) {
      $products_in_category = (SHOW_COUNTS == 'true' ? zen_count_products_in_category($categories->fields['categories_id']) : 0);
      $this->data[$categories->fields['parent_id']][$categories->fields['categories_id']] = array('name' => $categories->fields['categories_name'], 'count' => $products_in_category);
      $categories->MoveNext();
    }
    // DEBUG: These lines will dump out the array for display and troubleshooting:
    // foreach ($this->data as $pkey=>$pvalue) { 
    //   foreach ($this->data[$pkey] as $key=>$value) { echo '['.$pkey.']'.$key . '=>' . $value['name'] . '<br>'; }
    // }
  }

  function buildBranch($parent_id, $level = 0, $cpath = '') {
    global $cPath;

    //$result = "\n".sprintf($this->parent_group_start_string, str_repeat(' ', $level*4))."\n";
    $result = sprintf($this->parent_group_start_string, ' class="level'. ($level+1) . '"' );  

    if (isset($this->data[$parent_id])) {
      foreach ($this->data[$parent_id] as $category_id => $category) {
        $result .= sprintf($this->child_start_string, str_repeat(' ', $level*4+2));
        if (isset($this->data[$category_id])) {
          $result .= $this->parent_start_string;
        }
        if ($level == 0) {
          $result .= $this->root_start_string;
          $new_cpath  = $category_id;
        } else {
          $new_cpath = $cpath."_".$category_id;
        }
        if ($cPath == $new_cpath) {
          $result .= '<a href="' . zen_href_link(FILENAME_DEFAULT, 'cPath=' . $new_cpath) . '" class="on">'; // highlight current category & disable link
        } else {
          $result .= '<a href="' . zen_href_link(FILENAME_DEFAULT, 'cPath=' . $new_cpath) . '">';
        }
        $result .= $category['name'];
        if (SHOW_COUNTS == 'true' && ((CATEGORIES_COUNT_ZERO == '1' && $category['count'] == 0) || $category['count'] >= 1)) {
          $result .= '<span class="count">' . CATEGORIES_COUNT_PREFIX . $category['count'] . CATEGORIES_COUNT_SUFFIX.'</span>';
        }        
	      $result .= '</a>';
        if (isset($this->data[$category_id])) {
          $result .= '<span class="toggle">open</span>';
        }
        if ($level == 0) {
          $result .= $this->root_end_string;
        }
        if (isset($this->data[$category_id])) {
          $result .= $this->parent_end_string;
        }
        if (isset($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
          $result .= $this->buildBranch($category_id, $level+1, $new_cpath);
          $result .= sprintf($this->child_end_string, str_repeat(' ', $level*4+2))."\n";
        } else {
          $result .= sprintf($this->child_end_string, '')."\n";
        }
      }
    }
    $result .= sprintf($this->parent_group_end_string, str_repeat(' ', $level*4))."\n";
    
    return $result;
  }

  function buildTree() {
    return $this->buildBranch($this->root_category_id, 0);
  }
}
?>