<?php

?>
<div class="wrap">

			<h2><?php _e( 'jQuery Pin It Button For Images Options', 'jquery-pin-it-button-for-images' ); ?></h2>
<?php
$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'selection_options';
$tab_found = false;
foreach( $settings_tabs as $tab_name => $tab_settings)
	$tab_found = $tab_found || ( $tab_name == $tab );

$tab = false == $tab_found ? 'selection_options' : $tab;
$current_settings = $settings_tabs[ $tab ];
?>
<div id="icon-plugins" class="icon32"></div>
<h2 class="nav-tab-wrapper">
	<?php foreach( $settings_tabs as $tab_name => $settings) { ?>
		<a href="?page=jpibfi_settings&tab=<?php echo $tab_name; ?>" class="nav-tab <?php echo $tab_name == $tab ? 'nav-tab-active' : ''; ?>"><?php echo $settings['tab_label']; ?></a>
	<?php } ?>
</h2>

<p>
	<a href="http://mrsztuczkens.me/how-to-get-the-most-out-of-jpibfi/" class="button" target="_blank" rel="nofollow"><b><?php _e( 'How to Get The Most Out of JPIBFI', 'jquery-pin-it-button-for-images' ); ?></b></a>
	<a href="<?php echo $current_settings['support_link']; ?>" class="button" target="_blank" rel="nofollow"><b><?php _e( 'Support forum', 'jquery-pin-it-button-for-images' ); ?></b></a>
</p>
<form method="post" action="options.php">
	<?php
	settings_fields( $current_settings[ 'settings_name'] );
	do_settings_sections( $current_settings[ 'settings_name'] );
	submit_button();
	?>
</form>
</div>