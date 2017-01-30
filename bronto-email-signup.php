<?php

/*
Bronto Email Signup is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Bronto Email Signup is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Bronto Email Signup. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
*/

/**
 *
 * @link              https://github.com/meshuggie/bronto-email-signup
 * @since             1.0.0
 * @package           Bronto_Email_Signup
 *
 * @wordpress-plugin
 * Plugin Name:       Bronto Email Signup
 * Plugin URI:        https://github.com/meshuggie/bronto-email-signup/
 * Description:       Creates a form for Bronto email signups, via a shortcode or widget. Allows you to add contacts to 1 or more Bronto Lists.
 * Version:           0.1.1
 * Author:            Joshua Harris
 * Author URI:        https://github.com/meshuggie/bronto-email-signup/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bronto-email-signup
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

function activate_bronto_email_signup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bronto-email-signup-activator.php';
	Bronto_Email_Signup_Activator::activate();
}

function deactivate_bronto_email_signup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bronto-email-signup-deactivator.php';
	Bronto_Email_Signup_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bronto_email_signup' );
register_deactivation_hook( __FILE__, 'deactivate_bronto_email_signup' );

require plugin_dir_path( __FILE__ ) . 'includes/class-bronto-email-signup.php';

function run_bronto_email_signup() {
	$plugin = new Bronto_Email_Signup();
	$plugin->run();
}
run_bronto_email_signup();
