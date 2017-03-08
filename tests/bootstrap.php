<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Bronto_Email_Signup
 */

 // Be sure to run cmd on CLI prior to running tests
 // bash bin/install-wp-tests.sh wordpress_test root '' localhost latest

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/bronto-email-signup.php';
	update_option( 'active_plugins', array('bronto-email-signup/bronto-email-signup.php') );
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

require 'vendor/autoload.php';

// Bypass the ajax check
function check_ajax_referer() {
	return true;
}
