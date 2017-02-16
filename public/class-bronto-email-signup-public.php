<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/meshuggie/bronto-email-signup
 * @since      1.0.0
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/public
 * @author     Joshua Harris
 */
class Bronto_Email_Signup_Public {

	private $broes_fields,
		$broes_required_fields,
		$all_fields,
		$input_objects,
		$input_fields,
		$broes_contact,
		$broes_cta,
		$broes_success_message,
		$broes_registered_message,
		$expected_inputs,
		$prefix;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bronto_email_signup    The ID of this plugin.
	 */
	private $bronto_email_signup;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $bronto_email_signup       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bronto_email_signup, $version, $option_fields ) {

		$this->bronto_email_signup = $bronto_email_signup;
		$this->option_fields = $option_fields;
		$this->version = $version;
		foreach ($this->option_fields as $key => $value) {
			$this->$key = get_option( $key );
		}
		$this->broes_cta = ( $this->broes_cta !== '' ) ? $this->broes_cta : 'Submit';

		$api = new Bronto_Email_Signup_Api( array( 'api_key' => $this->broes_api_key ) );
		if ( $api->connection ) {
			$this->all_fields = $api->get_fields();
			$this->input_objects = $this->get_input_objects();
			$this->expected_inputs = $this->get_expected_inputs();
		}

	}

	public function shortcode() {
		add_shortcode('broes_signup_form', array( $this, 'bronto_email_signup_shortcode' ) );
	}

	public function bronto_email_signup_shortcode( $atts = array(), $content = null ) {
		$this->prefix = (isset($atts['prefix-id'])) ? $atts['prefix-id'] . '-' : 'broes-';
		$this->input_fields = $this->get_input_fields();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/bronto-email-signup-public-display.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bronto_Email_Signup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bronto_Email_Signup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->bronto_email_signup, plugin_dir_url( __FILE__ ) . 'dist/css/bronto-email-signup-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bronto_Email_Signup_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bronto_Email_Signup_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

 		$data = array(
 			'ajax_url' => admin_url( 'admin-ajax.php' ),
 			'nonce' => wp_create_nonce( 'broes_nonce' ),
			'expected_inputs' => $this->expected_inputs,
			'success_message' => $this->broes_success_message,
			'registered_message' => $this->broes_registered_message
 		);
		wp_enqueue_script( 'jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->bronto_email_signup, plugin_dir_url( __FILE__ ) . 'dist/js/bronto-email-signup.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->bronto_email_signup, 'broes', $data );

	}

	private function get_input_objects() {
		$input_fields = array();
		if ( !is_array( $this->broes_fields ) ) return $input_fields;
		foreach ($this->broes_fields as $value) {
		  $filtered = array_filter($this->all_fields, function($el) use ($value) {
		    return $el->id == $value;
		  });
		  $input_fields[] = $filtered;
		}
		return $input_fields;
	}

	private function get_input_fields() {
		$input_html = array();
		if ( !is_array( $this->input_objects ) ) {
			return $input_html;
		}
		foreach($this->input_objects as $field) {
		  $field = array_values($field);
		  $input_html[] = $this->input_html($field[0]);
		}
		return $input_html;
	}

	private function input_html($field) {
		$required_label = ( !empty( $this->broes_required_fields ) && in_array( $field->id, $this->broes_required_fields ) ) ? '<span class="required">*</span>' : '';
		$required_input = ( !empty( $this->broes_required_fields ) && in_array( $field->id, $this->broes_required_fields ) ) ? ' aria-required="true"' : '';
	  switch($field->type) {
	    case 'select' :
				$html = '<div role="group" class="form-group">';
	      $html .= '<label for="' . $this->prefix . $field->name . '">';
				$html .= $field->name;
				$html .= $required_label;
				$html .= '</label>';
	      $html .= '<select id="' . $this->prefix . $field->name . '" name="' . $field->id . '"' . $required_input . '>';
	      foreach($field->options as $option) {
	        $selected = ($option->isDefault) ? ' selected disabled hidden' : '';
	        $html .= '<option label="' . $option->label . '" value="' . $option->value . '"' . $selected . '>' . $option->label . '</option>';
	      }
	      $html .= '</select>';
				$html .= '</div>';
	      return $html;
	    case 'checkbox' :
				$html = '<div role="checkbox" aria-labelledby="' . $this->prefix . $field->name . '" class="form-group">';
				if (isset($field->options)) {
					$html .= '<label id="' . $this->prefix . $field->name . '">';
					$html .= $field->name;
					$html .= $required_label;
					$html .= '</label>';
		      $html .= '<div id="' . $this->prefix . $field->name . '" role="group">';
					foreach($field->options as $option) {
		        $selected = ($option->isDefault) ? ' selected="selected"' : '';
		        $html .= '<input type="' . $field->type . '" id="' . $this->prefix . $option->label . '" name="' . $field->id . '[]" value="' . $option->value . '"' . $selected . $required_input . '>';
		        $html .= '<label for="' . $this->prefix . $option->label . '">' . $option->label . '</label>';
		      }
				} else {
					$html .= '<input type="' . $field->type . '" id="' . $field->id . '" name="' . $field->id . '">';
					$html .= '<label for="' . $field->id . '">' . $field->name . '</label>';
				}
	      $html .= '</div>';
	      return $html;
	    case 'radio' :
				$html = '<div role="radiogroup" aria-labelledby="' . $this->prefix . $field->name . '" class="form-group">';
	      $html .= '<label id="' . $this->prefix . $field->name . '">';
				$html .= $field->name;
				$html .= $required_label;
				$html .= '</label>';
				if (isset($field->options)) {
					foreach($field->options as $option) {
						$html .= '<div role="radio">';
		        $selected = ($option->isDefault) ? ' selected="selected"' : '';
		        $html .= '<input type="' . $field->type . '" id="' . $this->prefix . $option->label . '" name="' . $field->id . '" value="' . $option->value . '"' . $selected . $required_input . '>';
		        $html .= '<label for="' . $this->prefix . $option->label . '">' . $option->label . '</label>';
						$html .= '</div>';
		      }
				}
	      $html .= '</div>';
	      return $html;
	    case 'textarea' :
				$html = '<div role="group">';
	      $html .= '<label for="' . $field->id . '">';
				$html .= $field->name;
				$html .= $required_label;
				$html .= '</label>';
				$html .= '<textarea id="' . $field->id . '" name="' . $field->id . '"' . $required_input . '></textarea>';
				$html .= '</div>';
				return $html;
	    default : {
				switch ($field->type) {
					case 'currency' :
					case 'integer' :
					case 'float' :
						$type = 'number';
					default :
						$type = 'text';
				}
				$html = '<div role="group" class="form-group">';
	      $html .= '<label for="' . $field->id . '">';
				$html .= $field->name;
				$html .= $required_label;
				$html .= '</label>';
				$html .= '<input type="' . $type . '" id="' . $field->id . '" name="' . $field->id . '"' . $required_input . '>';
				$html .= '</div>';
				return $html;
			}
	  }
	}

	private function get_expected_inputs() {
		$inputs = array();
		foreach ( $this->input_objects as $field ) {
		  $field = array_values($field);
			if ( !empty( $field[0] ) ) {
				$inputs[] = $field[0]->id;
			}
		}
		$inputs[] = $this->broes_contact;
		return $inputs;
	}

}
