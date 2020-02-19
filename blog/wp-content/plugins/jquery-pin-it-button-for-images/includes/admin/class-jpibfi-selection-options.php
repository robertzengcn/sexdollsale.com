<?php

class JPIBFI_Selection_Options extends JPIBFI_Options {

	protected static $instance = null;

	protected $admin_selection_options = null;

	private function __construct() {
		add_action( 'admin_init', array( $this, 'initialize_selection_options' ) );
	}

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

    function get_default_options(){
        $defaults = array(
            'image_selector' => '.jpibfi_container img',
            'disabled_classes' => 'nopin;wp-smiley',
            'enabled_classes' => '',
            'min_image_height'	=> '0',
            'min_image_width' => '0',
            'show_on_home' => '1',
            'show_on_single' => '1',
            'show_on_page' => '1',
            'show_on_category' => '1',
            'show_on_blog' => '1'
        );

        return apply_filters('jpibfi_default_selection_options', $defaults);
    }

    function get_option_name(){
        return 'jpibfi_selection_options';
    }

    private function get_checkbox_settings(){
		return array(
			'show_on_home',
			'show_on_single',
			'show_on_page',
			'show_on_category',
			'show_on_blog'
		);
	}

	/*
	 * Defines selection options section and adds all required fields
	 */
	public function initialize_selection_options() {

		// First, we register a section.
		add_settings_section(
			'selection_options_section',			// ID used to identify this section and with which to register options
			__( 'Selection', 'jquery-pin-it-button-for-images' ),		// Title to be displayed on the administration page
			array( $this, 'selection_options_callback' ),	// Callback used to render the description of the section
			'jpibfi_selection_options'		// Page on which to add this section of options
		);

		//lThen add all necessary fields to the section
		add_settings_field(
			'image_selector',						// ID used to identify the field throughout the plugin
			__( 'Image selector', 'jquery-pin-it-button-for-images' ),							// The label to the left of the option interface element
			array( $this, 'image_selector_callback'),	// The name of the function responsible for rendering the option interface
			'jpibfi_selection_options',	// The page on which this option will be displayed
			'selection_options_section',			// The name of the section to which this field belongs
			array(								// The array of arguments to pass to the callback. In this case, just a description.
				sprintf ( __( 'jQuery selector for all the images that should have the "Pin it" button. Set the value to %s if you want the "Pin it" button to appear only on images in content or %s to appear on all images on site (including sidebar, header and footer). If you know a thing or two about jQuery, you might use your own selector. %sClick here%s to read about jQuery selectors.', 'jquery-pin-it-button-for-images' ),
					'<a href="#" class="jpibfi_selector_option">.jpibfi_container img</a>',
					'<a href="#" class="jpibfi_selector_option">img</a>',
					'<a href="http://api.jquery.com/category/selectors/" target="_blank">',
					'</a>'
				)
			)
		);

		add_settings_field(
			'disabled_classes',
			__( 'Disabled classes', 'jquery-pin-it-button-for-images' ),
			array( $this, 'disabled_classes_callback' ),
			'jpibfi_selection_options',
			'selection_options_section',
			array(
				__( 'Pictures with these CSS classes won\'t show the "Pin it" button. Please separate multiple classes with semicolons. Spaces are not accepted.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'enabled_classes',
			__( 'Enabled classes', 'jquery-pin-it-button-for-images' ),
			array( $this, 'enabled_classes_callback' ),
			'jpibfi_selection_options',
			'selection_options_section',
			array(
				__( 'Only pictures with these CSS classes will show the "Pin it" button. Please separate multiple classes with semicolons. If this field is empty, images with any (besides disabled ones) classes will show the Pin It button.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'show_on_field',
			__( 'On which pages the "Pin it" button should be shown', 'jquery-pin-it-button-for-images' ),
			array( $this, 'show_on_field_callback' ),
			'jpibfi_selection_options',
			'selection_options_section',
			array(
				__( 'Check on which pages you want the Pinterest button to show up.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'min_image',
			__( 'Minimum resolution that should trigger the "Pin it" button to show up', 'jquery-pin-it-button-for-images' ),
			array( $this, 'min_image_callback' ),
			'jpibfi_selection_options',
			'selection_options_section',
			array(
				__( 'If you\'d like the "Pin it" button to not show up on small images (e.g. social media icons), just set the appropriate values above. The default values cause the "Pin it" button to show on every eligible image.', 'jquery-pin-it-button-for-images' ),
			)
		);

		register_setting(
			'jpibfi_selection_options',
			'jpibfi_selection_options',
			array( $this, 'sanitize_selection_options' )
		);
	}

	public function selection_options_callback() {
		echo '<p>' . __('Which images can be pinned', 'jquery-pin-it-button-for-images') . '</p>';
	}

	public function image_selector_callback( $args ) {

		$options = $this->get_selection_options();

		$selector = esc_attr( $options['image_selector'] );

		echo '<input type="text" id="image_selector" name="jpibfi_selection_options[image_selector]" value="' . $selector . '"/>';
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function disabled_classes_callback( $args ){

		$options = $this->get_selection_options();
		$value = esc_attr( $options[ 'disabled_classes' ] );
		?>
        <div id="jpibfi-disabled-classes">
            <input type="hidden" class="jpibfi-value" name="jpibfi_selection_options[disabled_classes]" value="<?php echo $value; ?>">
            <span class="jpibfi-empty"><?php echo JPIBFI_Admin_Utilities::create_description( __( 'No classes added.', 'jquery-pin-it-button-for-images' ) ); ?></span>
            <div class="jpibfi-classes-list tagchecklist"></div>
            <div>
                <label>
                    <?php _e( 'Class name', 'jquery-pin-it-button-for-images' ); ?>
                    <input class="jpibfi-class-name" type="text">
                </label>
                <button type="button" class="jpibfi-class-button button"><?php _e( 'Add', 'jquery-pin-it-button-for-images' ); ?></button>
            </div>
        </div>

		<?php
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
		echo JPIBFI_Admin_Utilities::create_errors( 'disabled_classes' );
	}

	public function enabled_classes_callback( $args ){

		$options = $this->get_selection_options();
		$value = esc_attr( $options[ 'enabled_classes' ] );

		?>
        <div id="jpibfi-enabled-classes">
            <input type="hidden" class="jpibfi-value" name="jpibfi_selection_options[enabled_classes]" value="<?php echo $value; ?>">
            <span class="jpibfi-empty"><?php echo JPIBFI_Admin_Utilities::create_description( __( 'No classes added.', 'jquery-pin-it-button-for-images' ) ); ?></span>
            <div class="jpibfi-classes-list tagchecklist"></div>
            <div>
                <label>
                    <?php _e( 'Class name', 'jquery-pin-it-button-for-images' ); ?>
                    <input class="jpibfi-class-name" type="text">
                </label>
                <button type="button" class="jpibfi-class-button button"><?php _e( 'Add', 'jquery-pin-it-button-for-images' ); ?></button>
            </div>
        </div>

		<?php
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
		echo JPIBFI_Admin_Utilities::create_errors( 'enabled_classes' );
	}

	public function show_on_field_callback( $args ) {
		$options = $this->get_selection_options();

		$show_on_home = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'show_on_home', '1' );
		$show_on_page = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'show_on_page', '1' );
		$show_on_single = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'show_on_single', '1' );
		$show_on_category = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'show_on_category', '1' );
		$show_on_blog = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'show_on_blog', '1' );
		?>

		<input type="checkbox" id="show_on_home" name="jpibfi_selection_options[show_on_home]" <?php checked( true, $show_on_home ); ?> value="1" />
		<label for="show_on_home"><?php _e( 'Home page', 'jquery-pin-it-button-for-images' ); ?></label><br/>
		<input type="checkbox" id="show_on_page" name="jpibfi_selection_options[show_on_page]" <?php checked( true, $show_on_page ); ?> value="1" />
		<label for="show_on_page"><?php _e( 'Pages', 'jquery-pin-it-button-for-images' ); ?></label><br />
		<input type="checkbox" id="show_on_single" name="jpibfi_selection_options[show_on_single]" <?php checked( true, $show_on_single ); ?> value="1" />
		<label for="show_on_single"><?php _e( 'Single posts', 'jquery-pin-it-button-for-images' ); ?></label><br />
		<input type="checkbox" id="show_on_category"	name="jpibfi_selection_options[show_on_category]" <?php checked( true, $show_on_category ); ?> value="1" />
		<label for="show_on_category"><?php _e( 'Category and archive pages', 'jquery-pin-it-button-for-images' ); ?></label><br />
		<input type="checkbox" id="show_on_blog"	name="jpibfi_selection_options[show_on_blog]" <?php checked( true, $show_on_blog ); ?> value="1" />
		<label for="show_on_blog"><?php _e( 'Blog pages', 'jquery-pin-it-button-for-images' ); ?></label>

		<?php
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function min_image_callback( $args ) {
		$options = $this->get_selection_options();

		$min_image_height = $options[ 'min_image_height' ];
		$min_image_width = $options[ 'min_image_width' ];
		?>

		<p>
			<label for="min_image_height"><?php _e('Height', 'jquery-pin-it-button-for-images'); ?></label>
			<input type="number" min="0" step="1" id="min_image_height" name="jpibfi_selection_options[min_image_height]" value="<?php echo $min_image_height; ?>"
						 class="small-text" /> px
			<?php echo JPIBFI_Admin_Utilities::create_errors( 'min_image_height' ); ?>
		</p>

		<p>
			<label for="min_image_width"><?php _e('Width', 'jquery-pin-it-button-for-images'); ?></label>
			<input type="number" min="0" step="1" id="min_image_width" name="jpibfi_selection_options[min_image_width]" value="<?php echo $min_image_width; ?>"
						 class="small-text" /> px
			<?php echo JPIBFI_Admin_Utilities::create_errors( 'min_image_width' ); ?>
		</p>

		<?php

		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function sanitize_selection_options( $input ) {
		$jpibfi_selection_options = $this->get_options();

		foreach( $input as $key => $value ) {
			switch($key) {
				case 'disabled_classes':
				case 'enabled_classes':
					if ( false == JPIBFI_Admin_Utilities::contains_css_class_names_or_empty( $input [ $key ] ) ) {

						$field = '';
						if ( 'disabled_classes' == $key )
							$field = __( 'Disabled classes', 'jquery-pin-it-button-for-images' );
						else if ( 'enabled_classes' == $key )
							$field = __( 'Enabled classes', 'jquery-pin-it-button-for-images' );

						add_settings_error(
							$key,
							esc_attr( 'settings_updated' ),
								$field . ' - ' . __('the given value doesn\'t meet the requirements. Please correct it and try again.', 'jquery-pin-it-button-for-images')
						);
					}
					break;
				case 'min_image_height':
				case 'min_image_width':
					if ( !is_numeric( $value ) || $value < 0 ) {

						$field = '';
						if ( 'min_image_height' == $key )
							$field = __( 'Minimum image height', 'jquery-pin-it-button-for-images' );
						else if ( 'min_image_width' == $key )
							$field = __( 'Minimum image width', 'jquery-pin-it-button-for-images' );

						add_settings_error(
							$key,
							esc_attr( 'settings_updated' ),
								$field . ' - ' . sprintf ( __('value must be a number greater or equal to %d.', 'jquery-pin-it-button-for-images'), '0' )
						);
					}
					break;
			}

		}

		$checkbox_settings = $this->get_checkbox_settings();
		foreach($checkbox_settings as $setting_name){
			if (false == array_key_exists( $setting_name, $input) ){
				$input[ $setting_name ] = '0';
			}
		}

		$errors = get_settings_errors();

		if ( count( $errors ) > 0 ) {

			update_option( $this->get_option_name() . '_errors', $input );
			return $jpibfi_selection_options;

		} else {

			delete_option( $this->get_option_name() . '_errors' );
			return $input;

		}
	}

	private function get_selection_options() {
		$jpibfi_selection_options = $this->get_options();

		if ( null == $this->admin_selection_options ) {
			//cumbersome, but works in WP 3.3
			$options = get_option( $this->get_option_name() . '_errors' );
			$this->admin_selection_options = false == $options ? $jpibfi_selection_options : $options;
		}

		return $this->admin_selection_options;
	}
}

add_action( 'plugins_loaded', array( 'JPIBFI_Selection_Options', 'get_instance' ) );