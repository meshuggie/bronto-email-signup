<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/meshuggie/bronto-email-signup
 * @since      1.0.0
 *
 * @package    Bronto_Email_Signup
 * @subpackage Bronto_Email_Signup/admin/partials
 */
?>

<div class="wrap">
	<?php
	screen_icon(); ?>
	<h2>Bronto Email Signup</h2>
	<form method="post" action="options.php">
		<?php settings_fields('broes_settings'); ?>
		<h3>Configure the default Open Graph settings using the form below:</h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row" style="width:350px;"><label for="broes_api_key">API Key</label></th>
				<td><input type="text" style="width:429px;" id="broes_api_key" name="broes_api_key" value="<?php
				echo get_option('broes_api_key'); ?>" placeholder="Enter SOAP API Access Token." /></td>
			</tr>
			<tr valign="top">
				<th scope="row" style="width:350px;"><label for="broes_list_id">List ID's</label></th>
				<td><input type="text" style="width:429px;" id="broes_list_id" name="broes_list_id" value="<?php
				echo get_option('broes_list_id'); ?>" placeholder="" /></td>
			</tr>
		</table>
		<?php submit_button(); ?>
    <p>
      <button name="test-connection" class="button button-small">Test Connection</button>
    </p>
	</form>
</div>
