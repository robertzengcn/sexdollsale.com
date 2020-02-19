<?php
/**
 * Page Template - tpl_footer_info_small.php
 *
 * Displays in footer the Follow Us, Contact Us and Payment Options.  
 *
 */
?>

<div id="footerInfoSmall" class="container">
	
	<ul class="socialContainer">
		<li class="first"><?php echo FOOTER_TXT_FOLLOW_US; ?>:</li>
		<?php 	
			foreach ($follow_us_order as $order_nr) {		 
				if(isset($order_nr) and $order_nr == 1 and $facebook){ 	 echo '<li><a class="facebook tooltip" target="_blank" title="Facebook" alt="Facebook" href="'.$facebook.'">Facebook</a></li>'; }
				if(isset($order_nr) and $order_nr == 2 and $twitter){ 	 echo '<li><a class="twitter tooltip" target="_blank" title="Twitter" alt="Twitter" href="'.$twitter.'">Twitter</a></li>'; }
				if(isset($order_nr) and $order_nr == 3 and $googleplus){ echo '<li><a class="googleplus tooltip" target="_blank" title="GooglePlus" alt="GooglePlus" href="'.$googleplus.'">GooglePlus</a></li>'; }
				if(isset($order_nr) and $order_nr == 4 and $linkedin){ 	 echo '<li><a class="linkedin tooltip" target="_blank" title="LinkedIn" alt="LinkedIn" href="'.$linkedin.'">LinkedIn</a></li>'; }
				if(isset($order_nr) and $order_nr == 5 and $youtube){    echo '<li><a class="youtube tooltip" target="_blank" title="Youtube" alt="Youtube" href="'.$youtube.'">Youtube</a></li>'; }
				if(isset($order_nr) and $order_nr == 6 and $vimeo){ 	 echo '<li><a class="vimeo tooltip" target="_blank" title="Vimeo" alt="Vimeo" href="'.$vimeo.'">Vimeo</a></li>'; }
				if(isset($order_nr) and $order_nr == 7 and $tumblr){ 	 echo '<li><a class="tumblr tooltip" target="_blank" title="Tumblr" alt="Tumblr" href="'.$tumblr.'">Tumblr</a></li>'; }
				if(isset($order_nr) and $order_nr == 8 and $rss){ 		 echo '<li><a class="rss tooltip" target="_blank" title="Rss" alt="Rss" href="'.$rss.'">Rss</a></li>'; }
				if(isset($order_nr) and $order_nr == 9 and $skype){ 	 echo '<li><a class="skype tooltip" target="_blank" title="'.$skype.'" alt="'.$skype.'" href="'.$skype.'">Skype</a></li>'; }
			}			
		?>		
	</ul>	
	<ul class="paymentContainer">
		<?php
			foreach ($payment_options->fields as $name => $order) {   
				if(isset($order) and $order > 0 and $name == 'visa'){ 	 		 echo '<li class="visa">Visa</li>'; } 
				if(isset($order) and $order > 0 and $name == 'mastercard'){ 	 echo '<li class="mastercard">Mastercard</li>'; } 
				if(isset($order) and $order > 0 and $name == 'americanexpress'){ echo '<li class="americanexpress">AmericanExpress</li>'; } 
				if(isset($order) and $order > 0 and $name == 'paypal'){  	 	 echo '<li class="paypal">Paypal</li>'; } 
				if(isset($order) and $order > 0 and $name == 'discover'){  	 	 echo '<li class="discover">Discover</li>'; } 
				if(isset($order) and $order > 0 and $name == 'westernunion'){ 	 echo '<li class="westernunion">Westernunion</li>'; } 
			}
		?>			
	</ul>
	<?php if($contact_info){ echo '<p class="callus"><i><span>info</span></i>'.$contact_info.'</p>'; } ?>
		
</div>
<hr class="sep">