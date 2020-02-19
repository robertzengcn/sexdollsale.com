<?php

class JPIBFI_Visual_Options extends JPIBFI_Options {
	protected static $instance = null;

	protected $admin_visual_options = null;

	private function __construct() {
		add_action( 'admin_init', array( $this, 'initialize_visual_options' ) );

	}

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function get_visual_options() {
        $db_options = $this->get_options();

		if ( null == $this->admin_visual_options ) {
			//cumbersome, but works in WP 3.3
			$options = get_option( $this->get_option_name() . '_errors' );
			$this->admin_visual_options = false == $options ? $db_options : $options;
		}

		return $this->admin_visual_options;
	}

    function get_default_options(){
        $defaults = array(
            'button_margin_bottom' => '20',
            'button_margin_top'	=> '20',
            'button_margin_right' => '20',
            'button_margin_left' => '20',
            'button_position'	=> '0',
            'custom_image_url'    => '',
            'custom_image_height' => '0',
            'custom_image_width'  => '0',
            'description_option'  => '1',
            'pinLinkedImages' => '0',
            'pinLinkedImagesExtensions' => '',
            'retina_friendly' => '0',
            'show_button' => 'hover',
            'transparency_value'  => '0.5',
            'use_custom_image'    => '0',
            'use_post_url'  => '0'
        );

        return apply_filters( 'jpibfi_default_visual_options', $defaults );
    }

    function get_option_name(){
        return 'jpibfi_visual_options';
    }

	function get_checkbox_settings() {
		return array(
            'pinLinkedImages',
			'use_post_url',
			'retina_friendly',
			'use_custom_image'
		);
	}

	/* Defines visual options section and defines all required fields */
	public function initialize_visual_options() {

		add_settings_section(
			'visual_options_section',			// ID used to identify this section and with which to register options
			__( 'Visual', 'jquery-pin-it-button-for-images' ),		// Title to be displayed on the administration page
			array( $this, 'visual_options_callback' ),	// Callback used to render the description of the section
			'jpibfi_visual_options'		// Page on which to add this section of options
		);

        add_settings_field(
            'show_button',
            __( 'Show button', 'jquery-pin-it-button-for-images' ),
            array( $this, 'show_button_callback' ),
            'jpibfi_visual_options',
            'visual_options_section',
            array(
                __( 'When the "Pin it" button should be visible.', 'jquery-pin-it-button-for-images' ),
            )
        );

		add_settings_field(
			'description_option',
			__( 'Description source', 'jquery-pin-it-button-for-images' ),
			array( $this, 'description_option_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				__( 'From where the Pinterest message should be taken. Please note that "Image description" works properly only for images that were added to your Media Library.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'use_post_url',
			__( 'Linked page', 'jquery-pin-it-button-for-images' ),
			array( $this, 'use_post_url_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				__( 'When checked, the link on Pinterest will always point to the individual page with the image and title of this individual page will be used if you\'ve selected Title as the description source, even when the image was pinned on an archive page, category page or homepage. If false, the link will point to the URL the user is currently on.', 'jquery-pin-it-button-for-images' ),
			)
		);

        add_settings_field(
            'pin_linked_images',
            __( 'Pin linked images', 'jquery-pin-it-button-for-images' ),
            array( $this, 'pin_linked_images_callback' ),
            'jpibfi_visual_options',
            'visual_options_section',
            array(
            )
        );

		add_settings_field(
			'transparency_value',
			__( 'Transparency value', 'jquery-pin-it-button-for-images' ),
			array( $this, 'transparency_value_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				__( 'This setting sets the transparency of the image.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'custom_pin_it_button',
			__( 'Custom "Pin It" button', 'jquery-pin-it-button-for-images' ),
			array( $this, 'custom_pin_it_button_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				__( 'Check the <b>Use custom image</b> checkbox, specify image\'s URL, height and width to use your own Pinterest button design. You can just upload an image using Wordpress media library if you wish.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'pin_it_button_position',
			__( '"Pin it" button position', 'jquery-pin-it-button-for-images' ),
			array( $this, 'pin_it_button_position_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				__( 'Where the "Pin it" button should appear on the image.', 'jquery-pin-it-button-for-images' ),
			)
		);

		add_settings_field(
			'pin_it_button_margins',
			__( '"Pin it" button margins', 'jquery-pin-it-button-for-images' ),
			array( $this, 'pin_it_button_margins_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				sprintf( __( 'Margins are used to adjust the position of the "Pin it" button, but not all margins are used on all button positions. Here is an example. If you\'re using the "%s" position, the button\'s position will be affected only by top and left margins. Bottom and right margins affect "%s" position, etc. The "%s" position does not use any margins at all.', 'jquery-pin-it-button-for-images' ),
					__( 'Top left', 'jquery-pin-it-button-for-images' ),
					__( 'Bottom right', 'jquery-pin-it-button-for-images' ),
					__( 'Middle', 'jquery-pin-it-button-for-images' )
				),
			)
		);

		add_settings_field(
			'retina_friendly',
			__( 'Retina friendly', 'jquery-pin-it-button-for-images' ),
			array( $this, 'retina_friendly_callback' ),
			'jpibfi_visual_options',
			'visual_options_section',
			array(
				__( 'Please note that checking this option will result in rendering the "Pin it" button half of its normal size (if you use a 80x60 image, the button will be 40x30). When uploading a custom "Pin it" button (the default one is too small), please make sure both width and height are even numbers (i.e. divisible by two) when using this option.', 'jquery-pin-it-button-for-images' ),
			)
		);

		register_setting(
			'jpibfi_visual_options',
			'jpibfi_visual_options',
			array( $this, 'sanitize_visual_options' )
		);
	}

