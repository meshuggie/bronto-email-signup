<?php
/**
 * Class SampleTest
 *
 * @package Bronto_Email_Signup
 */

class AdminTest extends WP_UnitTestCase {
	private $plugin,
		$fields = array(
			'broes_api_key' => '',
			'broes_webform_url' => '',
			'broes_webform_secret' => '',
			'broes_contact' => '',
			'broes_list_ids' => '',
			'broes_fields' => '',
			'broes_required_fields' => '',
			'broes_cta' => '',
			'broes_success_message' => '',
			'broes_registered_message' => ''
		);

	public function setUp() {
		parent::setUp();
		foreach ($this->fields as $key => $value) {
			add_option($key, $value);
		}
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function testPluginActive() {
		$test = is_plugin_active( 'bronto-email-signup/bronto-email-signup.php' );
		$this->assertTrue( $test );
	}

	public function testAdminInit() {
		$this->setUp();
		foreach ( $this->fields as $key => $value ) {
			$this->assertTrue( empty( get_option( $key ) ) );
		}
	}
}
