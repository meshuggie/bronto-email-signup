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
		$all_fields,
		$input_fields;

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
	public function __construct( $bronto_email_signup, $version ) {

		$this->bronto_email_signup = $bronto_email_signup;
		$this->version = $version;
		$broes_api_key = get_option( 'broes_api_key' );
		$this->broes_fields = get_option( 'broes_fields' );

		$api = new Bronto_Email_Signup_Api( array( 'api_key' => $broes_api_key ) );
		if ( $api->connection ) {
			$this->all_fields = $api->get_fields();
			$this->input_fields = $this->get_input_fields();
		}

	}

	public function shortcode() {
		add_shortcode('broes_signup_form', array( $this, 'bronto_email_signup_shortcode' ) );
	}

	public function bronto_email_signup_shortcode( $atts = [], $content = null ) {
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

		wp_enqueue_style( $this->bronto_email_signup, plugin_dir_url( __FILE__ ) . 'css/bronto-email-signup-public.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( 'jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->bronto_email_signup, plugin_dir_url( __FILE__ ) . 'js/bronto-email-signup-public.js', array( 'jquery' ), $this->version, false );

	}

	private function get_input_fields() {
		$input_fields = array();
		$input_html = array();
		foreach ($this->broes_fields as $value) {
		  $filtered = array_filter($this->all_fields, function($el) use ($value) {
		    return $el->id == $value;
		  });
		  $input_fields[] = $filtered;
		}
		foreach($input_fields as $field) {
		  $field = array_values($field);
		  $input_html[] = $this->input_html($field[0]);
		}
		return $input_html;
	}

	private function input_html($field) {
	  switch($field->type) {
	    case 'select' :
	      $html = '<label for="bronto-' . $field->name . '">' . $field->name . '</label>';
	      $html .= '<select id="bronto-' . $field->name . '" name="' . $field->name . '">';
	      foreach($field->options as $option) {
	        $selected = ($option->isDefault) ? ' selected="selected"' : '';
	        $html .= '<option label="' . $option->label . '" value="' . $option->value . '"' . $selected . '>' . $option->label . '</option>';
	      }
	      $html .= '</select>';
	      return $html;
	    case 'checkbox' :
	      $html = '<label for="bronto-' . $field->name . '">' . $field->name . '</label>';
	      $html .= '<div id="bronto-' . $field->name . '" role="group">';
	      foreach($field->options as $option) {
	        $selected = ($option->isDefault) ? ' selected="selected"' : '';
	        $html .= '<input type="' . $field->type . '" id="bronto-' . $option->label . '" name="' . $field->name . '[]" value="' . $option->value . '"' . $selected . '>';
	        $html .= '<label for="bronto-' . $option->label . '">' . $option->label . '</label>';
	      }
	      $html .= '</div>';
	      return $html;
	    case 'radio' :
	      $html = '<label id="bronto-' . $field->name . '">' . $field->name . '</label>';
	      $html .= '<div role="radiogroup" aria-labelledby="bronto-' . $field->name . '">';
	      foreach($field->options as $option) {
	        $selected = ($option->isDefault) ? ' selected="selected"' : '';
	        $html .= '<input type="' . $field->type . '" id="bronto-' . $option->label . '" name="' . $field->name . '" value="' . $option->value . '"' . $selected . '>';
	        $html .= '<label for="bronto-' . $option->label . '">' . $option->label . '</label>';
	      }
	      $html .= '</div>';
	      return $html;
	    case 'textarea' :
	      return '<label for="' . $field->id . '">' . $field->name . '</label><textarea id="' . $field->id . '" name="' . $field->id . '"></textarea>';
	    default :
	      return '<label for="' . $field->id . '">' . $field->name . '</label><input type="' . $field->type . '" id="' . $field->id . '" name="' . $field->id . '">';
	  }
	}
}