	public function visual_options_callback() {
		echo '<p>' . __('How it should look like', 'jquery-pin-it-button-for-images') . '</p>';
	}

    public function show_button_callback($args){
        $options = $this->get_visual_options();
        $show_button = 'hover';
        ?>
        <select id="show_button" name="jpibfi_visual_options[show_button]">
            <option value="hover" <?php selected ( "hover", $show_button ); ?>><?php _e( 'On hover', 'jquery-pin-it-button-for-images' ); ?></option>
            <option value="always_touch" <?php selected ( "always_touch", $show_button ); ?>><?php _e( 'Always on touch devices', 'jquery-pin-it-button-for-images' ); ?></option>
            <option value="always" <?php selected ( "always", $show_button ); ?>><?php _e( 'Always', 'jquery-pin-it-button-for-images' ); ?></option>
        </select>
        <?php
        ?>
        <div id="show_button_error">
            <p><strong><?php echo sprintf(__('This feature is disabled in this version of the plugin. Consider buying <a target="_blank" href="%s">the PRO version</a>.', 'jquery-pin-it-button-for-images'), 'http://mrsztuczkens.me/jpibfi-pro/'); ?></strong></p>
        </div>
        <?php
        echo JPIBFI_Admin_Utilities::create_description( $args[0] );
    }

