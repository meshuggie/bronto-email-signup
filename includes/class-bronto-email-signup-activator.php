<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/includes
 * @author     Joshua Harris
 */
class Bronto_Email_Signup_Activator {

	public static function activate() {
		add_option('broes_api_key');
		add_option('broes_list_id');
		register_setting('broes_settings', 'broes_api_key');
		register_setting( 'broes_settings', 'broes_list_id' );
	}

}
