 <div class="centerBoxWrapper" id="similar_product">
<h2 class="centerBoxHeading"><?php echo SIMILAR_PRODUCT;?></h2>

<ul>
<?php

$products_id = (int)$_GET['products_id'];

    $flash_page_id_con = array();
    $flash_page_images_con = array();
    $flash_page_price_con = array();
    $flash_page_name_con = array();

    $flash_page_query = "select p.products_id,p.products_image,pd.products_name from " . TABLE_PRODUCTS ." p, ". TABLE_PRODUCTS_DESCRIPTION . " pd where p.`products_id`=pd.`products_id` AND pd.`language_id` = '" . (int)$_SESSION['languages_id'] . "' AND p.`master_categories_id` = " . zen_get_products_category_id($products_id) . " AND p.`products_status` ='1' ORDER BY rand() limit 3";
    $flash_page = $db->Execute($flash_page_query);
    while(!$flash_page->EOF){
        $flash_page_items[] = $flash_page->fields;
        $flash_page_id_con[]    = $flash_page->fields['products_id'];
        $flash_page_images_src = is_int(strpos($flash_page->fields['products_image'],','))? substr($flash_page->fields['products_image'],0,strpos($flash_page->fields['products_image'],',')):$flash_page->fields['products_image'];
        $flash_page_images_con[]    = '"'.(zen_not_null($flash_page->fields['products_image']) ? $flash_page_images_src : PRODUCTS_IMAGE_NO_IMAGE ).'"';
        $flash_page_price_con[]    = '"'.$currencies->display_price(zen_get_products_base_price($flash_page->fields['products_id']),zen_get_tax_rate($product_check->fields['products_tax_class_id'])).'"';
        $flash_page_name_con[]    = '"'.zen_output_string(zen_get_products_name($flash_page->fields['products_id'])).'"';
        $flash_page->MoveNext();
    }
  
    $flash_page_id = implode(",", $flash_page_id_con);
    $flash_page_images = implode(",", $flash_page_images_con);
    $flash_page_price = implode(",", $flash_page_price_con);
    $flash_page_name = implode(",", $flash_page_name_con);
    $flash_page_display_num    = $flash_page->RecordCount();
?>

<?php for($i = 0; $i< $flash_page_display_num ; $i++){?>
<li id="li<?php echo $i;?>" style="display:block;float:left;padding:5px 15px; width:27%;">
<a href="<?php echo zen_href_link(zen_get_info_page($flash_page_items[$i]['products_id']), 'products_id=' . $flash_page_items[$i]['products_id']);?>"><?php echo zen_image(DIR_WS_IMAGES . $flash_page_items[$i]['products_image'], $flash_page_items[$i]['products_name'], IMAGE_FEATURED_PRODUCTS_LISTING_WIDTH, IMAGE_FEATURED_PRODUCTS_LISTING_HEIGHT); ?></a><p style="text-align:center;"><a href="<?php echo zen_href_link(zen_get_info_page($flash_page_items[$i]['products_id']), 'products_id=' . $flash_page_items[$i]['products_id']);?>"><?php echo $flash_page_items[$i]['products_name']; ?></a><br /><strong id="cell_price<?php echo $i?>" class="red"><?php echo $currencies->display_price((zen_get_products_base_price($flash_page_items[$i]['products_id']) == 0 ? zen_get_products_sample_price($flash_page_items[$i]['products_id']): zen_get_products_base_price($flash_page_items[$i]['products_id'])),zen_get_tax_rate($product_check->fields['products_tax_class_id'])); ?></strong></p>
</li>
<?php
//print_r($flash_page_items[$i]);
}
?>
</ul>

</div>