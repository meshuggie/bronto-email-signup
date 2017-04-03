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
		delete_option( 'broes_api_key' );
		delete_option( 'broes_webform_url' );
		delete_option( 'broes_webform_secret' );
		delete_option( 'broes_contact' );
		delete_option( 'broes_list_ids' );
		delete_option( 'broes_fields' );
		delete_option( 'broes_cta' );
		delete_option( 'broes_success_message' );
		delete_option( 'broes_registered_message' );
		unregister_setting( 'broes_settings', 'broes_api_key' );
		unregister_setting( 'broes_settings', 'broes_webform_url' );
		unregister_setting( 'broes_settings', 'broes_webform_secret' );
		unregister_setting( 'broes_settings', 'broes_contact' );
		unregister_setting( 'broes_settings', 'broes_list_ids' );
		unregister_setting( 'broes_settings', 'broes_fields' );
		unregister_setting( 'broes_settings', 'broes_cta' );
		unregister_setting( 'broes_settings', 'broes_success_message' );
		unregister_setting( 'broes_settings', 'broes_registered_message' );
		remove_shortcode( 'broes_signup_form' );
		remove_shortcode( 'broes_webform' );
	}

}
