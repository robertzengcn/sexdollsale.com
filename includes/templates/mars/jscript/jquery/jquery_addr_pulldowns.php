<?php
/**
 * jscript_addr_pulldowns
 *
 * handles pulldown menu dependencies for state/country selection
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id:
 */
?>
<script language="javascript" type="text/javascript"><!--//
	<?php
		switch($_GET['main_page']) {
			case 'no_account':
	?>
  jQuery(document).ready(function() {
    update_zone(document.no_account);
  });
	<?php
				break;
			case 'create_account':
	?>
  jQuery(document).ready(function() {
    update_zone(document.create_account);
  });
	<?php
				break;
		}
	?>
  function update_zone(theForm) {
    // if there is no zone_id field to update, or if it is hidden from display, then exit performing no updates
    if (!theForm || !theForm.elements["zone_id"]) return;
    if (theForm.zone_id.type == "hidden") return;

    // set initial values
    var SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;
    var SelectedZone = theForm.elements["zone_id"].value;

    // reset the array of pulldown options so it can be repopulated
    var NumState = theForm.zone_id.options.length;
    while(NumState > 0) {
      NumState = NumState - 1;
      theForm.zone_id.options[NumState] = null;
    } 
    // build dynamic list of countries/zones for pulldown
  <?php echo zen_js_zone_list('SelectedCountry', 'theForm', 'zone_id'); ?>

    // if we had a value before reset, set it again
    if (SelectedZone != "") theForm.elements["zone_id"].value = SelectedZone;
    
    // display the state drop down
    displayStateDropdown(theForm);
  }

  function update_zone_shipping(theForm) {
    // if there is no zone_id field to update, or if it is hidden from display, then exit performing no updates
    if (!theForm || !theForm.elements["zone_id_shipping"]) return;
    if (theForm.zone_id_shipping.type == "hidden") return;

    // set initial values
    var SelectedCountry = theForm.zone_country_id_shipping.options[theForm.zone_country_id_shipping.selectedIndex].value;
    var SelectedZone = theForm.elements["zone_id_shipping"].value;

    // reset the array of pulldown options so it can be repopulated
    var NumState = theForm.zone_id_shipping.options.length;
    while(NumState > 0) {
      NumState = NumState - 1;
      theForm.zone_id_shipping.options[NumState] = null;
    }
    // build dynamic list of countries/zones for pulldown
  <?php echo zen_fec_js_zone_list_shipping('SelectedCountry', 'theForm', 'zone_id_shipping'); ?>

    // if we had a value before reset, set it again
    if (SelectedZone != "") theForm.elements["zone_id_shipping"].value = SelectedZone;
    
    // display the state drop down
    displayStateDropdownShipping(theForm);
  }
  
  function displayStateDropdown(theForm) {
    jQuery('[name="zone_id"]').removeClass('hiddenField');
  }

  function displayStateDropdownShipping(theForm) {
    jQuery('[name="zone_id_shipping"]').removeClass('hiddenField');
  }

  function hideStateField(theForm) {
    if (jQuery('[name="state"]').length > 0) {
      jQuery('[name="state"]').attr('disabled', 'disabled');
      jQuery('[name="state"]').removeClass('visibleField');
      jQuery('[name="state"]').addClass('hiddenField');
    }
    if (jQuery('#stateLabel').length > 0) {
      jQuery('#stateLabel').removeClass('visibleField');
      jQuery('#stateLabel').addClass('hiddenField');
    }
    if (jQuery('#stBreak').length > 0) {
      jQuery('#stBreak').removeClass('visibleField');
      jQuery('#stBreak').addClass('hiddenField');
    }
    if (jQuery('#stText').length > 0) {
      jQuery('#stText').removeClass('visibleField');
      jQuery('#stText').addClass('hiddenField');
    }
    
        jQuery("#fec-state-2-field").hide();
    
  }
  
  function hideStateFieldShipping() {
    if (jQuery('[name="state_shipping"]').length > 0) {
      jQuery('[name="state_shipping"]').attr('disabled', 'disabled');
      jQuery('[name="state_shipping"]').removeClass('visibleField');
      jQuery('[name="state_shipping"]').addClass('hiddenField');
    }
    if (jQuery('#stateLabelShipping').length > 0) {
      jQuery('#stateLabelShipping').removeClass('visibleField');
      jQuery('#stateLabelShipping').addClass('hiddenField');
    }
    if (jQuery('#stBreakShipping').length > 0) {
      jQuery('#stBreakShipping').removeClass('visibleField');
      jQuery('#stBreakShipping').addClass('hiddenField');
    }
    if (jQuery('#stTextShipping').length > 0) {
      jQuery('#stTextShipping').removeClass('visibleField');
      jQuery('#stTextShipping').addClass('hiddenField');
    }

    
        jQuery("#fec-state-2-field-shipping").hide();
    
  }

  function showStateField() {
    if (jQuery('[name="state"]').length > 0) {
      jQuery('[name="state"]').removeAttr('disabled');
      jQuery('[name="state"]').removeClass('hiddenField');
      jQuery('[name="state"]').addClass('visibleField');
    }
    if (jQuery('#stateLabel').length > 0) {
      jQuery('#stateLabel').removeClass('hiddenField');
      jQuery('#stateLabel').addClass('visibleField');
    }
    if (jQuery('#stBreak').length > 0) {
      jQuery('#stBreak').removeClass('hiddenField');
       jQuery('#stBreak').addClass('visibleField');
    }
    if (jQuery('#stText').length > 0) {
      jQuery('#stText').removeClass('hiddenField');
      jQuery('#stText').addClass('visibleField');
    }
    
    if(jQuery("#country").val() !== "") {
        jQuery("#fec-state-2-field").show();
    }
  }

  function showStateFieldShipping() {
    if (jQuery('[name="state_shipping"]').length > 0) {
      jQuery('[name="state_shipping"]').removeAttr('disabled');
      jQuery('[name="state_shipping"]').removeClass('hiddenField');
      jQuery('[name="state_shipping"]').addClass('visibleField');
    }
    if (jQuery('#stateLabelShipping').length > 0) {
      jQuery('#stateLabelShipping').removeClass('hiddenField');
      jQuery('#stateLabelShipping').addClass('visibleField');
    }
    if (jQuery('#stBreakShipping').length > 0) {
      jQuery('#stBreakShipping').removeClass('hiddenField');
       jQuery('#stBreakShipping').addClass('visibleField');
    }
    if (jQuery('#stTextShipping').length > 0) {
      jQuery('#stTextShipping').removeClass('hiddenField');
      jQuery('#stTextShipping').addClass('visibleField');
    }

    
    jQuery("#fec-state-2-field-shipping").show();
  }
//--></script>