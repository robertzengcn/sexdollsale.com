jQuery(document).ready(function() {
  if (!jQuery('#shippingAddress-checkbox').is(':checked')) {
    jQuery('#shippingField').show();
  } else {
    jQuery('#shippingField').hide();    
  }
	
	jQuery('#shippingAddress-checkbox').click(function (){
		if(jQuery(this).is(':checked')) {
			jQuery('#shippingField').fadeOut();
		} else {
			jQuery('#shippingField').fadeIn();
		}
	});
});