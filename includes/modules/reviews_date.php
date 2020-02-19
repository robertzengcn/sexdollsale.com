<?php require(DIR_WS_LANGUAGES.'english/'.'product_reviews_write.php');?>



<!--bof Product description reviews -->

				<?php $review_status = " and r.status = 1";



  /*$reviews_query_raw = "SELECT r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name

                        FROM " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd

                        WHERE r.products_id = ".(int)$_GET['products_id']."

                        AND r.reviews_id = rd.reviews_id

                        AND rd.languages_id = ".(int)$_SESSION['languages_id'] . $review_status . "

                        ORDER BY r.reviews_id desc";*/

	$reviews_query_raw = "SELECT r.reviews_id, rd.reviews_text, r.reviews_rating, r.date_added, r.customers_name

                        FROM " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd

                        WHERE r.products_id = ".(int)$_GET['products_id']."

                        AND r.reviews_id = rd.reviews_id

                        AND rd.languages_id = ".(int)$_SESSION['languages_id'] . $review_status . "

                        ORDER BY r.reviews_id desc";
				

  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  $reviews = $db->Execute($reviews_split->sql_query);

  $reviewsArray = array();

  while (!$reviews->EOF) {

  	$reviewsArray[] = array('id'=>$reviews->fields['reviews_id'],

  	                        'customersName'=>$reviews->fields['customers_name'],

  	                        'dateAdded'=>$reviews->fields['date_added'],

  	                        'reviewsText'=>$reviews->fields['reviews_text'],

  	                        'reviewsRating'=>$reviews->fields['reviews_rating']);



    $reviews->MoveNext();

  }

  ?>