<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/meshuggie/bronto-email-signup
 * @since      1.0.0
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/public/partials
 */
?>

<h2>my bronto stuff</h2>
<form>
  <label for="email">Email</label>
  <input type="email" name="email">
  <?php
  foreach($this->input_fields as $field) {
    echo $field;
  }
  ?>
</form>
