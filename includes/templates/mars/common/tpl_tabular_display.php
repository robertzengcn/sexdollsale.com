<?php
/**
 * Common Template - tpl_tabular_display.php
 *
 * This file is used for generating tabular output where needed, based on the supplied array of table-cell contents.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_tabular_display.php 3957 2006-07-13 07:27:06Z drbyte $
 */

//print_r($list_box_contents);
  $cell_scope = (!isset($cell_scope) || empty($cell_scope)) ? 'col' : $cell_scope;
  $cell_title = (!isset($cell_title) || empty($cell_title)) ? 'list' : $cell_title;

?>


<ul id="<?php echo 'cat' . $cPath . 'Table'; ?>" class="productListingList">
  <?php
    for($row=0; $row<sizeof($list_box_contents); $row++) {
      $r_params = "";
      $c_params = "";              
      if (isset($list_box_contents[$row]['params'])){
        $list_item_wrapper_class = str_replace('class="', 'class="section group ', $list_box_contents[$row]['params']);
        $r_params .= ' ' . $list_item_wrapper_class;
      }      
  ?>  
  <li <?php echo $r_params; ?>>
<?php
    $col_small = 'col_1_of_4';
    $col_big = 'col_2_of_4';

    $array_size = sizeof($list_box_contents[$row])-1;
    switch ($array_size) {
      case 1:
          $col_small = 'col_12_of_12';
          $col_big = 'col_12_of_12';
        break;
      case 2:
          $col_small = 'col_1_of_3';
          $col_big = 'col_2_of_3';
        break;
      case 3:
          $col_small = 'col_1_of_4';
          $col_big = 'col_2_of_4';
        break;  
      case 4:
          $col_small = 'col_1_of_6';
          $col_big = 'col_3_of_6';
        break;  
      case 5:
          $col_small = 'col_1_of_7';
          $col_big = 'col_3_of_7';
        break; 
      case 6:
          $col_small = 'col_1_of_8';
          $col_big = 'col_3_of_8';
        break;
      case 7:
          $col_small = 'col_1_of_9';
          $col_big = 'col_3_of_9';
        break;
          
      default:
          $col_small = 'col_1_of_4';
          $col_big = 'col_2_of_4';
        break;
    }

    for($col=0;$col<sizeof($list_box_contents[$row]);$col++) {      
      $c_params = "";
      //$cell_type = ($row==0) ? 'th' : 'td';      
      if (isset($list_box_contents[$row][$col]['params'])){
        $col_class = ($col==1) ? $col_big : $col_small;
        $list_item_class = str_replace('class="', 'class="col '.$col_class.' ', $list_box_contents[$row][$col]['params']);
        $c_params .= ' ' . $list_item_class;
      }    
      //if (isset($list_box_contents[$row][$col]['align']) && $list_box_contents[$row][$col]['align'] != '') $c_params .= ' align="' . $list_box_contents[$row][$col]['align'] . '"';
      //if ($cell_type=='th') $c_params .= ' scope="' . $cell_scope . '" id="' . $cell_title . 'Cell' . $row . '-' . $col.'"';
      if (isset($list_box_contents[$row][$col]['text'])) {
?>
   <?php echo '<div'. $c_params . '>'; ?><?php echo $list_box_contents[$row][$col]['text'] ?><?php echo '</div>' . "\n"; ?>
<?php
      }
    }
?>

  </li>  
  <?php
    }
  ?> 
</ul>