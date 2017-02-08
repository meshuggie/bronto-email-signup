<?php
/**
 * Class SampleTest
 *
 * @package Bronto_Email_Signup
 */

class AdminTest extends WP_UnitTestCase {
	private $plugin;

	public function setUp() {
		parent::setUp();
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testPluginActive() {
		$test = is_plugin_active( 'bronto-email-signup/bronto-email-signup.php' );
		$this->assertTrue( $test );
	}
}
