<?php
class Bronto_Email_Signup_Api {
  public $connection;
  protected $client,
    $webform_url,
    $connection_data;

  public function __construct( $connection_data = null ) {
    $this->client = new SoapClient(
      'https://api.bronto.com/v4?wsdl',
      array(
        'trace' => 1,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS
      )
    );

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
        'email' => htmlentities($this->connection_data['email']),
        'listIds' => $this->connection_data['list_ids'],
        'fields' => $this->connection_data['fields']
      );

      // Create hashed link to Manage Pref's Bronto form
      if ( !empty( $this->connection_data['webform_url'] ) ) {
        $this->webform_url = $this->get_webform_url();
      }

      $write_result = $this->client->addOrUpdateContacts( array( $contacts ) )->return;
      if ( $write_result->errors ) {
        $result = array(
          'result' => 'error',
          'message' => $write_result->results[0]->errorString,
          'webformUrl' => $this->webform_url
        );
      } elseif ( $write_result->results[0]->isNew == true ) {
        $result = array(
          'result' => 'added',
          'message' => 'Thank you for signing up.',
          'webformUrl' => $this->webform_url
        );
      } else {
        $result = array(
          'result' => 'updated',
          'message' => 'Thank you for signing up.',
          'webformUrl' => $this->webform_url
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

  private function get_webform_url() {
    preg_match("/.*?lookup\/(.*?)\/(.*?)\/manpref\//", $this->connection_data['webform_url'], $output_array);
    $webform_hash = hash_hmac('sha256',
      $output_array[2] .
      $output_array[1] .
      $this->connection_data['email'],
      $this->connection_data['webform_secret']
    );
    return $this->connection_data['webform_url'] . $this->connection_data['email'] . '/' .  $webform_hash;
  }

}
