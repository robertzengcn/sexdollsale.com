<?php
/**
 *scrolling recent-orders sidebox definitions - text for inclusion in a new blank sidebox
 *
 * @package templateSystem
 * @copyright 2010 dannysun
 * @copyright Portions Copyright 2003-2007 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: recent_orders_sidebox.php 2007-05-26 kuroi $
 * @email prettywish@126.com 
 * @QQ 305962848
 */
?>

<?php
  $content = '';
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent recentOrders">';  
  $content .='<div id="shangfan" style="overflow:hidden;height:200px;"><div id="holder1">';  
  for($i=0;$i<$count;$i++)
  {
  	$content .='<div id="recentOrderItemWrapper">';
  	$content .='	
				<div id="boxRecentOrdersImg">
					<div align="center"><a href="' . zen_href_link(zen_get_info_page($recent_orders_product_id[$i]), 'products_id=' . $recent_orders_product_id[$i]) . '">'
						.zen_image(DIR_WS_IMAGES . $recent_orders_product_image[$i], $recent_orders_product_name[$i], SMALL_IMAGE_WIDTH+1, SMALL_IMAGE_HEIGHT+1)
					.'</a></div>'
				.'</div>'	
			 .'<div id="recentOrdersName">'
			 .'<a href="' . zen_href_link(zen_get_info_page($recent_orders_product_id[$i]), 'cPath=' . $productsInCategory[$recent_orders_product_id[$i]] . '&products_id=' . $recent_orders_product_id[$i]) . '">'			 
			 . $recent_orders_product_name[$i]			 
			 . '</a>'
			 . '</div>';
  	$content .= 'ship to'. ' ' . $recent_orders_delivery_state[$i]. ', ' . $recent_orders_delivery_country[$i];
	$content .='</div></p>';//end recentOrderItemWrapper
  }
  $content .='</div><div id="holder2"></div></div>';// end shangfan
  $content .='</div>';
  $content .='<script>
var speed=40
var demo=document.getElementById("shangfan");
var demo2=document.getElementById("holder2");
var demo1=document.getElementById("holder1");
demo2.innerHTML=demo1.innerHTML
function Marquee(){
if(demo2.offsetTop-demo.scrollTop<=0)
demo.scrollTop-=demo1.offsetHeight
else{
demo.scrollTop++
}
}
var MyMar=setInterval(Marquee,speed)
demo.onmouseover=function() {clearInterval(MyMar)}
demo.onmouseout=function() {MyMar=setInterval(Marquee,speed)}
</script>';
?>
