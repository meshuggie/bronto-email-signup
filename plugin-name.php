<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Bronto_Email_Signup
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate
 * Plugin URI:        http://example.com/bronto-email-signup-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bronto-email-signup
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bronto-email-signup-activator.php
 */
function activate_bronto_email_signup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bronto-email-signup-activator.php';
	Bronto_Email_Signup_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bronto-email-signup-deactivator.php
 */
function deactivate_bronto_email_signup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bronto-email-signup-deactivator.php';
	Bronto_Email_Signup_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bronto_email_signup' );
register_deactivation_hook( __FILE__, 'deactivate_bronto_email_signup' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bronto-email-signup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bronto_email_signup() {

	$plugin = new Bronto_Email_Signup();
	$plugin->run();

}
run_bronto_email_signup();
