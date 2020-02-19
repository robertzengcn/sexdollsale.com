<?php if (FACEBOOK_OPEN_GRAPH_STATUS == 'true') { ?>
<meta property="og:title" content="<?php echo META_TAG_TITLE; ?>" />
<meta property="og:site_name" content="<?php echo STORE_NAME; ?>" />
<?php
if (FACEBOOK_OPEN_GRAPH_STORED_URL == 'true' && isset($_GET['products_id'])) {
  $stored_url = $db->Execute("SELECT stored_url FROM " . TABLE_PRODUCTS . " WHERE products_id = " . (int)$_GET['products_id'] . " AND stored_url IS NOT NULL AND stored_url <> '' LIMIT 1;");
  if ($stored_url->RecordCount() > 0) {
    $og_url = $stored_url->fields['stored_url'];
  }
}
if ($og_url == '') {
  if (FACEBOOK_OPEN_GRAPH_CANONICAL == 'true') {
    $og_url = html_entity_decode($canonicalLink); 
  } else {
    $fb_exclude_params = array('action', 'notify', 'main_page', 'zenid');
    if (FACEBOOK_OPEN_GRAPH_CPATH == 'false') {
      $fb_exclude_params[] = 'cPath'; 
    }
    if (FACEBOOK_OPEN_GRAPH_LANGUAGE == 'false') {
      $fb_exclude_params[] = 'language'; 
    }
    $og_url = zen_href_link($_GET['main_page'], zen_get_all_get_params($fb_exclude_params), 'NONSSL', false);    
  }
}
if ( (FACEBOOK_OPEN_GRAPH_STORED_URL == 'true') && isset($_GET['products_id']) && $og_url != '' && is_object($stored_url) && !$stored_url->RecordCount() > 0) {
  // update the database with the og_url
  $db->Execute("UPDATE " . TABLE_PRODUCTS . " SET stored_url = '" . $og_url . "' WHERE products_id = " . (int)$_GET['products_id'] . " LIMIT 1;");
}
?>
<meta property="og:url" content="<?php echo $og_url; ?>" />
<?php if (FACEBOOK_OPEN_GRAPH_ADMINID != '') { ?>
<meta property="fb:admins" content="<?php echo FACEBOOK_OPEN_GRAPH_ADMINID; ?>" />
<?php } ?>
<?php if (FACEBOOK_OPEN_GRAPH_APPID != '') { ?>
<meta property="fb:app_id" content="<?php echo FACEBOOK_OPEN_GRAPH_APPID; ?>" />
<?php } ?>
<?php
  if (isset($_GET['products_id'])) { // use products_image if products_id exists
    $facebook_image = $db->Execute("select p.products_image from " . TABLE_PRODUCTS . " p where products_id='" . (int)$_GET['products_id'] . "'");
    $fb_image = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $facebook_image->fields['products_image'];
  } elseif (isset($_GET['cPath'])) {
    $fb_cPath_array = explode('_', $_GET['cPath']);
    $fb_cPath_size = sizeof($fb_cPath_array);
    $fb_categories_id = $fb_cPath_array[$fb_cPath_size - 1]; 
    $fb_categories_image = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . zen_get_categories_image($fb_categories_id);
  }
  if ($fb_image == '' && FACEBOOK_OPEN_GRAPH_DEFAULT_IMAGE != '') { // if no products image, use the default image if enabled
   $fb_image = FACEBOOK_OPEN_GRAPH_DEFAULT_IMAGE;
  }
  if ($fb_image != '') {
?>
<meta property="og:image" content="<?php echo $fb_image; ?>" />
<?php 
  }
?>
<?php if (FACEBOOK_OPEN_GRAPH_TYPE != '') { ?>
<meta property="og:type" content="<?php echo FACEBOOK_OPEN_GRAPH_TYPE; ?>" />
<?php } ?>
<?php } ?>