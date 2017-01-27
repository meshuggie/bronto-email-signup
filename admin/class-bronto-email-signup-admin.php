<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/meshuggie/bronto-email-signup
 * @since      1.0.0
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/admin
 * @author     Joshua Harris
 */
class Bronto_Email_Signup_Admin {

	private $broes_api_key,
		$broes_webform_url,
		$broes_webform_secret,
		$broes_contact,
		$broes_list_ids,
		$broes_fields,
		$broes_required_fields,
		$broes_cta,
		$broes_success_message,
		$broes_registered_message,
		$api_initiated,
		$lists,
		$fields;

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
	 * @param      string    $bronto_email_signup       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $bronto_email_signup, $version ) {

		$this->bronto_email_signup = $bronto_email_signup;
		$this->version = $version;
		$this->broes_api_key = get_option( 'broes_api_key' );
		$this->broes_webform_url = get_option( 'broes_webform_url' );
		$this->broes_webform_secret = get_option( 'broes_webform_secret' );
		$this->broes_contact = get_option( 'broes_contact' );
		$this->broes_list_ids = get_option( 'broes_list_ids' );
		$this->broes_fields = get_option( 'broes_fields' );
		$this->broes_required_fields = get_option( 'broes_required_fields' );
		$this->broes_cta = get_option( 'broes_cta' );
		$this->broes_success_message = get_option( 'broes_success_message' );
		$this->broes_registered_message = get_option( 'broes_registered_message' );

		$api = new Bronto_Email_Signup_Api( array( 'api_key' => $this->broes_api_key ) );
		$this->api_initiated = $api->connection;
		if ( $this->api_initiated ) {
			$this->lists = $api->get_lists();
			$this->fields = $api->get_fields();
		}

	}

	/**
	 * Register the Menu for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function bronto_email_signup_menu() {

		/**
		 * Adds the plugin to the admin settings menu.
		 *
		 */
		 add_menu_page(
			 'Bronto Email Signup',
			 'Bronto Email',
			 'manage_options',
			 'bronto-email-signup-options',
			 array( $this, 'bronto_email_signup_page' ),
			 'dashicons-email-alt'
		 );

	}

	public function widget() {
		register_widget( 'Broes_Widget' );
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->bronto_email_signup, plugin_dir_url( __FILE__ ) . 'css/bronto-email-signup-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		$broes_nonce = wp_create_nonce( 'broes_nonce' );
		$data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => $broes_nonce
		);
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->bronto_email_signup, plugin_dir_url( __FILE__ ) . 'js/bronto-email-signup-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->bronto_email_signup, 'broes', $data );

	}

	/**
	 * Sets up the admin page.
	 *
	 * @since    1.0.0
	 */
	public function bronto_email_signup_page() {

		include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/bronto-email-signup-admin-display.php';

	}

	public function update_settings() {

		check_ajax_referer( 'broes_nonce' );
		update_option( 'broes_api_key', $_POST['api_key'] );
		update_option( 'broes_webform_url', $_POST['webform_url'] );
		update_option( 'broes_webform_secret', $_POST['webform_secret'] );
		update_option( 'broes_contact', $_POST['contact'] );
		update_option( 'broes_list_ids', $_POST['list_ids'] );
		update_option( 'broes_fields', $_POST['fields'] );
		update_option( 'broes_required_fields', $_POST['required_fields'] );
		update_option( 'broes_cta', $_POST['cta'] );
		update_option( 'broes_success_message', $_POST['success_message'] );
		update_option( 'broes_registered_message', wp_kses_post($_POST['registered_message']) );
		$result = array(
			'result' => 'success',
			'message' => 'Bronto settings successfully updated.'
		);
		echo json_encode( $result );
		wp_die();

	}

	public function add_contact() {

		check_ajax_referer( 'broes_nonce' );
		// Check if we are testing connection in backend.
		if (isset($_POST['api_key'])) {
			$connection_data = array(
				'list_ids' => $_POST['list_ids'],
				'api_key' => $_POST['api_key'],
				'email' => $_POST['email']
			);
		} else {
			$connection_data = $this->get_expected_inputs($_POST);
			$connection_data['api_key'] = $this->broes_api_key;
			$connection_data['list_ids'] = $this->broes_list_ids;
			$connection_data['webform_url'] = $this->broes_webform_url;
			$connection_data['webform_secret'] = $this->broes_webform_secret;
		}
		$signup = new Bronto_Email_Signup_Api( $connection_data );

		echo $signup->add_contact();
		wp_die();

	}

	private function get_expected_inputs($data) {
		$fields = array();
		foreach($data['expected_inputs'] as $key => $input) {
			$fields[$input] = array(
				'fieldId' => $input,
				'content' => $data[$input]
			);
		}
		unset($fields[$this->broes_contact]);
		$fields = array_values($fields);
		return array(
			'fields' => $fields,
			$this->broes_contact => $data[$this->broes_contact]
		);
	}

}
