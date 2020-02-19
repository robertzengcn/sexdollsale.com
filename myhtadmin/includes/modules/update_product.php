<?php
/**
 * @package admin
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: update_product.php 18695 2011-05-04 05:24:19Z drbyte $
 */
  if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
  }
  
  function download_image($image)
{
	$ch = curl_init($image);
	if (!$ch) {
		die("Could not init curl.");
	}
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$response = curl_exec($ch);
	if (!$response) {
		die("curl_exec error: ". curl_error($ch));
	}
	// Then, after your curl_exec call:
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	curl_close($ch);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	if (!preg_match("/Content-Type: (.*)\r\n/i", $header, $match)) {
		die("No content type for image: $image.");
	}
	$type = $match[1];
	switch (strtolower($type)) {
	case "image/gif":
		$ext = "gif";
		break;
	case "image/jpg":
	case "image/jpeg":
		$ext = "jpg";
		break;
	case "image/png":
		$ext = "png";
		break;
	case "image/bmp":
		$ext = "bmp";
		break;
	default:
		$ext = "";
		die("Content-type: $type not supported.");
		break;
	}
	$prodrwname = $_POST['products_name'][$_SESSION['languages_id']];
	$rand = mt_rand();
	$new = "/proimages/" . $prodrwname. $rand . "." . $ext;
	$base = $_SERVER["DOCUMENT_ROOT"] . $new;
	$fp = fopen($base, "wb");
	if (!$fp) {
		die("File: $base. Directory not writable.");
	}
	fwrite($fp, $body);
	fclose($fp);
	return $new;
}

