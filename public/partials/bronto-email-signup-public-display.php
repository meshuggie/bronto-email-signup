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

<form id="<?php echo $this->prefix; ?>email-signup" class="bronto-email-signup disabled" onsubmit="return false;">
  <noscript>You must have javascript enabled to use this feature.</noscript>
  <fieldset disabled>
    <img src="<?php echo plugins_url( 'bronto-email-signup/public/images/bronto-loading.svg' ); ?>" class="loading">
    <div role="group" class="form-group">
      <?php if ($this->broes_contact == 'phone') : ?>
      <label for="phone">Phone Number<span class="required">*</span></label>
      <input type="tel" name="mobileNumber" aria-required="true">
      <?php else : ?>
      <label for="email">Email<span class="required">*</span></label>
      <input type="email" name="email" aria-required="true">
      <?php endif; ?>
    </div>
    <?php
    foreach($this->input_fields as $field) {
      echo $field;
    }
    ?>
    <button type="submit"><?php echo $this->broes_cta; ?></button>
    <div class="response"></div>
  </fieldset>
</form>