	public function description_option_callback( $args ) {
		$options = $this->get_visual_options();

		$description_option = $options[ 'description_option' ];
		?>

		<select id="description_option" name="jpibfi_visual_options[description_option]">
			<option value="1" <?php selected ( "1", $description_option ); ?>><?php _e( 'Page title', 'jquery-pin-it-button-for-images' ); ?></option>
			<option value="2" <?php selected ( "2", $description_option ); ?>><?php _e( 'Page description', 'jquery-pin-it-button-for-images' ); ?></option>
			<option value="3" <?php selected ( "3", $description_option ); ?>><?php _e( 'Picture title or (if title not available) alt attribute', 'jquery-pin-it-button-for-images' ); ?></option>
			<option value="4" <?php selected ( "4", $description_option ); ?>><?php _e( 'Site title (Settings->General)', 'jquery-pin-it-button-for-images' ); ?></option>
			<option value="5" <?php selected ( "5", $description_option ); ?>><?php _e( 'Image description', 'jquery-pin-it-button-for-images' ); ?></option>
			<option value="6" <?php selected ( "6", $description_option ); ?>><?php _e( 'Image alt attribute', 'jquery-pin-it-button-for-images' ); ?></option>
		</select>

		<?php
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function use_post_url_callback( $args ) {

		$options = $this->get_visual_options();
		$use_post_url = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'use_post_url', '1' );

		echo '<input type="checkbox" id="use_post_url" name="jpibfi_visual_options[use_post_url]" value="1" ' . checked( true, $use_post_url, false ) . '>';
		echo '<label for="use_post_url">' . __( 'Always link to individual post page', 'jquery-pin-it-button-for-images' ) . '</label>';

		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

    public function pin_linked_images_callback( $args){
        $options = $this->get_visual_options();

        $pin_linked_images = $options['pinLinkedImages']== '1';
        $pin_linked_images_extensions = $options['pinLinkedImagesExtensions'];

        echo '<input type="checkbox" id="pinLinkedImages" name="jpibfi_visual_options[pinLinkedImages]" value="1" ' . checked( true, $pin_linked_images, false ) . '>';
        echo '<label for="pinLinkedImages">' . __( 'Active', 'jquery-pin-it-button-for-images' ) . '</label>';
        echo JPIBFI_Admin_Utilities::create_description(__( 'When checked, pins full-sized images instead of thumbnails (works only if you link thumbnails to their full versions).', 'jquery-pin-it-button-for-images' ));
        echo '<p><label for="pinLinkedImagesExtensions">' . __( 'File extensions to use', 'jquery-pin-it-button-for-images' ) . '</label> ';
        echo '<input type="text" id="pinLinkedImagesExtensions" name="jpibfi_visual_options[pinLinkedImagesExtensions]" value="' . $pin_linked_images_extensions . '"></p>';
        echo JPIBFI_Admin_Utilities::create_description( __('Leaving this empty means files of any extension will be used (also those without any extension). If you want to use only files of specific extension(s), type those file extensions here (separated by commas).', 'jquery-pin-it-button-for-images') );
    }

	public function transparency_value_callback( $args ) {
		$options = $this->get_visual_options();

		$transparency_value = $options[ 'transparency_value' ];

		echo '<label for="transparency_value">' . sprintf ( __('Choose transparency (between %.02f and %.02f)', 'jquery-pin-it-button-for-images'), '0.00', '1.00' ) . '</label><br/>';
		echo '<input type="number" min="0" max="1" step="0.01" id="transparency_value" name="jpibfi_visual_options[transparency_value]"' .
				'value="' . $transparency_value . '" class="small-text" >';
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
		echo JPIBFI_Admin_Utilities::create_errors( 'transparency_value' );
	}

	public function custom_pin_it_button_callback( $args ) {
		$options = $this->get_visual_options();

		$use_custom_image = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'use_custom_image', '1' );
		$custom_image_url = $options[ 'custom_image_url' ];
		$custom_image_height = $options[ 'custom_image_height' ];
		$custom_image_width = $options[ 'custom_image_width' ];

		?>
		<p>
			<input type="checkbox" id="use_custom_image" name="jpibfi_visual_options[use_custom_image]" value="1" <?php checked( true, $use_custom_image ); ?> >
			<label class="chbox-label" for="use_custom_image"><?php _e( 'Use custom image', 'jquery-pin-it-button-for-images' ); ?></label>
		</p>

		<button id=upload-image><?php _e( 'Upload an image using media library','jquery-pin-it-button-for-images' ); ?></button><br />

		<p>
			<label for="custom_image_url"><?php _e( 'URL address of the image', 'jquery-pin-it-button-for-images' ); ?></label>
			<input type="url" id="custom_image_url" name="jpibfi_visual_options[custom_image_url]" value="<?php echo $custom_image_url; ?>">
		</p>

		<p>
			<label for="custom_image_height"><?php _e( 'Height', 'jquery-pin-it-button-for-images' ); ?></label>
			<input type="number" min="0" step="1" id="custom_image_height" name="jpibfi_visual_options[custom_image_height]" value="<?php echo $custom_image_height; ?>"
						 class="small-text" /> px
			<?php echo JPIBFI_Admin_Utilities::create_errors( 'custom_image_height' ); ?>
		</p>

		<p>
			<label for="custom_image_width"><?php _e( 'Width', 'jquery-pin-it-button-for-images' ); ?></label>
			<input type="number" min="0" step="1" id="custom_image_width" name="jpibfi_visual_options[custom_image_width]" value="<?php echo $custom_image_width; ?>"
						 class="small-text"  /> px
			<?php echo JPIBFI_Admin_Utilities::create_errors( 'custom_image_width' ); ?>
		</p>

		<p>
			<b><?php _e( 'Custom Pin It button preview', 'jquery-pin-it-button-for-images' ); ?></b><br/>
		<span id="custom_button_preview" style="width: <?php echo $custom_image_width; ?>px; height: <?php echo $custom_image_height; ?>px; background-image: url('<?php echo $custom_image_url; ?>');">
			Preview
		</span><br/>
			<button id="refresh_custom_button_preview"><?php _e( 'Refresh preview', 'jquery-pin-it-button-for-images' ); ?></button>
		</p>

		<?php

		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function pin_it_button_position_callback( $args ) {
		$options = $this->get_visual_options();

		$jpibfi_button_dropdown = array(
			__( 'Top left', 'jquery-pin-it-button-for-images' ),
			__( 'Top right', 'jquery-pin-it-button-for-images' ),
			__( 'Bottom left', 'jquery-pin-it-button-for-images' ),
			__( 'Bottom right', 'jquery-pin-it-button-for-images' ),
			__( 'Middle', 'jquery-pin-it-button-for-images' )
		);

		$button_position = $options[ 'button_position' ];

		?>

		<select name="jpibfi_visual_options[button_position]" id="button_position">
			<?php for( $i = 0; $i < count( $jpibfi_button_dropdown ); $i++) { ?>
				<option value="<?php echo $i; ?>" <?php selected( $i, $button_position ); ?>><?php echo $jpibfi_button_dropdown[ $i ]; ?></option>
			<?php } ?>
		</select><br/>

		<?php
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function pin_it_button_margins_callback( $args ) {
		$options = $this->get_visual_options();
		?>
		<label for="button_margin_top"><?php _e('Top', 'jquery-pin-it-button-for-images'); ?></label>
		<input type="number" min="-1000" max="1000" step="1" id="button_margin_top" name="jpibfi_visual_options[button_margin_top]" value="<?php echo $options[ 'button_margin_top' ]; ?>" class="small-text" >px<br/>
		<label for="button_margin_bottom"><?php _e('Bottom', 'jquery-pin-it-button-for-images'); ?></label>
		<input type="number" min="-1000" max="1000" step="1" id="button_margin_bottom" name="jpibfi_visual_options[button_margin_bottom]" value="<?php echo $options[ 'button_margin_bottom' ]; ?>" class="small-text" >px<br/>
		<label for="button_margin_left"><?php _e('Left', 'jquery-pin-it-button-for-images'); ?></label>
		<input type="number" min="-1000" max="1000" step="1" id="button_margin_left" name="jpibfi_visual_options[button_margin_left]" value="<?php echo $options[ 'button_margin_left' ]; ?>" class="small-text" >px<br/>
		<label for="button_margin_right"><?php _e('Right', 'jquery-pin-it-button-for-images'); ?></label>
		<input type="number" min="-1000" max="1000" step="1" id="button_margin_right" name="jpibfi_visual_options[button_margin_right]" value="<?php echo $options[ 'button_margin_right' ]; ?>" class="small-text" >px<br/>

		<?php
		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function retina_friendly_callback( $args ) {

		$options = $this->get_visual_options();
		$retina_friendly = JPIBFI_Admin_Utilities::exists_and_equal_to( $options, 'retina_friendly', '1' );

		echo '<input type="checkbox" id="retina_friendly" name="jpibfi_visual_options[retina_friendly]" value="1" ' . checked( true, $retina_friendly, false ) . '>';
		echo '<label for="retina_friendly">' . __( 'Optimize for high pixel density displays', 'jquery-pin-it-button-for-images' ) . '</label>';

		echo JPIBFI_Admin_Utilities::create_description( $args[0] );
	}

	public function sanitize_visual_options( $input ) {
		$jpibfi_visual_options = $this->get_options();

		foreach( $input as $key => $value ) {

			switch($key) {
                case 'custom_image_height':
                case 'custom_image_width':
                    $name = "";
                    if ( 'custom_image_height' == $key )
                        $name = __('Custom image height', 'jquery-pin-it-button-for-images' );
                    else if ( 'custom_image_width' == $key )
                        $name = __('Custom image width', 'jquery-pin-it-button-for-images' );

                    if ( '' != $value && ( !is_numeric( $value ) || $value < 0 ) ) {
                        add_settings_error(
                            $key,
                            esc_attr( 'settings_updated' ),
                            $name . ' - ' . sprintf ( __('value must be a number greater or equal to %d.', 'jquery-pin-it-button-for-images'), '0' )
                        );
                    }
                    break;
				case 'transparency_value':
					if ( !is_numeric( $input[ $key ] ) || ( $input[ $key ] < 0.0 ) || ( $input[ $key ] > 1.0 ) ) {

						add_settings_error(
							$key,
							esc_attr( 'settings_updated' ),
							sprintf( __('Transparency value must be a number between %.02d and %.02f.', 'jquery-pin-it-button-for-images'), '0.00', '1.00' )
						);
					}
					break;
                case 'pinLinkedImagesExtensions':
                    $options[ $key ] = esc_attr( $value );
                    break;
			}
		}

        $defaults = $this->get_default_options();
        $input['show_button'] = $defaults['show_button'];

		$checkbox_settings = $this->get_checkbox_settings();
		foreach($checkbox_settings as $setting_name){
			if (false == array_key_exists( $setting_name, $input) )
				$input[ $setting_name ] = '0';
		}

		$errors = get_settings_errors();

		if ( count( $errors ) > 0 ) {

			update_option( $this->get_option_name() . '_errors', $input );
			return $jpibfi_visual_options;

		} else {

			delete_option( $this->get_option_name() . '_errors' );
			return $input;

		}
	}
}

add_action( 'plugins_loaded', array( 'JPIBFI_Visual_Options', 'get_instance' ) );