/* searches the html for remote images and replaces them with a local copy */
function process_remote_images($products_descriptions)
{
	$base_url = "http://" . $_SERVER["SERVER_NAME"];
	if (preg_match_all("/src=\"(https?:\\/\\/[^\"]+)\"/Ui", $products_descriptions, $arr)) {
		$images = $arr[1];
		foreach ($images as $image) {
			$image1 = html_entity_decode($image);
			if (strcasecmp($base_url, substr($image1, 0, strlen($base_url))) == 0) {
				/* the picture is already on our server */
				continue;
			}
			$new = download_image($image1);
			$new = $base_url . $new;
			$products_descriptions = str_ireplace($image, $new, $products_descriptions);
		}
	}
	return $products_descriptions;
}
  
  if (isset($_GET['pID'])) $products_id = zen_db_prepare_input($_GET['pID']);
  if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
    $action = 'new_product';
  } elseif ($_POST['products_model'] . $_POST['products_url'] . $_POST['products_name'] . $_POST['products_description'] != '') {
    $products_date_available = zen_db_prepare_input($_POST['products_date_available']);

    $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

    // Data-cleaning to prevent MySQL5 data-type mismatch errors:
    $tmp_value = zen_db_prepare_input($_POST['products_quantity']);
    $products_quantity = (!zen_not_null($tmp_value) || $tmp_value=='' || $tmp_value == 0) ? 0 : $tmp_value;
    $tmp_value = zen_db_prepare_input($_POST['products_price']);
    $products_price = (!zen_not_null($tmp_value) || $tmp_value=='' || $tmp_value == 0) ? 0 : $tmp_value;
    $tmp_value = zen_db_prepare_input($_POST['products_weight']);
    $products_weight = (!zen_not_null($tmp_value) || $tmp_value=='' || $tmp_value == 0) ? 0 : $tmp_value;
    $tmp_value = zen_db_prepare_input($_POST['manufacturers_id']);
    $manufacturers_id = (!zen_not_null($tmp_value) || $tmp_value=='' || $tmp_value == 0) ? 0 : $tmp_value;

    $sql_data_array = array('products_quantity' => $products_quantity,
                            'products_type' => zen_db_prepare_input($_GET['product_type']),
                            'products_model' => zen_db_prepare_input($_POST['products_model']),
                            'products_price' => $products_price,
                            'products_date_available' => $products_date_available,
                            'products_weight' => $products_weight,
                            'products_status' => zen_db_prepare_input((int)$_POST['products_status']),
                            'products_virtual' => zen_db_prepare_input((int)$_POST['products_virtual']),
                            'products_tax_class_id' => zen_db_prepare_input((int)$_POST['products_tax_class_id']),
                            'manufacturers_id' => $manufacturers_id,
                            'products_quantity_order_min' => zen_db_prepare_input($_POST['products_quantity_order_min']),
                            'products_quantity_order_units' => zen_db_prepare_input($_POST['products_quantity_order_units']),
                            'products_priced_by_attribute' => zen_db_prepare_input($_POST['products_priced_by_attribute']),
                            'product_is_free' => zen_db_prepare_input((int)$_POST['product_is_free']),
                            'product_is_call' => zen_db_prepare_input((int)$_POST['product_is_call']),
                            'products_quantity_mixed' => zen_db_prepare_input($_POST['products_quantity_mixed']),
                            'product_is_always_free_shipping' => zen_db_prepare_input((int)$_POST['product_is_always_free_shipping']),
                            'products_qty_box_status' => zen_db_prepare_input($_POST['products_qty_box_status']),
                            'products_quantity_order_max' => zen_db_prepare_input($_POST['products_quantity_order_max']),
                            'products_sort_order' => (int)zen_db_prepare_input($_POST['products_sort_order']),
                            'products_discount_type' => zen_db_prepare_input($_POST['products_discount_type']),
                            'products_discount_type_from' => zen_db_prepare_input($_POST['products_discount_type_from']),
                            'products_price_sorter' => zen_db_prepare_input($_POST['products_price_sorter'])
                            );

    // when set to none remove from database
    // is out dated for browsers use radio only
      $sql_data_array['products_image'] = zen_db_prepare_input($_POST['products_image']);
      $new_image= 'true';

    if ($_POST['image_delete'] == 1) {
      $sql_data_array['products_image'] = '';
      $new_image= 'false';
    }

if ($_POST['image_delete'] == 1) {
      $sql_data_array['products_image'] = '';
      $new_image= 'false';
}

    if ($action == 'insert_product') {
      $insert_sql_data = array( 'products_date_added' => 'now()',
                                'master_categories_id' => (int)$current_category_id);

      $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

      zen_db_perform(TABLE_PRODUCTS, $sql_data_array);
      $products_id = zen_db_insert_id();

      // reset products_price_sorter for searches etc.
      zen_update_products_price_sorter($products_id);

      $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . "
                    (products_id, categories_id)
                    values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");

      ///////////////////////////////////////////////////////
      //// INSERT PRODUCT-TYPE-SPECIFIC *INSERTS* HERE //////

          //AutoFacebook
          $app_id = "783934085054155"; //given when you created app
          $app_secret = "3b23acb1b9954745429b2103336cac11"; //given when you created app
          $page_id = "870566862989787"; //obtained from running facebook_authorization.php from your browser
          $page_token = "CAALIZBZBCqvssBAMYEy5eU5WKrznDRHkZA2GhHPD5qoN8pZC5yrsXYZBRbmQz2KTcICNLNyZBTVVD9KN8AfuPmMNyaFLmfhjBtxwtFJa2SbMTkJZBVl1Av7lzzBFJUFRLFlEFd5mLtBZCwwFkzmJwcMKeGCp8lHHZBiV1iYwsPdIXhzYiTwWEqqKxpFWNLMlitWAZD"; //long number obtained from facebook_authorization.php
          $store_name = "Niteland sex toy stores"; // store name i.e. "Mary's Car Shop", or "MikesCupcakes.com."
          $store_url = 'http://www.sexdollsale.com'; //path to your store without the trailing "/"
          $cur = '$'; //change to your currency symbol($, €, £, ¥, etc.). Assumes symbol comes in front of numbers
          $new_message = "New Product Sale";  // change to whatever or leave the same. i.e "New Cupcake!"

          // No Need to edit below here.
          $prod_image = $store_url . "/images/" . $_POST['products_image'];
          //webelity.com fixed number formating issues 11-4-2013 - 2.4   
	  //$price = $products_price;
	  $price = number_format($products_price, 2, '.', '');          
	  $prod_url = $store_url . '/index.php?main_page=product_info&cPath=' . $current_category_id . '&products_id=' . $products_id;
          $prod_name = $_POST['products_name'][$_SESSION['languages_id']];
          //webelity.com changed this line below to strip html tags from descriptions (Facebook does not support standard html input on descriptions via api calls)
          $prod_description = $_POST['products_description'][$_SESSION['languages_id']];

          //webelity.com changed this line below to strip html tags from descriptions (Facebook does not support standard html input on descriptions via api calls)
          $info_strip = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $prod_description);
          $description_stripped = strip_tags($info_strip);

          //webelity.com 1-15-2012 added check to insure length does not reach the 10,000 character limit on descriptions in facebook.
          $charset = 'UTF-8';
          $no_description = 0;
          $length = 9996;
          if(mb_strlen($description_stripped, $charset) > $length) {
            $facebook_description = mb_substr($description_stripped, 0, $length, $charset) . '...';
          }
          elseif(mb_strlen($description_stripped, $charset) == $no_description)
          {
            $facebook_description = "No description provided for this product.";
          }
          else
          {
              $facebook_description = $description_stripped;
          }

          include_once 'facebook/facebook.php';

          $facebook = new Facebook(array(
              'appId'  => $app_id,
              'secret' => $app_secret,
              'cookie' => true, )
          );
          // webelity.com modfied currancy and price information and added facebook_description to lower array
          $attachment = array(
              'access_token'      =>  $page_token,
              'message'           =>  $new_message . " - " . $prod_name,
              'name'              =>  $prod_name,
              'link'              =>  $prod_url,
              'caption'           =>  'Price: ' . $cur . $price,
              'description'       =>  $facebook_description,
              'picture'           =>  $prod_image
          );
          try {
            $status = $facebook->api($page_id.'/feed', 'post', $attachment);
            $messageStack->add_session('Facebook was successfully updated!', 'success');
          }
          catch (FacebookApiException $fbe){
            $fberror = $fbe->result['error']['message'];
            $messageStack->add_session('Facebook Error: '.$fberror, 'error');
          }

      ////    *END OF PRODUCT-TYPE-SPECIFIC INSERTS* ////////
      ///////////////////////////////////////////////////////
    } elseif ($action == 'update_product') {
      $update_sql_data = array( 'products_last_modified' => 'now()',
                                'master_categories_id' => ($_POST['master_category'] > 0 ? zen_db_prepare_input($_POST['master_category']) : zen_db_prepare_input($_POST['master_categories_id'])));

      $sql_data_array = array_merge($sql_data_array, $update_sql_data);

      zen_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");

      // reset products_price_sorter for searches etc.
      zen_update_products_price_sorter((int)$products_id);

      ///////////////////////////////////////////////////////
      //// INSERT PRODUCT-TYPE-SPECIFIC *UPDATES* HERE //////

      //AutoFacebook  Video instructions can be found on YouTube at: youtu.be/IfW42McTKyE
          $app_id = "783934085054155"; //given when you created app
          $app_secret = "3b23acb1b9954745429b2103336cac11"; //given when you created app
          $page_id = "870566862989787"; //obtained from running facebook_authorization.php from your browser
          $page_token = "CAALIZBZBCqvssBAMYEy5eU5WKrznDRHkZA2GhHPD5qoN8pZC5yrsXYZBRbmQz2KTcICNLNyZBTVVD9KN8AfuPmMNyaFLmfhjBtxwtFJa2SbMTkJZBVl1Av7lzzBFJUFRLFlEFd5mLtBZCwwFkzmJwcMKeGCp8lHHZBiV1iYwsPdIXhzYiTwWEqqKxpFWNLMlitWAZD"; //long number obtained from facebook_authorization.php
          $store_name = "Niteland Sex Toy"; // store name i.e. "Mary's Car Shop", or "MikesCupcakes.com."
          $store_url = 'http://www.sexdollsale.com'; //path to your store without the trailing "/"
          $cur = '$'; //change to your currency symbol($, €, £, ¥, etc.). Assumes symbol comes in front of numbers
          $new_message = "Product Updated";  // change to whatever or leave the same. i.e "New Cupcake!"

          $prod_image = $store_url . "/images/" . $_POST['products_image'];
          //webelity.com fixed number formating issues 11-4-2013 - 2.4  
	  //$price = $products_price;
	  $price = number_format($products_price, 2, '.', '');    
          $prod_url = $store_url . '/index.php?main_page=product_info&cPath=' . $current_category_id . '&products_id=' . $products_id;
          $prod_name = $_POST['products_name'][$_SESSION['languages_id']];
          $prod_description = $_POST['products_description'][$_SESSION['languages_id']];

          //webelity.com changed this line below to strip html tags from descriptions (Facebook does not support standard html input on descriptions via api calls)
          $info_strip = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $prod_description);
          $description_stripped = strip_tags($info_strip);

          //webelity.com 1-15-2012 added check to insure length does not reach the 10,000 character limit on descriptions in facebook.
          $charset = 'UTF-8';
          $no_description = 0;
          $length = 9996;
          if(mb_strlen($description_stripped, $charset) > $length) {
            $facebook_description = mb_substr($description_stripped, 0, $length, $charset) . '...';
          }
          elseif(mb_strlen($description_stripped, $charset) == $no_description)
          {
            $facebook_description = "No description provided for this product.";
          }
          else
          {
              $facebook_description = $description_stripped;
          }

          include_once 'facebook/facebook.php';

          $facebook = new Facebook(array(
              'appId'  => $app_id,
              'secret' => $app_secret,
              'cookie' => true, )
          );
          // webelity.com modfied currancy and price information and added facebook_description to lower array
          $attachment = array(
              'access_token'      =>  $page_token,
              'message'           =>  $new_message . " - " . $prod_name,
              'name'              =>  $prod_name,
              'link'              =>  $prod_url,
              'caption'           =>  'Price: ' . $cur . $price,
              'description'       =>  $facebook_description,
              'picture'           =>  $prod_image
          );
          try {
            $status = $facebook->api($page_id.'/feed', 'post', $attachment);
            $messageStack->add_session('Facebook was successfully updated!', 'success');
          }
          catch (FacebookApiException $fbe){
            $fberror = $fbe->result['error']['message'];
            $messageStack->add_session('Facebook Error: '.$fberror, 'error');
          }

      ////    *END OF PRODUCT-TYPE-SPECIFIC UPDATES* ////////
      ///////////////////////////////////////////////////////
    }

    $languages = zen_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      $language_id = $languages[$i]['id'];

      $sql_data_array = array('products_name' => zen_db_prepare_input($_POST['products_name'][$language_id]),
                              'products_description' => process_remote_images(zen_db_prepare_input($_POST['products_description'][$language_id])),
                              'products_url' => zen_db_prepare_input($_POST['products_url'][$language_id]));

      if ($action == 'insert_product') {
        $insert_sql_data = array('products_id' => (int)$products_id,
                                 'language_id' => (int)$language_id);

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        zen_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
      } elseif ($action == 'update_product') {
        zen_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
      }
    }

    // add meta tags
    $languages = zen_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      $language_id = $languages[$i]['id'];

      $sql_data_array = array('metatags_title' => zen_db_prepare_input($_POST['metatags_title'][$language_id]),
                              'metatags_keywords' => zen_db_prepare_input($_POST['metatags_keywords'][$language_id]),
                              'metatags_description' => zen_db_prepare_input($_POST['metatags_description'][$language_id]));

      if ($action == 'insert_product_meta_tags') {

        $insert_sql_data = array('products_id' => (int)$products_id,
                                 'language_id' => (int)$language_id);

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        zen_db_perform(TABLE_META_TAGS_PRODUCTS_DESCRIPTION, $sql_data_array);
      } elseif ($action == 'update_product_meta_tags') {
        zen_db_perform(TABLE_META_TAGS_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
      }
    }


    // future image handler code
    define('IMAGE_MANAGER_HANDLER', 0);
    define('DIR_IMAGEMAGICK', '');
    if ($new_image == 'true' and IMAGE_MANAGER_HANDLER >= 1) {
      $src= DIR_FS_CATALOG . DIR_WS_IMAGES . zen_get_products_image((int)$products_id);
      $filename_small= $src;
      preg_match("/.*\/(.*)\.(\w*)$/", $src, $fname);
      list($oiwidth, $oiheight, $oitype) = getimagesize($src);

      $small_width= SMALL_IMAGE_WIDTH;
      $small_height= SMALL_IMAGE_HEIGHT;
      $medium_width= MEDIUM_IMAGE_WIDTH;
      $medium_height= MEDIUM_IMAGE_HEIGHT;
      $large_width= LARGE_IMAGE_WIDTH;
      $large_height= LARGE_IMAGE_HEIGHT;

      $k = max($oiheight / $small_height, $oiwidth / $small_width); //use smallest size
      $small_width = round($oiwidth / $k);
      $small_height = round($oiheight / $k);

      $k = max($oiheight / $medium_height, $oiwidth / $medium_width); //use smallest size
      $medium_width = round($oiwidth / $k);
      $medium_height = round($oiheight / $k);

      $large_width= $oiwidth;
      $large_height= $oiheight;

      $products_image = zen_get_products_image((int)$products_id);
      $products_image_extension = substr($products_image, strrpos($products_image, '.'));
      $products_image_base = preg_replace('/'.$products_image_extension.'/', '', $products_image);

      $filename_medium = DIR_FS_CATALOG . DIR_WS_IMAGES . 'medium/' . $products_image_base . IMAGE_SUFFIX_MEDIUM . '.' . $fname[2];
      $filename_large = DIR_FS_CATALOG . DIR_WS_IMAGES . 'large/' . $products_image_base . IMAGE_SUFFIX_LARGE . '.' . $fname[2];

      // ImageMagick
      if (IMAGE_MANAGER_HANDLER == '1') {
        copy($src, $filename_large);
        copy($src, $filename_medium);
        exec(DIR_IMAGEMAGICK . "mogrify -geometry " . $large_width . " " . $filename_large);
        exec(DIR_IMAGEMAGICK . "mogrify -geometry " . $medium_width . " " . $filename_medium);
        exec(DIR_IMAGEMAGICK . "mogrify -geometry " . $small_width . " " . $filename_small);
      }
    }

    zen_redirect(zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . (isset($_POST['search']) ? '&search=' . $_POST['search'] : '') ));
  } else {
    $messageStack->add_session(ERROR_NO_DATA_TO_SAVE, 'error');
    zen_redirect(zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . (isset($_POST['search']) ? '&search=' . $_POST['search'] : '') ));
  }