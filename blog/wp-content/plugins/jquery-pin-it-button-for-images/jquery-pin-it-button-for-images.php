<?php
/*
Plugin Name: jQuery Pin It Button For Images
Plugin URI: http://mrsztuczkens.me/jpibfi/
Description: Highlights images on hover and adds a "Pin It" button over them for easy pinning.
Text Domain: jquery-pin-it-button-for-images
Domain Path: /languages
Author: Marcin Skrzypiec
Version:1.51
Author URI: http://mrsztuczkens.me/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'jQuery_Pin_It_Button_For_Images' ) ) :

	final class jQuery_Pin_It_Button_For_Images {

		private static $instance;

		private function __construct() {
			$this->includes();
			$this->load_textdomain();

			$jpibfi_plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$jpibfi_plugin", array( $this, 'plugin_settings_filter' ) );

			register_activation_hook( __FILE__, array( $this, 'update_plugin' ) );
			add_action( 'plugins_loaded', array( $this, 'update_plugin' ) );
		}

		public static function instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		private function includes() {

            require_once(plugin_dir_path(__FILE__) . 'includes/class-jpibfi-globals.php');
            JPIBFI_Globals::init(__FILE__,  '1.51', 'a');

            $files = array(
                'includes/admin/class-jpibfi-admin-utilities.php',
                'includes/admin/class-jpibfi-options.php',
                'includes/admin/class-jpibfi-selection-options.php',
                'includes/admin/class-jpibfi-advanced-options.php',
                'includes/admin/class-jpibfi-visual-options.php',
                'includes/admin/class-jpibfi-admin.php',
                'includes/public/class-jpibfi-client-utilities.php',
                'includes/public/class-jpibfi-client.php'
            );
            foreach($files as $file)
                require_once JPIBFI_Globals::get_plugin_dir() .$file;
		}

		public function load_textdomain() {
			load_plugin_textdomain( 'jquery-pin-it-button-for-images', FALSE, dirname( plugin_basename( JPIBFI_Globals::get_plugin_file() ) ) . '/languages/' );
		}

		public function plugin_settings_filter( $links ) {
			$settings_link = '<a href="options-general.php?page=jpibfi_settings">' . __( 'Settings', 'jquery-pin-it-button-for-images' ) . '</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		/* Function updates DB if it detects new version of the plugin */
		public function update_plugin() {
            $version_option = 'jpibfi_version';
            $update_option = 'jpibfi_update_options';

            $jpibfi_v = JPIBFI_Globals::get_version();
			$version = get_option( $version_option );
			if ( false == $version || (float)$version < (float)$jpibfi_v  || get_option( $update_option ) ) {
				update_option( $version_option, $jpibfi_v);
				update_option( $update_option, false);
                update_option('jpibfi_pro_ad', 1);
			}
		}
	}

endif; // End if class_exists check


$JPIBFI = jQuery_Pin_It_Button_For_Images::instance();
