<?php
/**
 * Side Box Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_shopping_cart.php 7192 2007-10-06 13:30:46Z drbyte $
 */
  $content ="";
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">';
  if ($_SESSION['cart']->count_contents() > 0) {
  $content .= '<div id="cartBoxListWrapper">' . "\n";
    $products = $_SESSION['cart']->get_products();

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {

      $sbProductImage = (IMAGE_SHOPPING_CART_STATUS == 1 ? zen_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : '');
      $sbLinkProductName = zen_href_link(zen_get_info_page($products[$i]['id']), 'products_id=' . $products[$i]['id']);  
      $sbLinkProductDelete = zen_href_link(FILENAME_SHOPPING_CART, 'action=remove_product&product_id=' . $products[$i]['id']);

      if (isset($_SESSION['new_products_id_in_cart']) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {        
        $content.= '<ul class="product cartNewItem">';
      } else {
        $content.= '<ul class="product">';
      }

      $content.= '
          <li class="img"><a href="'.$sbLinkProductName.'">'. $sbProductImage .'</a></li>
          <li class="name"><strong>'. $products[$i]['quantity'] .''. BOX_SHOPPING_CART_DIVIDER .'</strong> <a href="'.$sbLinkProductName.'">'. $products[$i]['name'] .'</a></li>
          <li class="amount">'. $currencies->format($products[$i]['final_price']) .'</li>                                  
          <li class="delete"><a href="'.$sbLinkProductDelete.'">delete</a></li>
        </ul>    
      ';      

      if (isset($_SESSION['new_products_id_in_cart']) && ($_SESSION['new_products_id_in_cart'] == $products[$i]['id'])) {
        $_SESSION['new_products_id_in_cart'] = '';
      }
    }    
    $content .= '</div>';
  } else {
    $content .= '<div id="cartBoxEmpty">' . BOX_SHOPPING_CART_EMPTY . '</div>';
  }

  if ($_SESSION['cart']->count_contents() > 0) {    
    $content .= '<div class="cartBoxTotal">' .HEADER_CART_SUBTOTAL .''. $currencies->format($_SESSION['cart']->show_total()) . '</div>';    
  }  

  if (isset($_SESSION['customer_id'])) {
    $gv_query = "select amount
                 from " . TABLE_COUPON_GV_CUSTOMER . "
                 where customer_id = '" . $_SESSION['customer_id'] . "'";
   $gv_result = $db->Execute($gv_query);

    if ($gv_result->RecordCount() && $gv_result->fields['amount'] > 0 ) {
      $content .= '<div id="cartBoxGVButton"><a href="' . zen_href_link(FILENAME_GV_SEND, '', 'SSL') . '">' . zen_image_button(BUTTON_IMAGE_SEND_A_GIFT_CERT , BUTTON_SEND_A_GIFT_CERT_ALT) . '</a></div>';
      $content .= '<div id="cartBoxVoucherBalance">' . VOUCHER_BALANCE . $currencies->format($gv_result->fields['amount']) . '</div>';
    }
  }

  if ($current_page_base != 'shopping_cart') { 
    $content.= '      
      <ul class="optionsWrapper group">
        <li><a class="btn" href="'.zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL').'">'.HEADER_TITLE_CART_CONTENTS.'</a></li>
        <li><a class="btn" href="'.zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'">'.HEADER_TITLE_CHECKOUT.'</a></li>
      </ul>
    ';
  }
  $content .= '</div>';
?>
