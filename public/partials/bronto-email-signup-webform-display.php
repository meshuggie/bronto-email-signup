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

<div class="bronto-webform">
  <iframe id="<?php echo $this->prefix; ?>email-signup" src="<?php echo $this->src; ?>"></iframe>
</div>
