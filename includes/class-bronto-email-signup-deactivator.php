<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/includes
 * @author     Joshua Harris
 */
class Bronto_Email_Signup_Deactivator {

	public static function deactivate() {
		delete_option('broes_api_key');
		delete_option('broes_list_ids');
		delete_option('broes_fields');
		unregister_setting('broes_settings', 'broes_api_key');
		unregister_setting( 'broes_settings', 'broes_list_ids' );
		unregister_setting( 'broes_settings', 'broes_fields' );
	}

}
