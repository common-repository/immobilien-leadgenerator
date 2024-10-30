<?php
/**
 * Main Class file for `WP_OSA`
 *
 * Main class that deals with all other classes.
 *
 * @since 	1.0.0
 * @package WPOSA
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * WP_OSA.
 *
 * WP Settings API Class.
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'WP_OSA' ) ) :

class WP_OSA {

	/**
	 * Sections array.
	 *
	 * @var 	array
	 * @since 	1.0.0
	 */
	private $sections_array = array();

	/**
	 * Fields array.
	 *
	 * @var 	array
	 * @since 	1.0.0
	 */
	public $fields_array = array();

	public $title_desc;

	public $options;

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct($options = null) {
		if(empty($options)) $this->options = array();
		else $this->options = $options;

		// Enqueue the admin scripts.
	    add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

	    // Hook it up.
	    add_action( 'admin_init', array( $this, 'admin_init' ) );

	}

	/**
	 * Admin Scripts.
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts() {
		// jQuery is needed.
		wp_enqueue_script( 'jquery' );

		// Color Picker
		wp_enqueue_style( 'wp-color-picker' );

		// Color Picker
		wp_enqueue_script( 'wp-color-picker' );

		// Media Uploader.
		wp_enqueue_media();
	}

	public function set_title_desc($title_desc) {
		$this->title_desc = $title_desc;
	}


	/**
	 * Set Sections.
	 *
	 * @param array   $sections
	 * @since 1.0.0
	 */
	public function set_sections( $sections ) {
		// Bail if not array.
		if ( ! is_array( $sections ) ) {
			return false;
		}

		// Assign to the sections array.
		$this->sections_array = $sections;

		return $this;
	}


	/**
	 * Add a single section.
	 *
	 * @param array   $section
	 * @since 1.0.0
	 */
	public function add_section( $section ) {
		// Bail if not array.
		if ( ! is_array( $section ) ) {
			return false;
		}

		// Assign the section to sections array.
		$this->sections_array[] = $section;

		return $this;
	}


	/**
	 * Set Fields.
	 *
	 * @since 1.0.0
	 */
	public function set_fields( $fields ) {
		// Bail if not array.
		if ( ! is_array( $fields ) ) {
			return false;
		}

		// Assign the fields.
		$this->fields_array = $fields;

		return $this;
	}



	/**
	 * Add a single field.
	 *
	 * @since 1.0.0
	 */
	public function add_field( $section, $field_array ) {
		// Set the defaults
		$defaults = array(
			'id'   => '',
			'name' => '',
			'desc' => '',
			'type' => 'text',
			'placeholder' => ''
		);

		// Combine the defaults with user's arguements.
		$arg = wp_parse_args( $field_array, $defaults );

		// Each field is an array named against its section.
		$this->fields_array[ $section ][] = $arg;

		return $this;
	}

	/**
	 * Displays a seperator tag (<hr/>)
	 */
	public function add_seperator($section) {
		$args = array(
			'id'   => 'seperator-'.rand(),
			'type' => 'seperator',
			'name' => ''
		);
		$this->fields_array[ $section ][] = $args;
	}

	/**
	 * Initialize API.
	 *
	 * Initializes and registers the settings sections and fields.
	 * Usually this should be called at `admin_init` hook.
	 *
	 * @since  1.0.0
	 */
	function admin_init() {
	    /**
	     * Register the sections.
	     *
	     * Sections array is like this:
		 *
		 * 		$sections_array = array (
		 * 			$section_array,
		 * 			$section_array,
		 * 			$section_array,
		 * 		);
		 *
		 * Section array is like this:
		 *
		 * 		$section_array = array (
		 * 			'id' 	=> 'section_id',
		 * 			'title' => 'Section Title'
		 * 		);
		 *
		 *
	     * @since 1.0.0
	     */
	    foreach ( $this->sections_array as $section ) {
	        if ( false == get_option( $section['id'] ) ) {
	            // Add a new field as section ID.
	            add_option( $section['id'] );
	        }

	        // Deals with sections description.
	        if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
	        	// Build HTML.
	            $section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';

	            // Create the callback for description.
	            $callback = create_function( '', 'echo "' . str_replace( '"', '\"', $section['desc'] ) . '";' );

	        } elseif ( isset( $section['callback'] ) ) {
	            $callback = $section['callback'];
	        } else {
	            $callback = null;
	        }


	        /**
	         * Add a new section to a settings page.
	         *
	         * @param string 	$id
	         * @param string 	$title
	         * @param callable 	$callback
	         * @param string 	$page 		| Page is same as sectipn ID.
	         * @since 1.0.0
	         */
	        add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
	    } // foreach ended.


		/**
		 * Register settings fields.
		 *
		 * Fields array is like this:
		 *
		 * 		$fields_array = array (
		 * 			$section => $field_array,
		 * 			$section => $field_array,
		 * 			$section => $field_array,
		 * 		);
		 *
		 *
		 * Field array is like this:
		 *
		 * 		$field_array = array (
		 * 			'id' 	=> 'id',
		 * 			'name' 	=> 'Name',
		 * 			'type'	=> 'text'
		 * 		);
		 *
	     * @since 1.0.0
		 */
	    foreach ( $this->fields_array as $section => $field_array ) {
	        foreach ( $field_array as $field ) {
				// ID.
				$id                = isset( $field['id'] ) ? $field['id'] : false;

				// Type.
				$type              = isset( $field['type'] ) ? $field['type'] : 'text';

				// Name.
				$name              = isset( $field['name'] ) ? $field['name'] : 'No Name Added';

				// Label for.
				$label_for         = "{$section}[{$field['id']}]";

				// Description.
				$description       = isset( $field['desc'] ) ? $field['desc'] : '';

				// Size.
				$size              = isset( $field['size'] ) ? $field['size'] : null;

				// Options.
				$options           = isset( $field['options'] ) ? $field['options'] : '';

				// Standard default value.
				$default           = isset( $field['default'] ) ? $field['default'] : '';
				
				// Standard default value.
				$placeholder       = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
				
				// Standard default value.
				$license     	    = isset( $field['license'] ) ? $field['license'] : '';

				// Standard default value.
				$disabled     	    = isset( $field['disabled'] ) ? $field['disabled'] : '';

				// Standard default value.
				$href		       = isset( $field['href'] ) ? $field['href'] : '';

				// Sanitize Callback.
				$sanitize_callback = isset( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : '';

	            $args = array(
					'id'                => $id,
					'type'              => $type,
					'name'              => $name,
					'label_for'         => $label_for,
					'desc'              => $description,
					'section'           => $section,
					'size'              => $size,
					'options'           => $options,
					'std'               => $default,
					'href'				=> $href,
					'placeholder'		=> $placeholder,
					'license'			=> $license,
					'disabled'			=> $disabled,
					'sanitize_callback' => $sanitize_callback
	            );

	            /**
	             * Add a new field to a section of a settings page.
	             *
	             * @param string 	$id
	             * @param string 	$title
	             * @param callable 	$callback
	             * @param string 	$page
	             * @param string 	$section = 'default'
	             * @param array 	$args = array()
	             * @since 1.0.0
	             */

	            // @param string 	$id
	            $field_id = $section . '[' . $field['id'] . ']';

	            add_settings_field(
	            	$field_id,
	            	$name,
	            	array( $this, 'callback_' . $type ),
	            	$section,
	            	$section,
	            	$args
	            );
	        } // foreach ended.
	    } // foreach ended.


	    // Creates our settings in the fields table.
	    foreach ( $this->sections_array as $section ) {
	    	/**
	    	 * Registers a setting and its sanitization callback.
	    	 *
	    	 * @param string 	$field_group 	| A settings group name.
	    	 * @param string 	$field_name 	| The name of an option to sanitize and save.
	    	 * @param callable 	$sanitize_callback = ''
	    	 * @since 1.0.0
	    	 */
	        register_setting( $section['id'], $section['id'], array( $this, 'sanitize_fields' ) );
	    } // foreach ended.

	} // admin_init() ended.


	/**
	 * Sanitize callback for Settings API fields.
	 *
	 * @since 1.0.0
	 */
	public function sanitize_fields( $fields ) {
		foreach( $fields as $field_slug => $field_value ) {
		    $sanitize_callback = $this->get_sanitize_callback( $field_slug );

		    // If callback is set, call it
		    if ( $sanitize_callback ) {
		        $fields[ $field_slug ] = call_user_func( $sanitize_callback, $field_value );
		        continue;
		    }
		}

		return $fields;
	}


	/**
	 * Get sanitization callback for given option slug
	 *
	 * @param string 	$slug option slug
	 * @return mixed 	string | bool 	false
	 * @since  1.0.0
	 */
	function get_sanitize_callback( $slug = '' ) {
	    if ( empty( $slug ) ) {
	        return false;
	    }

	    // Iterate over registered fields and see if we can find proper callback.
	    foreach( $this->fields_array as $section => $field_array ) {
	        foreach ( $field_array as $field ) {
	            if ( $field['name'] != $slug ) {
	                continue;
	            }

	            // Return the callback name
	            return isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ? $field['sanitize_callback'] : false;
	        }
	    }

	    return false;
	}


	/**
	 * Get field description for display
	 *
	 * @param array   $args settings field args
	 */
	public function get_field_description( $args ) {
	    if ( ! empty( $args['desc'] ) ) {
	        $desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
	    } else {
	        $desc = '';
	    }

	    return $desc;
	}


	/**
	 * Displays a text field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_text( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type = isset( $args['type'] ) ? $args['type'] : 'text';
		$placeholder = isset($args['placeholder']) ? $args['placeholder'] : $value;
		$disabled = isset($args['disabled']) && boolval($args['disabled']) ? 'disabled' : '';

	    $html = sprintf( '<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s" placeholder="%6$s" %7$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $disabled);
	    $html .= $this->get_field_description( $args );

	    echo $html;
	}

	function callback_seperator($args) {
		echo "<hr />";
	}

	function callback_button($args) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		?><a href="<?php echo sprintf('%s',$args['href']); ?>"><button class="button button-secondary" type="button"><?php echo $value; ?></button></a><?php
		echo $this->get_field_description( $args );
	}

	/**
	 * Displays a url field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_url( $args ) {
	    $this->callback_text( $args );
	}

	/**
	 * Displays a number field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_number( $args ) {
	    $this->callback_text( $args );
	}

	/**
	 * Displays a checkbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_checkbox( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

	    $html = '<fieldset>';
	    $html .= sprintf( '<label for="wpuf-%1$s[%2$s]">', $args['section'], $args['id'] );
	    $html .= sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
	    $html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked( $value, 'on', false ) );
	    $html .= sprintf( '%1$s</label>', $args['desc'] );
	    $html .= '</fieldset>';

	    echo $html;
	}

	/**
	 * Displays a multicheckbox a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_multicheck( $args ) {

	    $value = $this->get_option( $args['id'], $args['section'], $args['std'] );

		$html = '<fieldset>';
		$html .= sprintf( '<input type="hidden" class="hidden-checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" />', $args['section'], $args['id'], 0 );
	    foreach ( $args['options'] as $key => $label ) {
			$checked = isset( $value[$key] ) ? $value[$key] : '0';
	        $html .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key );
	        $html .= sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked( $checked, $key, false ) );
	        $html .= sprintf( '%1$s</label><br>',  $label );
	    }
	    $html .= $this->get_field_description( $args );
	    $html .= '</fieldset>';

	    echo $html;
	}

	/**
	 * Displays a multicheckbox a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_radio( $args ) {

		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$disabled = isset($args['disabled']) && boolval($args['disabled']) ? 'disabled' : '';

		$html = '<fieldset>';
	    foreach ( $args['options'] as $key => $label ) {
	        $html .= sprintf( '<label for="wpuf-%1$s[%2$s][%3$s]">',  $args['section'], $args['id'], $key );
	        $html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s %5$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ), $disabled );
	        $html .= sprintf( '%1$s</label><br>', $label );
	    }
	    $html .= $this->get_field_description( $args );
	    $html .= '</fieldset>';

	    echo $html;
	}

	/**
	 * Displays a selectbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_select( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	    $html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id'] );
	    foreach ( $args['options'] as $key => $label ) {
	        $html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
	    }
	    $html .= sprintf( '</select>' );
	    $html .= $this->get_field_description( $args );

	    echo $html;
	}

	/**
	 * Displays a textarea for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_textarea( $args ) {

	    $value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	    $html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['section'], $args['id'], $value );
	    $html .= $this->get_field_description( $args );

	    echo $html;
	}

	/**
	 * Displays a textarea for a settings field
	 *
	 * @param array   $args settings field args
	 * @return string
	 */
	function callback_html( $args ) {
	    echo $this->get_field_description( $args );
	}

	/**
	 * Displays a rich text textarea for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_wysiwyg( $args ) {

	    $value = $this->get_option( $args['id'], $args['section'], $args['std'] );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : '500px';

	    echo '<div style="max-width: ' . $size . ';">';

	    $editor_settings = array(
			'wpautop' => false,
			'drag_drop_upload' => true,
	        'teeny' => true,
	        'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
	        'textarea_rows' => 10
	    );
	    if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
	        $editor_settings = array_merge( $editor_settings, $args['options'] );
	    }

	    wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

	    echo '</div>';

	    echo $this->get_field_description( $args );
	}

	/**
	 * Displays a file upload field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_file( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
	    $id = $args['section']  . '[' . $args['id'] . ']';
	    $label = isset( $args['options']['button_label'] ) ?
	                    $args['options']['button_label'] :
	                    __( 'Choose File' );

	    $html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
	    $html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
	    $html .= $this->get_field_description( $args );

	    echo $html;
	}

	/**
	 * Displays an image upload field with a preview
	 *
	 * @param array   $args settings field args
	 */
	function callback_image( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';
	    $id = $args['section']  . '[' . $args['id'] . ']';
	    $label = isset( $args['options']['button_label'] ) ?
	                    $args['options']['button_label'] :
	                    __( 'Choose Image' );

	    $html  = sprintf( '<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
	    $html .= '<input type="button" class="button wpsa-browse" value="' . $label . '" />';
	    $html .= $this->get_field_description( $args );
	    $html .= '<p class="wpsa-image-preview"><img src=""/></p>';

	    echo $html;
	}

	/**
	 * Displays a password field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_password( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	    $html = sprintf( '<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value );
	    $html .= $this->get_field_description( $args );

	    echo $html;
	}

	/**
	 * Displays a color picker field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_color( $args ) {

	    $value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
	    $size = isset( $args['size'] ) && !is_null( $args['size'] ) ? $args['size'] : 'regular';

	    $html = sprintf( '<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std'] );
	    $html .= $this->get_field_description( $args );

	    echo $html;
	}

	/**
	 * Callback for Google Fonts
	 */
	function callback_googlefonts($args) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$html = sprintf('<input id="%2$s" name="%1$s[%2$s]" class="gfontselector" value="%3$s" type="text">', $args['section'], $args['id'], $value);
		$html .= sprintf('<script>var h=$("#%2$s");h.fontselect().change(function(){
					var font = $(this).val().replace(/\+/g, " ");
					font = font.split(":");
					h.attr("value", font[0]);
				});</script>', $args['section'], $args['id']);
		$html .= $this->get_field_description( $args );
		
		echo $html;
	}

	/**
	 * Get the value of a settings field
	 *
	 * @param string  $option  settings field name
	 * @param string  $section the section name this field belongs to
	 * @param string  $default default text if it's not found
	 * @return string
	 */
	function get_option( $option, $section, $default = '' ) {

	    $options = get_option( $section );

	    if ( isset( $options[$option] ) ) {
	        return $options[$option];
	    }

	    return $default;
	}

	/**
	 * Call this function to trigger the WPOSA print
	 */
	public function plugin_page($title = PRT_SETTINGS_TITLE, $desc = null) {
		$this->set_title_desc($desc);
		echo '<div class="wrap">';
	    	echo '<h1>'. $title .' <span style="font-size:50%;">v' . PRT_VERSION . ' - BASIC</span></h1>';
			$this->show_navigation();
			$this->show_forms();
			$this->show_upgrade_notice();
	    echo '</div>';
	}

	public function show_upgrade_notice() {
		?><div class="notice notice-error" id="PRT_RQ_Basic" style="padding: 20px;">
			<strong>UPGRADE:</strong> Um die Bewertungen seitens ImmobilienScout24 zu aktivieren und den PDF Generator nutzen zu können, müssen Sie auf die <b>Professional</b> Version upgraden.
			<br><a href="<?php echo PRT_PRO_LINK; ?>">Mehr Informationen</a>
		</div><?php
	}

	/**
	 * Show navigations as tab
	 *
	 * Shows all the settings section labels as tab
	 */
	function show_navigation() {
	    $html = '<h2 class="nav-tab-wrapper">';

	    foreach ( $this->sections_array as $tab ) {
	        $html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
	    }

	    $html .= '</h2>';

	    echo $html;
	}

	/**
	 * Show the section settings forms
	 *
	 * This function displays every sections in a different form
	 */
	function show_forms() {
		?>
		<style type="text/css">
			/** Google Font Selector */
			.font-select>a,.font-select>a span{overflow:hidden;white-space:nowrap}.font-select{font-size:16px;width:210px;position:relative;display:inline-block;zoom:1}.font-select .fs-drop{background:#fff;border:1px solid #aaa;border-top:0;position:absolute;top:29px;left:0;-webkit-box-shadow:0 4px 5px rgba(0,0,0,.15);-moz-box-shadow:0 4px 5px rgba(0,0,0,.15);-o-box-shadow:0 4px 5px rgba(0,0,0,.15);box-shadow:0 4px 5px rgba(0,0,0,.15);z-index:999}.font-select>a{background-color:#fff;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0,#eee),color-stop(.5,#fff));background-image:-webkit-linear-gradient(center bottom,#eee 0,#fff 50%);background-image:-moz-linear-gradient(center bottom,#eee 0,#fff 50%);background-image:-o-linear-gradient(top,#eee 0,#fff 50%);background-image:-ms-linear-gradient(top,#eee 0,#fff 50%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#ffffff', GradientType=0 );background-image:linear-gradient(top,#eee 0,#fff 50%);-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;-moz-background-clip:padding;-webkit-background-clip:padding-box;background-clip:padding-box;border:1px solid #aaa;display:block;position:relative;height:26px;line-height:26px;padding:0 0 0 8px;color:#444;text-decoration:none}.font-select>a span{margin-right:26px;display:block;line-height:1.8;-o-text-overflow:ellipsis;-ms-text-overflow:ellipsis;text-overflow:ellipsis;cursor:pointer}.font-select>a div{-webkit-border-radius:0 4px 4px 0;-moz-border-radius:0 4px 4px 0;border-radius:0 4px 4px 0;-moz-background-clip:padding;-webkit-background-clip:padding-box;background:#ccc;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0,#ccc),color-stop(.6,#eee));background-image:-webkit-linear-gradient(center bottom,#ccc 0,#eee 60%);background-image:-moz-linear-gradient(center bottom,#ccc 0,#eee 60%);background-image:-o-linear-gradient(bottom,#ccc 0,#eee 60%);background-image:-ms-linear-gradient(top,#ccc 0,#eee 60%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#cccccc', endColorstr='#eeeeee', GradientType=0 );background-image:linear-gradient(top,#ccc 0,#eee 60%);border-left:1px solid #aaa;position:absolute;right:0;top:0;display:block;height:100%;width:18px}.font-select>a div b{background:url(../fs-sprite.png) 0 1px no-repeat;display:block;width:100%;height:100%;cursor:pointer}.font-select .fs-drop{-webkit-border-radius:0 0 4px 4px;-moz-border-radius:0 0 4px 4px;border-radius:0 0 4px 4px;-moz-background-clip:padding;-webkit-background-clip:padding-box;background-clip:padding-box}.font-select .fs-results{margin:0 4px 4px 0;max-height:190px;width:200px;padding:0 0 0 4px;position:relative;overflow-x:hidden;overflow-y:auto}.font-select .fs-results li{line-height:80%;padding:7px 7px 8px;margin:0;list-style:none;font-size:18px}.font-select .fs-results li.active{background:#3875d7;color:#fff;cursor:pointer}.font-select .fs-results li em{background:#feffde;font-style:normal}.font-select .fs-results li.active em{background:0 0}.font-select-active>a{border:1px solid #aaa;-webkit-box-shadow:0 1px 0 #fff inset;-moz-box-shadow:0 1px 0 #fff inset;-o-box-shadow:0 1px 0 #fff inset;box-shadow:0 1px 0 #fff inset;background-color:#eee;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0,#fff),color-stop(.5,#eee));background-image:-webkit-linear-gradient(center bottom,#fff 0,#eee 50%);background-image:-moz-linear-gradient(center bottom,#fff 0,#eee 50%);background-image:-o-linear-gradient(bottom,#fff 0,#eee 50%);background-image:-ms-linear-gradient(top,#fff 0,#eee 50%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eeeeee', GradientType=0 );background-image:linear-gradient(top,#fff 0,#eee 50%);-webkit-border-bottom-left-radius:0;-webkit-border-bottom-right-radius:0;-moz-border-radius-bottomleft:0;-moz-border-radius-bottomright:0;border-bottom-left-radius:0;border-bottom-right-radius:0}.font-select-active>a div{background:0 0;border-left:none}.font-select-active>a div b{background-position:-18px 1px}
		</style>
		<script>
			/** Google Font Selector */
			!function(t){t.fn.fontselect=function(e){var i=function(t,e){return function(){return t.apply(e,arguments)}},o=["Aclonica","Allan","Annie+Use+Your+Telescope","Anonymous+Pro","Allerta+Stencil","Allerta","Amaranth","Anton","Architects+Daughter","Arimo","Artifika","Arvo","Asset","Astloch","Bangers","Bentham","Bevan","Bigshot+One","Bowlby+One","Bowlby+One+SC","Brawler","Buda:300","Cabin","Calligraffitti","Candal","Cantarell","Cardo","Carter One","Caudex","Cedarville+Cursive","Cherry+Cream+Soda","Chewy","Coda","Coming+Soon","Copse","Corben:700","Cousine","Covered+By+Your+Grace","Crafty+Girls","Crimson+Text","Crushed","Cuprum","Damion","Dancing+Script","Dawning+of+a+New+Day","Didact+Gothic","Droid+Sans","Droid+Sans+Mono","Droid+Serif","EB+Garamond","Expletus+Sans","Fontdiner+Swanky","Forum","Francois+One","Geo","Give+You+Glory","Goblin+One","Goudy+Bookletter+1911","Gravitas+One","Gruppo","Hammersmith+One","Holtwood+One+SC","Homemade+Apple","Inconsolata","Indie+Flower","IM+Fell+DW+Pica","IM+Fell+DW+Pica+SC","IM+Fell+Double+Pica","IM+Fell+Double+Pica+SC","IM+Fell+English","IM+Fell+English+SC","IM+Fell+French+Canon","IM+Fell+French+Canon+SC","IM+Fell+Great+Primer","IM+Fell+Great+Primer+SC","Irish+Grover","Irish+Growler","Istok+Web","Josefin+Sans","Josefin+Slab","Judson","Jura","Jura:500","Jura:600","Just+Another+Hand","Just+Me+Again+Down+Here","Kameron","Kenia","Kranky","Kreon","Kristi","La+Belle+Aurore","Lato:100","Lato:100italic","Lato:300","Lato","Lato:bold","Lato:900","League+Script","Lekton","Limelight","Lobster","Lobster Two","Lora","Love+Ya+Like+A+Sister","Loved+by+the+King","Luckiest+Guy","Maiden+Orange","Mako","Maven+Pro","Maven+Pro:500","Maven+Pro:700","Maven+Pro:900","Meddon","MedievalSharp","Megrim","Merriweather","Metrophobic","Michroma","Miltonian Tattoo","Miltonian","Modern Antiqua","Monofett","Molengo","Mountains of Christmas","Muli:300","Muli","Neucha","Neuton","News+Cycle","Nixie+One","Nobile","Nova+Cut","Nova+Flat","Nova+Mono","Nova+Oval","Nova+Round","Nova+Script","Nova+Slim","Nova+Square","Nunito:light","Nunito","OFL+Sorts+Mill+Goudy+TT","Old+Standard+TT","Open+Sans:300","Open+Sans","Open+Sans:600","Open+Sans:800","Open+Sans+Condensed:300","Orbitron","Orbitron:500","Orbitron:700","Orbitron:900","Oswald","Over+the+Rainbow","Reenie+Beanie","Pacifico","Patrick+Hand","Paytone+One","Permanent+Marker","Philosopher","Play","Playfair+Display","Podkova","PT+Sans","PT+Sans+Narrow","PT+Sans+Narrow:regular,bold","PT+Serif","PT+Serif Caption","Puritan","Quattrocento","Quattrocento+Sans","Radley","Raleway:100","Redressed","Rock+Salt","Rokkitt","Roboto","Ruslan+Display","Schoolbell","Shadows+Into+Light","Shanti","Sigmar+One","Six+Caps","Slackey","Smythe","Sniglet:800","Special+Elite","Stardos+Stencil","Sue+Ellen+Francisco","Sunshiney","Swanky+and+Moo+Moo","Syncopate","Tangerine","Tenor+Sans","Terminal+Dosis+Light","The+Girl+Next+Door","Tinos","Ubuntu","Ultra","Unkempt","UnifrakturCook:bold","UnifrakturMaguntia","Varela","Varela Round","Vibur","Vollkorn","VT323","Waiting+for+the+Sunrise","Wallpoet","Walter+Turncoat","Wire+One","Yanone+Kaffeesatz","Yanone+Kaffeesatz:300","Yanone+Kaffeesatz:400","Yanone+Kaffeesatz:700","Yeseva+One","Zeyada"],a={style:"font-select",placeholder:"Select a font",lookahead:2,api:"https://fonts.googleapis.com/css?family="},n=function(){function e(e,i){this.$original=t(e),this.options=i,this.active=!1,this.setupHtml(),this.getVisibleFonts(),this.bindEvents();var o=this.$original.val();o&&(this.updateSelected(),this.addFontLink(o))}return e.prototype.bindEvents=function(){var e=this;t(document).click(function(i){e.active&&!t(i.target).parents("#fontSelect-"+e.$original.id).length&&e.toggleDrop()}),t("li",this.$results).click(i(this.selectFont,this)).mouseenter(i(this.activateFont,this)).mouseleave(i(this.deactivateFont,this)),t("span",this.$select).click(i(this.toggleDrop,this)),this.$arrow.click(i(this.toggleDrop,this))},e.prototype.toggleDrop=function(t){this.active?(this.$element.removeClass("font-select-active"),this.$drop.hide(),clearInterval(this.visibleInterval)):(this.$element.addClass("font-select-active"),this.$drop.show(),this.moveToSelected(),this.visibleInterval=setInterval(i(this.getVisibleFonts,this),500)),this.active=!this.active},e.prototype.selectFont=function(){var e=t("li.active",this.$results).data("value");this.$original.val(e).change(),this.updateSelected(),this.toggleDrop()},e.prototype.moveToSelected=function(){var e,i=this.$original.val();e=i?t("li[data-value='"+i+"']",this.$results):t("li",this.$results).first()},e.prototype.activateFont=function(e){t("li.active",this.$results).removeClass("active"),t(e.currentTarget).addClass("active")},e.prototype.deactivateFont=function(e){t(e.currentTarget).removeClass("active")},e.prototype.updateSelected=function(){var e=this.$original.val();t("span",this.$element).text(this.toReadable(e)).css(this.toStyle(e))},e.prototype.setupHtml=function(){this.$original.empty().hide(),this.$element=t("<div>",{id:"fontSelect-"+this.$original.id,class:this.options.style}),this.$arrow=t("<div><b></b></div>"),this.$select=t("<a><span>"+this.options.placeholder+"</span></a>"),this.$drop=t("<div>",{class:"fs-drop"}),this.$results=t("<ul>",{class:"fs-results"}),this.$original.after(this.$element.append(this.$select.append(this.$arrow)).append(this.$drop)),this.$drop.append(this.$results.append(this.fontsAsHtml())).hide()},e.prototype.fontsAsHtml=function(){for(var t,e,i=o.length,a="",n=0;n<i;n++)t=this.toReadable(o[n]),e=this.toStyle(o[n]),a+='<li data-value="'+o[n]+'" style="font-family: '+e["font-family"]+"; font-weight: "+e["font-weight"]+'">'+t+"</li>";return a},e.prototype.toReadable=function(t){return t.replace(/[\+|:]/g," ")},e.prototype.toStyle=function(t){var e=t.split(":");return{"font-family":this.toReadable(e[0]),"font-weight":e[1]||400}},e.prototype.getVisibleFonts=function(){if(!this.$results.is(":hidden")){var e=this,i=this.$results.scrollTop(),o=i+this.$results.height();if(this.options.lookahead){var a=t("li",this.$results).first().height();o+=a*this.options.lookahead}t("li",this.$results).each(function(){var a=t(this).position().top+i;if(a+t(this).height()>=i&&a<=o){var n=t(this).data("value");e.addFontLink(n)}})}},e.prototype.addFontLink=function(e){var i=this.options.api+e;0===t("link[href*='"+e+"']").length&&t("link:last").after('<link href="'+i+'" rel="stylesheet" type="text/css">')},e}();return this.each(function(e){return e&&t.extend(a,e),new n(this,a)})}}(jQuery);
		</script>
	    <div class="metabox-holder">
	        <?php foreach ( $this->sections_array as $form ) { ?>
	            <!-- style="display: none;" -->
	            <div id="<?php echo $form['id']; ?>" class="group" >
	                <form method="post" action="options.php">
	                    <?php
	                    do_action( 'wsa_form_top_' . $form['id'], $form );
						settings_fields( $form['id'] );
						echo '<div class="prt-settings-desc">', $this->title_desc,'</div>';
	                    do_settings_sections( $form['id'] );
	                    do_action( 'wsa_form_bottom_' . $form['id'], $form );
	                    ?>
	                    <div style="padding-left: 10px">
	                        <?php submit_button(); ?>
	                    </div>
	                </form>
	            </div>
	        <?php } ?>
	    </div>
	    <?php
	    $this->script();
	}

	/**
	 * Tabbable JavaScript codes & Initiate Color Picker
	 *
	 * This code uses localstorage for displaying active tabs
	 */
	function script() {
	    ?>
	    <script>
			var $prt_settings_desc = jQuery("div.prt-settings-desc");
			$prt_settings_desc.insertAfter($prt_settings_desc.next());
	        jQuery(document).ready(function($) {
	            //Initiate Color Picker
	            $('.wp-color-picker-field').wpColorPicker();

	            // Switches option sections
	            $('.group').hide();
	            var activetab = '';
	            if (typeof(localStorage) != 'undefined' ) {
	                activetab = localStorage.getItem("activetab");
	            }
	            if (activetab != '' && $(activetab).length ) {
	                $(activetab).fadeIn();
	            } else {
	                $('.group:first').fadeIn();
	            }
	            $('.group .collapsed').each(function(){
	                $(this).find('input:checked').parent().parent().parent().nextAll().each(
	                function(){
	                    if ($(this).hasClass('last')) {
	                        $(this).removeClass('hidden');
	                        return false;
	                    }
	                    $(this).filter('.hidden').removeClass('hidden');
	                });
	            });

	            if (activetab != '' && $(activetab + '-tab').length ) {
	                $(activetab + '-tab').addClass('nav-tab-active');
	            }
	            else {
	                $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
	            }
	            $('.nav-tab-wrapper a').click(function(evt) {
	                $('.nav-tab-wrapper a').removeClass('nav-tab-active');
	                $(this).addClass('nav-tab-active').blur();
	                var clicked_group = $(this).attr('href');
	                if (typeof(localStorage) != 'undefined' ) {
	                    localStorage.setItem("activetab", $(this).attr('href'));
	                }
	                $('.group').hide();
	                $(clicked_group).fadeIn();
	                evt.preventDefault();
	            });

	            $('.wpsa-browse').on('click', function (event) {
	                event.preventDefault();

	                var self = $(this);

	                // Create the media frame.
	                var file_frame = wp.media.frames.file_frame = wp.media({
	                    title: self.data('uploader_title'),
	                    button: {
	                        text: self.data('uploader_button_text'),
	                    },
	                    multiple: false
	                });

	                file_frame.on('select', function () {
	                    attachment = file_frame.state().get('selection').first().toJSON();

	                    self.prev('.wpsa-url').val(attachment.url).change();
	                });

	                // Finally, open the modal
	                file_frame.open();
	            });

	            $('input.wpsa-url').on( 'change keyup paste input', (function() {
	                var self = $(this);
	                self.next().parent().children( '.wpsa-image-preview' ).children( 'img' ).attr( 'src', self.val() );
				})).change();
				
				$('form').submit(function() {
					$(this).attr('action', $(this).attr('action') + '?prt_settings_saved=1');
				});
				
				jQuery('.prt-license-status-btn').click();
		});

	    </script>

	    <style type="text/css">
	        /** WordPress 3.8 Fix **/
	        .form-table th { padding: 20px 10px; }
	        #wpbody-content .metabox-holder { padding-top: 5px; }
	        .wpsa-image-preview img{height: auto; max-width: 70px;}
			.form-table input:disabled {
				background-color: #BDBDBD;
			}
	    </style>
	    <?php
	}




} // WP_OSA ended.

endif;