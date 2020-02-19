<?php

class JPIBFI_Admin {

	protected static $instance = null;

	private $admin_screen_hook = '';

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'print_admin_page_action') );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_meta_data' ) );
        add_action( 'admin_enqueue_scripts', array($this, 'add_admin_site_scripts') );
        add_action( 'admin_notices', array( $this, 'show_admin_notice') );
        add_filter( 'plugin_row_meta', array($this, 'plugin_meta_links'), 10, 2 );
        add_action( 'wp_ajax_jpibfi_remove_pro_ad', array($this, 'remove_pro_ad') );
	}

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function print_admin_page_action() {

        $name = 'jQuery Pin It Button For Images Lite';

		$this->admin_screen_hook = add_options_page(
			$name,
			$name,
			'manage_options',
			'jpibfi_settings',
			array( $this, 'print_admin_page' )
		);
	}

	/* Adds admin scripts */
	public function add_admin_site_scripts($hook) {

        $hooks = array();
        $hooks[] = $this->admin_screen_hook;
        $hooks[] = 'plugins.php';

        if (!in_array($hook, $hooks))
            return;

        $is_plugins_php = $hook == 'plugins.php';

		wp_register_style( 'jquery-pin-it-button-admin-style', JPIBFI_Globals::get_plugin_url() . 'css/admin.css', array(), JPIBFI_Globals::get_file_version(), 'all' );
		wp_enqueue_style( 'jquery-pin-it-button-admin-style' );

        $settings = array();
        $settings['version'] = 'lite';
		wp_enqueue_script( 'jquery-pin-it-button-admin-script', JPIBFI_Globals::get_plugin_url() . 'js/admin.js', array( 'jquery' ), JPIBFI_Globals::get_file_version(), false );
        wp_localize_script('jquery-pin-it-button-admin-script', 'jpibfiAdminSettings', $settings);

        if ($is_plugins_php)
            return;

		if ( function_exists( "wp_enqueue_media") ) {
			wp_enqueue_media();
			wp_enqueue_script( 'jpibfi-upload-new', JPIBFI_Globals::get_plugin_url() . 'js/upload-button-new.js', array(), JPIBFI_Globals::get_file_version(), false );
		} 	else {
			wp_enqueue_script( 'jpibfi-upload-old', JPIBFI_Globals::get_plugin_url() . 'js/upload-button-old.js', array('thickbox', 'media-upload' ), JPIBFI_Globals::get_file_version(), false );
		}
	}

	/* Prints admin page */
	public function print_admin_page() {

		//dictionary of tab names associated with settings to render names
		$settings_tabs = array(
			'selection_options' => array(
				'settings_name' => 'jpibfi_selection_options',
				'tab_label' => __( 'Selection', 'jquery-pin-it-button-for-images' ),
				'support_link' => 'http://wordpress.org/support/plugin/jquery-pin-it-button-for-images',
			),
			'visual_options' => array(
				'settings_name' => 'jpibfi_visual_options',
				'tab_label' => __( 'Visual', 'jquery-pin-it-button-for-images' ),
				'support_link' => 'http://wordpress.org/support/plugin/jquery-pin-it-button-for-images',
			),
			'advanced_options' => array(
				'settings_name' => 'jpibfi_advanced_options',
				'tab_label' => __('Advanced', 'jquery-pin-it-button-for-images' ),
				'support_link' => 'http://wordpress.org/support/plugin/jquery-pin-it-button-for-images',
			)
		);

		$settings_tabs = apply_filters( 'jpibfi_settings_tabs', $settings_tabs);

		include_once( 'views/admin.php' );
		//cumbersome, but needed for error management to work properly in WP 3.3
		delete_option( JPIBFI_Selection_Options::get_instance()->get_option_name() . '_errors' );
		delete_option( JPIBFI_Visual_Options::get_instance()->get_option_name() . '_errors' );
	}

	/* Meta box for each post and page */
	public function add_meta_box() {
        /* TODO check if has permissions*/
        $post_types = array("post", "page");
        foreach($post_types as $pt){
            add_meta_box(
                'jpibfi_settings_id',
                    'jQuery Pin It Button for Images - ' . __( 'Settings', 'jquery-pin-it-button-for-images' ),
                array( $this, 'print_meta_box' ), 
                $pt,
                'side',
                'default'
            );
        }
	}

	/* Displays the meta box */
	public function print_meta_box( $post, $metabox ) {

		wp_nonce_field( plugin_basename( __FILE__ ), 'jpibfi_nonce' );

		$post_meta = get_post_meta( $post->ID, 'jpibfi_meta', true );
		$checked = isset( $post_meta ) && isset( $post_meta['jpibfi_disable_for_post'] ) && $post_meta['jpibfi_disable_for_post'] == '1';

		echo '<input type="checkbox" id="jpibfi_disable_for_post" name="jpibfi_disable_for_post" value="1"'	. checked( $checked, true, false ) . '>';
		echo '<label for="jpibfi_disable_for_post">' . __( 'Disable "Pin it" button for this post (works only on single pages/posts)', 'jquery-pin-it-button-for-images' ) . '</label><br />';
	}

	public function save_meta_data( $post_id ) {

		//check user's permissions and autosave
		if ( ! current_user_can( 'edit_post', $post_id ) ||  (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) )
			return;

		// security check = updating possible only using post edit form
		if ( !isset( $_POST['jpibfi_nonce'] ) || ! wp_verify_nonce( $_POST['jpibfi_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		$post_meta = array( 'jpibfi_disable_for_post' => '0' );
		// now store data in custom fields based on checkboxes selected
		$post_meta['jpibfi_disable_for_post'] =	isset( $_POST['jpibfi_disable_for_post'] ) && $_POST['jpibfi_disable_for_post'] == '1' ? '1' : '0';

		if ( $post_meta['jpibfi_disable_for_post'] == '1' )
			update_post_meta( $post_id, 'jpibfi_meta', $post_meta );
		else
			delete_post_meta( $post_id, 'jpibfi_meta' );
	}

    function show_admin_notice() {
        global $hook_suffix;
        $hooks = array();
        $hooks[] = $this->admin_screen_hook;
        if (get_option('jpibfi_pro_ad')) {
            $hooks[] = 'plugins.php';
        }

        if (in_array($hook_suffix, $hooks)) {
            $show_close = 'plugins.php' == $hook_suffix;
            ?>
            <div class="updated jpibfi-pro-notice">
                <p><?php echo sprintf(__('jQuery Pin It Button for Images Pro is coming soon. Click <a target="_blank" href="%s">here</a> to learn more.', 'jquery-pin-it-button-for-images'), 'http://mrsztuczkens.me/jpibfi-pro/'); ?></p>
                <?php if ($show_close): ?><span id="jpibfi_remove_ad" class="dashicons dashicons-no"></span><?php endif; ?>
            </div>
            <?php
        }
    }

    function plugin_meta_links( $links, $file ) {
        $basefile = JPIBFI_Globals::get_plugin_file();
        $plugin = plugin_basename($basefile);
        // create link
        if ( $file == $plugin ) {
            return array_merge(
                $links,
                array( '<a href="http://mrsztuczkens.me/jpibfi-pro/">jQuery Pin It Button for Images Pro</a>' )
            );
        }
        return $links;
    }

    function remove_pro_ad(){
        delete_option('jpibfi_pro_ad');
        wp_die();
    }



}

add_action( 'plugins_loaded', array( 'JPIBFI_Admin', 'get_instance' ) );