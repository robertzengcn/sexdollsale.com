<?php
/**
 * Page Template - tpl_footer_info_big.php
 *
 * Displays in footer the Twitter, Facebook, About Us and Contacts Information.  
 *
 */
?>

<div id="footerInfoBig" class="container group">
	
	<?php 
		$columns = 'col_1_of_5';
		$column_nr = 5;
		
		if(empty($info_content->fields['info_custom_info'])) $column_nr--;
		if(empty($info_content->fields['info_facebook'])) $column_nr--;
		if(empty($info_content->fields['info_twitter'])) $column_nr--;
		if(empty($info_content->fields['info_about_us'])) $column_nr--;
		if(empty($info_content->fields['info_contacts'])) $column_nr--;

		switch ($column_nr) {
			case 5: $columns = 'col_1_of_5'; break;
			case 4: $columns = 'col_1_of_4'; break;
			case 3: $columns = 'col_1_of_3'; break;
			case 2: $columns = 'col_1_of_2'; break;
			case 1: $columns = 'col_12_of_12'; break;
		}	

	foreach ($info_content->fields as $name => $order) {
		if(isset($order) and $order > 0 and $name == 'info_custom_info'){   
		?>		
			<div class="col <?php echo $columns; ?>">
				<div>
					<h3><?php echo FOOTER_TXT_MUST_HAVE; ?><div class="toggler">Toggle</div></h3>
					<div class="footerContent">
						<?php 
							// include template specific file name defines						
							require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_FOOTER_CUSTOM_INFO, 'false'));		
						?>
					</div>
				</div>
			</div>
		<?php } if(isset($order) and $order > 0 and $name == 'info_twitter'){ ?>
			<div class="col <?php echo $columns; ?>">
				<div>
					<h3><?php echo FOOTER_TXT_TWITTER; ?><div class="toggler">Toggle</div></h3>
					<div class="footerContent">
						<div class="<?php echo TWITTER_CONTAINER; ?>">
							
						</div>
					</div>
				</div>
			</div>				
		<?php } if(isset($order) and $order > 0 and $name == 'info_facebook'){ ?>
			<div class="col <?php echo $columns; ?>">
				<div>
					<h3><?php echo FOOTER_TXT_FACEBOOK; ?><div class="toggler">Toggle</div></h3>
					<div class="footerContent">
						<div class="facebookLikeBoxWrapper">					
							<div class="fb-like-box" data-href="<?php echo $facebook_url; ?>" data-width="292" data-show-faces="true" data-stream="false" data-show-border="false" data-header="false"></div>
						</div>
					</div>
				</div>
			</div>
		<?php } if(isset($order) and $order > 0 and $name == 'info_about_us'){ ?>
			<div class="col <?php echo $columns; ?> footer_about_us">
				<div>
					<h3><?php echo FOOTER_TXT_ABOUT_MARS; ?><div class="toggler">Toggle</div></h3>
					<div class="footerContent">
					<?php 
						// include template specific file name defines						
						require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_FOOTER_ABOUT_US, 'false'));		
					?>	
					</div>
				</div>
			</div>
		<?php } if(isset($order) and $order > 0 and $name == 'info_contacts'){ ?>
			<div class="col <?php echo $columns; ?> footer_contact_us">
				<div>
					<h3><?php echo FOOTER_TXT_CONTACTS; ?><div class="toggler">Toggle</div></h3>						
					<div class="footerContent">
						<?php 
							// include template specific file name defines						
							require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_FOOTER_CONTACT_US, 'false'));		
						?>		
					</div>
				</div>
			</div>
	<?php }
	} ?>
</div>
<hr class="sep">
