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
		activate_bronto_email_signup();
		$this->plugin = new Bronto_Email_Signup();
		$this->plugin_admin = new Bronto_Email_Signup_Admin( $this->plugin->get_bronto_email_signup(), $this->plugin->get_version(), $this->plugin->get_option_fields() );
		$this->fields = $this->plugin->get_option_fields();

		$this->faker = Faker\Factory::create();
	}

	public function testPluginActive() {
		$test = is_plugin_active( 'bronto-email-signup/bronto-email-signup.php' );
		$this->assertTrue( $test );
	}

	public function testAdminInit() {
		foreach ( $this->fields as $key => $value ) {
			$option = get_option( $key );
			$this->assertTrue( is_string( $option ) );
			// fwrite(STDERR, print_r($option, TRUE));
		}
	}

	public function testAdminSave() {
		$broes_nonce = wp_create_nonce( 'broes_nonce' );
		$_POST['_ajax_nonce'] = $broes_nonce;
		foreach ( $this->fields as $key => $value ) {
			$_POST[$key] = 'klsjdf';
		}
		$this->assertTrue( check_ajax_referer( 'broes_nonce' ) );
		// $url = admin_url( 'admin.php?page=bronto-email-signup-options' );
	}

	public function tearDown() {
		parent::tearDown();
		deactivate_bronto_email_signup();
	}

}
