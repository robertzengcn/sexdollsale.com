<?php
/**
 * additional_images module
 *
 * Prepares list of additional product images to be displayed in template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: additional_images.php 18697 2011-05-04 14:35:20Z wilt $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
if (!defined('IMAGE_ADDITIONAL_DISPLAY_LINK_EVEN_WHEN_NO_LARGE')) define('IMAGE_ADDITIONAL_DISPLAY_LINK_EVEN_WHEN_NO_LARGE','Yes');
$images_array = array();

// do not check for additional images when turned off
if ($products_image != '' && $flag_show_product_info_additional_images != 0) {
  // prepare image name
  $products_image_extension = substr($products_image, strrpos($products_image, '.'));
  $products_image_base = str_replace($products_image_extension, '', $products_image);

  // if in a subdirectory
  if (strrpos($products_image, '/')) {
    $products_image_match = substr($products_image, strrpos($products_image, '/')+1);
    //echo 'TEST 1: I match ' . $products_image_match . ' - ' . $file . ' -  base ' . $products_image_base . '<br>';
    $products_image_match = str_replace($products_image_extension, '', $products_image_match) . '_';
    $products_image_base = $products_image_match;
  }

  $products_image_directory = str_replace($products_image, '', substr($products_image, strrpos($products_image, '/')));
  if ($products_image_directory != '') {
    $products_image_directory = DIR_WS_IMAGES . str_replace($products_image_directory, '', $products_image) . "/";
  } else {
    $products_image_directory = DIR_WS_IMAGES;
  }

  // Check for additional matching images
  $file_extension = $products_image_extension;
  $products_image_match_array = array();
  if ($dir = @dir($products_image_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($products_image_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          if(preg_match('/\Q' . $products_image_base . '\E/i', $file) == 1) {
            if ($file != $products_image) {
              if ($products_image_base . str_replace($products_image_base, '', $file) == $file) {
                //  echo 'I AM A MATCH ' . $file . '<br>';
                $images_array[] = $file;
              } else {
                //  echo 'I AM NOT A MATCH ' . $file . '<br>';
              }
            }
          }
        }
      }
    }
    if (sizeof($images_array)) {
      sort($images_array);
    }
    $dir->close();
  }
}

// Build output based on images found
$num_images = sizeof($images_array);

$additionalImages = '';

if ($num_images) {

  for ($i=0, $n=$num_images; $i<$n; $i++) {
    $file = $images_array[$i];
    // Medium Image
    $products_image_medium_additional = str_replace(DIR_WS_IMAGES, DIR_WS_IMAGES . 'medium/', $products_image_directory) . str_replace($products_image_extension, '', $file) . IMAGE_SUFFIX_MEDIUM . $products_image_extension;
    $flag_has_medium = file_exists($products_image_medium_additional);
    $products_image_medium_additional = ($flag_has_medium ? $products_image_medium_additional : $products_image_directory . $file);    
    // Large Image
    $products_image_large_additional = str_replace(DIR_WS_IMAGES, DIR_WS_IMAGES . 'large/', $products_image_directory) . str_replace($products_image_extension, '', $file) . IMAGE_SUFFIX_LARGE . $products_image_extension;
    $flag_has_large = file_exists($products_image_large_additional);
    $products_image_large_additional = ($flag_has_large ? $products_image_large_additional : $products_image_directory . $file);    
    // Base Image
    $base_image = $products_image_directory . $file;
    $thumb_slashes = zen_image(addslashes($base_image), addslashes($products_name), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
    $thumb_regular = zen_image($base_image, $products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
    
    // Additional Images array generation:    
    $additionalImages[$i] = array(
                              'small' => $thumb_regular,
                              'medium' => $products_image_medium_additional,
                              'large' => $products_image_large_additional,
                              'name' => $products_name
                            );

  } // end for loop
} // endif
