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

		$this->plugin = new Bronto_Email_Signup();
		$this->helper = new Test_Helper($this->plugin);
	}

	public function testGetLists() {
		// $lists = $this->helper->email_lists();

		// $client = Mockery::mock()
		// 	->shouldReceive('readLists')
		// 	->once()
		// 	->with( array( 'pageNumber' => 1, 'filter' => array() ) )
		// 	->andReturn(
		// 		array(
		// 			'return' => simplexml_load_string($lists->xml)
		// 		)
		// 	)
		// 	->getMock();
		
		// $response = (new Bronto_Email_Signup_Api($client))->get_lists();
		// $xml = simplexml_load_string($lists->xml);
		// fwrite(STDERR, print_r(json_decode(json_encode($response)), TRUE));
		// fwrite(STDERR, print_r($lists->xml, TRUE));
		
		// $this->assertEquals($lists->obj, $response);

		// $brontoAPI = $this->getMockFromWsdl(
		// 	dirname( dirname( __FILE__ ) ) . '/tests/brontov4.xml',
		// 	'BrontoSoapApiImplService'
		// );

		$lists = $this->helper->email_lists();
		$brontoAPI = $this->getMockBuilder('BrontoAPI')->setMethods(array('readLists'))->getMock();
		// $brontoAPI = $this->getMockBuilder('BrontoAPI')->getMock();
		// $brontoAPI->setMethods(array('readLists'));
		$brontoAPI->expects($this->any())
			->method('readLists')
			->with( array( 'pageNumber' => 1, 'filter' => array() ) )
			->will( $this->returnValue(
				array(
					'return' => $lists->obj
				)
			) );

		// $brontoAPI->method('readLists')
		// 	->willReturn(
		// 		array(
		// 			'return' => $lists->obj
		// 		)
		// 	);
		// $lists = $this->helper->email_lists();
		
		// $brontoAPI->expects($this->any())
		// 	->method('readLists')
		// 	->will($this->returnValue(
		// 		array(
		// 			'return' => $lists->obj
		// 		)
		// 	));

		$response = (new Bronto_Email_Signup_Api( $brontoAPI ))->get_lists();
		fwrite(STDERR, print_r(json_decode(json_encode($response)), TRUE));
		// fwrite(STDERR, print_r($apiResult, TRUE));

		$this->assertEquals(
			$lists->obj,
			$response
		);
	}

	/*public function testSearch() {
		$googleSearch = $this->getMockFromWsdl(
			'https://raw.githubusercontent.com/sensepost/BilePublic/master/GoogleSearch.wsdl', 'GoogleSearch'
		);

		$directoryCategory = new stdClass;
		$directoryCategory->fullViewableName = '';
		$directoryCategory->specialEncoding = '';

		$element = new stdClass;
		$element->summary = '';
		$element->URL = 'https://phpunit.de/';
		$element->snippet = '...';
		$element->title = '<b>PHPUnit</b>';
		$element->cachedSize = '11k';
		$element->relatedInformationPresent = true;
		$element->hostName = 'phpunit.de';
		$element->directoryCategory = $directoryCategory;
		$element->directoryTitle = '';

		$result = new stdClass;
		$result->documentFiltering = false;
		$result->searchComments = '';
		$result->estimatedTotalResultsCount = 3.9000;
		$result->estimateIsExact = false;
		$result->resultElements = [$element];
		$result->searchQuery = 'PHPUnit';
		$result->startIndex = 1;
		$result->endIndex = 1;
		$result->searchTips = '';
		$result->directoryCategories = [];
		$result->searchTime = 0.248822;

		$googleSearch->expects($this->any())
									->method('doGoogleSearch')
									->will($this->returnValue($result));

		/**
		 * $googleSearch->doGoogleSearch() will now return a stubbed result and
		 * the web service's doGoogleSearch() method will not be invoked.
		 */
		/*$this->assertEquals(
			$result,
			$googleSearch->doGoogleSearch(
				'00000000000000000000000000000000',
				'PHPUnit',
				0,
				1,
				false,
				'',
				false,
				'',
				'',
				''
			)
		);
}*/

    private function getXml()
    {
        return <<<XML
<GetBooksResponse>
    <Books>
        <Book>
            <Title>The Alchemist</Title>
        </Book>
        <Book>
            <Title>Veronica Decides To Die</Title>
        </Book>
        <Book>
            <Title>The Second Machine Age</Title>
        </Book>
    </Books>
<GetBooksResponse>
XML;
    }

	public function tearDown() {
		parent::tearDown();
		deactivate_bronto_email_signup();
	}

}
