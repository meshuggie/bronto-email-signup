<?php
class Bronto_Email_Signup_Api {

  public $connection;
  protected $client,
    $connection_data;

  public function __construct( $connection_data = null ) {

    $this->client = new SoapClient('https://api.bronto.com/v4?wsdl', array(
      'trace' => 1,
      'features' => SOAP_SINGLE_ELEMENT_ARRAYS
    ));
    try {
      $sessionId = $this->client->login(
        array('apiToken' => $connection_data['api_key'])
      )->return;

      $session_header = new SoapHeader(
        "http://api.bronto.com/v4",
        'sessionHeader',
        array( 'sessionId' => $sessionId )
      );
      $this->client->__setSoapHeaders( array( $session_header ) );
      $this->connection = true;
    } catch (Exception $e) {
      $this->connection = false;
    }

    $this->connection_data = $connection_data;

	}

  public function add_contact() {

    try {

      $contacts = array(
        'email' => $this->connection_data['email'],
        'listIds' => $this->connection_data['list_ids']
      );

      $write_result = $this->client->addContacts(array($contacts))->return;

      if ($write_result->errors) {
        $result = array(
          'result' => 'error',
          'message' => $write_result->results[0]->errorString
        );
      } else {
        $message = ( count( $this->connection_data['list_ids'] ) > 1 ) ? 'Test contact successfully added to Bronto lists.' : 'Test contact successfully added to Bronto list.';
        $result = array(
          'result' => 'success',
          'message' => $message
        );
      }

    } catch (Exception $e) {
      $result = array(
        'result' => 'exception',
        'message' => $e
      );
    }

    echo json_encode( $result );

  }

  public function get_lists() {

    try {

      return $this->client->readLists(
        array( 'pageNumber' => 1, 'filter' => array() )
      )->return;

    } catch (Exception $e) {

      return array(
        'result' => 'exception',
        'message' => $e
      );

    }

  }

  public function get_fields() {

    try {

      return $this->client->readFields(
        array( 'pageNumber' => 1, 'filter' => array() )
      )->return;

    } catch (Exception $e) {

      return array(
        'result' => 'exception',
        'message' => $e
      );

    }

  }
}
