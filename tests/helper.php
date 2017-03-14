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

  public function post_data() {
    $_POST['_wpnonce'] = wp_create_nonce( 'my_nonce' );
    foreach($this->field_values as $key => $value) {
      $_POST[$key] = $value;
    }
    return $_POST;
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

}
