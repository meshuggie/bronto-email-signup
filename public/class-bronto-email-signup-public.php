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

}
