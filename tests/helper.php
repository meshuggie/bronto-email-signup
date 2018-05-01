<?php
/**
 * Class Helper
 *
 * @package Bronto_Email_Signup
 */

class Test_Helper {

  public function __construct($plugin) {
    $this->faker = Faker\Factory::create();
    $this->faker->seed(1);
    $this->fields = $plugin->get_option_fields();
    $this->field_values = $this->field_values();
    $this->contact_inputs = $this->contact_inputs();
  }

  public function field_values() {
    $field_values = [];

    foreach ( $this->fields as $key => $value ) {
      $field_values[$key] = $this->faker->word;
    }

    $field_values['broes_fields'] = [];
    $sort_number = range(0, 2);
    shuffle($sort_number);
    for ($i=0; $i<3; $i++) {
      $field_values['broes_fields'][] = $this->input_types($sort_number[$i]);
    }
    return $field_values;
  }

  public function post_data($values) {
    $_POST['_wpnonce'] = wp_create_nonce( 'my_nonce' );
    foreach($values as $key => $value) {
      $_POST[$key] = $value;
    }
    return $_POST;
  }

  public function email_lists($xml = false) {
    $arr = [];
    $max = $this->faker->numberBetween(1, 5);
    $lists = new SimpleXMLElement($this->getListsXml());
    for ($i=0; $i < $max; $i++) {
      $list = new stdClass;
      $list->id = $this->faker->unique()->randomDigit;
      $list->name = $this->faker->name;
      $list->label = $this->faker->name;
      $list->activeCount = $this->faker->numberBetween(1, 10);
      $list->status = 'active';
      $list->visibility = 'public';
      $arr[] = $list;
      $xmlObj = $lists->addChild('list');
      $xmlObj->addChild('id', $list->id);
      $xmlObj->addChild('name', $list->name);
      $xmlObj->addChild('label', $list->label);
      $xmlObj->addChild('activeCount', $list->activeCount);
      $xmlObj->addChild('status', 'active');
      $xmlObj->addChild('visibility', 'public');
    }
    $return = new stdClass;
    $return->xml = $lists;
    $return->obj = $arr;
    return $return;
  }

  private function input_types($sort_number) {
    return [
      'id' => $this->faker->md5,
      'required' => $this->faker->boolean,
      'sort' => $sort_number,
      'hidden' => $this->faker->boolean,
      'value' => $this->faker->word
    ];
  }

  private function contact_inputs() {
    $values = array();
    for ($i=0; $i < 3; $i++) {
      $values []= $this->faker->unique()->randomDigit;
    }

    return [
      'api_key' => $this->faker->md5,
      'list_ids' => $values,
      'webform_url' => $this->faker->url,
      'webform_secret' => $this->faker->md5
    ];
  }

	private function getListsXml() {
			return <<<XML
<lists>
</lists>
XML;
	}

}
