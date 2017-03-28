<?php
/**
 * Class SampleTest
 *
 * @package Bronto_Email_Signup
 */

class ApiTest extends WP_Ajax_UnitTestCase {
	private $plugin, $client, $ajax;

	public function setUp() {
		parent::setUp();
		activate_bronto_email_signup();

		$this->api = new Bronto_Email_Signup_Api( array( 'api_key' => '' ) );
		$this->helper = new Test_Helper($this->plugin);
	}

	public function tearDown() {
		parent::tearDown();
		deactivate_bronto_email_signup();
	}

}
