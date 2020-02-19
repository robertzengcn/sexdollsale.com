<?php

class JPIBFI_Client {

	protected static $instance = null;
    private $image_width = 65;
    private $image_height = 41;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_plugin_scripts' ) );
		add_action( 'wp_head', array( $this, 'print_header_style' ) );
		add_filter( "the_content", array( $this, 'prepare_the_content' ), 9999 );
		add_filter( "the_excerpt", array( $this, 'prepare_the_content' ), 9999 );
	}

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	//Adds all necessary scripts
	public function add_plugin_scripts() {
        $jpibfi_adanced_options = JPIBFI_Advanced_Options::get_instance()->get_options();
        $jpibfi_selection_options = JPIBFI_Selection_Options::get_instance()->get_options();
        $jpibfi_visual_options = JPIBFI_Visual_Options::get_instance()->get_options();

		if ( ! ( JPIBFI_Client_Utilities::add_jpibfi() ) )
			return;
		wp_enqueue_script( 'jquery-pin-it-button-script', JPIBFI_Globals::get_plugin_url() . 'js/jpibfi.js', array( 'jquery' ), JPIBFI_Globals::get_file_version(), false );

		$use_custom_image = $jpibfi_visual_options[ 'use_custom_image' ] == "1";

		$parameters_array = array(
			'imageSelector' 	=> $jpibfi_selection_options['image_selector'],
			'disabledClasses' 	=> $jpibfi_selection_options['disabled_classes'],
			'enabledClasses' 	=> $jpibfi_selection_options['enabled_classes'],
			'descriptionOption' => $jpibfi_visual_options['description_option'],
			'usePostUrl' 		=> $jpibfi_visual_options['use_post_url'],
			'minImageHeight'	=> $jpibfi_selection_options['min_image_height'],
			'minImageWidth'		=> $jpibfi_selection_options['min_image_width'],
			'siteTitle'			=> get_bloginfo( 'name', 'display' ),
			'buttonPosition'	=> $jpibfi_visual_options[ 'button_position' ],
			'debug'				=> $jpibfi_adanced_options[ 'debug'],
			'containerSelector' => $jpibfi_adanced_options[ 'container_selector'],
			'pinImageHeight' 	=> $use_custom_image ? $jpibfi_visual_options['custom_image_height'] : $this->image_height,
			'pinImageWidth'		=> $use_custom_image ? $jpibfi_visual_options['custom_image_width'] : $this->image_width,
			'buttonMarginTop'	=> $jpibfi_visual_options[ 'button_margin_top' ],
			'buttonMarginBottom'=> $jpibfi_visual_options[ 'button_margin_bottom' ],
			'buttonMarginLeft'   => $jpibfi_visual_options[ 'button_margin_left' ],
			'buttonMarginRight'	=> $jpibfi_visual_options[ 'button_margin_right' ],
			'retinaFriendly'    => $jpibfi_visual_options[ 'retina_friendly' ] == '1' ? '1' : '0',
            'showButton'        => $jpibfi_visual_options['show_button'],
            'pinLinkedImages'   => $jpibfi_visual_options['pinLinkedImages'] == '1',
            'pinLinkedImagesExtensions' => $jpibfi_visual_options['pinLinkedImagesExtensions'],
		);

		wp_localize_script( 'jquery-pin-it-button-script', 'jpibfi_options', apply_filters( 'jpibfi_javascript_parameters', $parameters_array ) );
	}

	public function print_header_style() {
        $jpibfi_visual_options = JPIBFI_Visual_Options::get_instance()->get_options();

		if ( ! ( JPIBFI_Client_Utilities::add_jpibfi() ) )
			return;

		$use_custom_image = $jpibfi_visual_options[ 'use_custom_image' ] == "1";

		$width  = $use_custom_image ? $jpibfi_visual_options['custom_image_width'] : $this->image_width;
		$height = $use_custom_image ? $jpibfi_visual_options['custom_image_height'] : $this->image_height;

		if ( $jpibfi_visual_options[ 'retina_friendly' ] == '1' ){
			$width = floor( $width / 2 );
			$height = floor ( $height / 2 );
		}

		$url = $use_custom_image ? $jpibfi_visual_options['custom_image_url'] : JPIBFI_Globals::get_plugin_url() . 'images/pinit-button.png';
        ob_start();
		?>
		<style type="text/css">
			a.pinit-button {
                position:absolute;
                text-indent:-9999em !important;
				width: <?php echo $width; ?>px !important;
				height: <?php echo $height; ?>px !important;
				background: transparent url('<?php echo $url; ?>') no-repeat 0 0 !important;
				background-size: <?php echo $width; ?>px <?php echo $height; ?>px !important;
			}

			img.pinit-hover {
				opacity: <?php echo (1 - $jpibfi_visual_options['transparency_value']); ?> !important;
				filter:alpha(opacity=<?php echo (1 - $jpibfi_visual_options['transparency_value']) * 100; ?>) !important; /* For IE8 and earlier */
			}
		</style>
        
	   <?php
       $what = "\\x00-\\x20";
       echo  trim( preg_replace( "/[".$what."]+/" , ' ' , ob_get_clean() ) , $what );
	}

	/*
 * Adds a hidden field with url and and description of the pin that's used when user uses "Link to individual page"
 * Thanks go to brocheafoin, who added most of the code that handles creating description
 */
	public function prepare_the_content( $content ) {
		if ( ! JPIBFI_Client_Utilities::add_jpibfi() )
			return $content;

        $jpibfi_visual_options = JPIBFI_Visual_Options::get_instance()->get_options();

		global $post;

		$add_attributes = false == is_singular() && '1' == $jpibfi_visual_options[ 'use_post_url' ];

		$attributes_html = '';

		//if we need to add additional attributes to handle use_post_url setting
		if ( $add_attributes ){
			//if page description should be used as pin description and an excerpt for the post exists
			if ( has_excerpt( $post->ID ) && 2 == $jpibfi_visual_options[ 'description_option' ] )
				$description = wp_kses( $post->post_excerpt, array() );
			else
				$description = get_the_title($post->ID);

			$attributes_html .= 'data-jpibfi-url="' . get_permalink( $post->ID ) . '" ' ;
			$attributes_html .= 'data-jpibfi-description ="' . esc_attr( $description ) . '" ';
		}

		$input_html = '<input class="jpibfi" type="hidden" ' . $attributes_html . '>';
		$content = $input_html . $content;

		$add_image_descriptions = '5' == $jpibfi_visual_options[ 'description_option' ];

		//if we need to add data-jpibfi-description to each image
		if ( $add_image_descriptions ){
			$content = $this->add_description_attribute_to_images( $content );
		}

		return $content;
	}

	/* PRIVATE METHODS */

	/*
 * Adds data-jpibfi-description attribute to each image that is added through media library. The value is the "Description"  of the image from media library.
 * This piece of code uses a lot of code from the Photo Protect http://wordpress.org/plugins/photo-protect/ plugin
 */
	private function add_description_attribute_to_images( $content ) {

		$imgPattern = '/<img[^>]*>/i';
		$attrPattern = '/ ([\w]+)[ ]*=[ ]*([\"\'])(.*?)\2/i';

		preg_match_all($imgPattern, $content, $images, PREG_SET_ORDER);

		foreach ($images as $img) {

			preg_match_all($attrPattern, $img[0], $attributes, PREG_SET_ORDER);

			$newImg = '<img';
			$src = '';
			$id = '';

			foreach ($attributes as $att) {
				$full = $att[0];
				$name = $att[1];
				$value = $att[3];

				$newImg .= $full;

				if ('class' == $name ) {
					$id = JPIBFI_Client_Utilities::get_post_id_from_image_classes( $value );
				}	else if ( 'src' == $name ) {
					$src = $value;
				}
			}

			$description = JPIBFI_Client_Utilities::get_image_description( $id, $src );
			$newImg .= ' data-jpibfi-description="' . esc_attr( $description ) . '" />';
			$content = str_replace($img[0], $newImg, $content);
		}

		return $content;
	}
}

add_action( 'plugins_loaded', array( 'JPIBFI_Client', 'get_instance' ) );