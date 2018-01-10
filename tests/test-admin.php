<?php
/**
 * Class SampleTest
 *
 * @package Bronto_Email_Signup
 */

class AdminTest extends WP_Ajax_UnitTestCase {
	private $plugin, $client, $ajax;

	public function setUp() {
		parent::setUp();
		activate_bronto_email_signup();

		$this->plugin = new Bronto_Email_Signup();
		$this->plugin_admin = new Bronto_Email_Signup_Admin(
			$this->plugin->get_bronto_email_signup(),
			$this->plugin->get_version(),
			$this->plugin->get_option_fields()
		);
		$this->helper = new Test_Helper($this->plugin);
		$this->http = new GuzzleHttp\Client(['base_uri' => 'https://httpbin.org/']);
	}

	public function testPluginActive() {
		$test = is_plugin_active( 'bronto-email-signup/bronto-email-signup.php' );
		$this->assertTrue( $test );
	}

	public function testAdminInit() {
		foreach ( $this->helper->fields as $key => $value ) {
			$option = get_option( $key );
			$this->assertTrue( is_string( $option ) );
		}
	}

	public function testAdminSave() {
		$this->_setRole( 'administrator' );
		$this->helper->post_data($this->helper->field_values);

		try {
			$this->_handleAjax( 'broes_update_settings' );
		} catch ( WPAjaxDieContinueException $e ) {}
		$response = json_decode($this->_last_response);

		$this->assertEquals( 'success', $response->result );

		foreach ( $this->helper->fields as $key => $value ) {
			$option = get_option( $key );
			$expected = $this->helper->field_values[$key];
			$this->assertEquals( $option, $expected );
		}
	}

	public function testAdminPermissions() {
	  $this->_setRole( 'subscriber' );
		$this->helper->post_data($this->helper->field_values);

		try {
			$this->_handleAjax( 'broes_update_settings' );
		} catch ( WPAjaxDieStopException $e ) {}
		$response = json_decode($this->_last_response);

	  $this->assertTrue( isset( $e ) );
	  $this->assertEquals( '-1', $e->getMessage() );

		foreach ( $this->helper->fields as $key => $value ) {
			$option = get_option( $key );
			$this->assertEquals( $option, '' );
		}
		// fwrite(STDERR, print_r($option, TRUE));
	}

	public function testApiFailed() {
		$this->assertFalse( $this->plugin_admin->api_initiated  );
	}

	public function testAddContact() {
		$this->helper->post_data($this->helper->contact_inputs);

		$response = $this->http->request('POST', 'post');
		$admin = $this->getMockBuilder(Bronto_Email_Signup_Api::class)
			->setMethods(['add_contact'])
			->getMock();
		$admin->expects($this->once())
			->method('add_contact')
			->with($response);

		try {
			$this->_handleAjax( 'broes_add_contact' );
		} catch ( WPAjaxDieContinueException $e ) {}
		$response = json_decode($this->_last_response);
		fwrite(STDERR, print_r($response, true));
	}

	public function tearDown() {
		parent::tearDown();
		deactivate_bronto_email_signup();
	}

}