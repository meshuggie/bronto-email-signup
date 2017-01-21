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

$broes_api_key = get_option( 'broes_api_key' );
$broes_list_ids = get_option( 'broes_list_ids' );
$broes_fields = get_option( 'broes_fields' );
$api = new Bronto_Email_Signup_Api( array( 'api_key' => $broes_api_key ) );
$api_initiated = $api->connection;
if ( $api_initiated ) {
	$lists = $api->get_lists();
	$fields = $api->get_fields();
}
?>

<div class="wrap">
	<?php
	screen_icon(); ?>
	<h2>Bronto Email Signup</h2>
	<form method="post" id="bronto-email-signup-form" action="<?php echo admin_url( 'admin.php?page=bronto-email-signup-options' ); ?>">
		<?php settings_fields('broes_settings'); ?>
    <?php do_settings_sections( 'broes_settings' ); ?>
		<?php if ($api_initiated) : ?>
		<h4>Select your integration options below. Then use the shortcode in a page or widget.</h4>
		<?php else : ?>
		<h4>Before gaining access to the other settings, you must enter you API Key.</h4>
		<?php endif; ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="broes_api_key">API Key</label></th>
				<td>
          <input type="text" id="broes_api_key" name="broes_api_key" class="regular-text" aria-describedby="api-key" value="<?php echo get_option('broes_api_key'); ?>" placeholder="Enter SOAP API Access Token." />
					<?php if (!$api_initiated) : ?>
					<label id="broes_api_key-error" class="error" for="broes_api_key">Your API key is incorrect.</label>
					<?php endif; ?>
          <div id="api-key">
            <p class="description">
              You can view your API keys <a href="https://app.bronto.com/mail/pref/data_exchange/" target="_blank">here</a>, or by navigating to Home -> Settings -> Data Exchange. They will be listed under the <b>SOAP API Access Tokens</b> section.
            </p>
            <p class="description">
              If you do not have any API keys, you can create a new one. <a href="http://dev.bronto.com/category/gettingstarted/soap-how-to-get-started/" target="_blank">Refer to the documentation</a> for more info.
            </p>
          </div>
        </td>
			</tr>
			<tr valign="top" class="list_ids<?php echo ( !$api_initiated ) ? ' hidden' : ''; ?>">
				<th scope="row"><label for="broes_list_ids">List ID's</label></th>
				<td>
					<p id="api-list-id" class="description">
            This select box contains all of your lists. You may select one or more lists.
          </p>
					<select multiple="multiple" name="broes_list_ids[]" id="broes_list_ids" class="widefat" size="9" aria-describedby="api-list-id">
						<?php foreach($lists as $list) : ?>
							<option value="<?php echo $list->id; ?>"<?php echo ( !empty( $broes_list_ids ) && in_array( $list->id, $broes_list_ids ) ) ? ' selected="selected"' : ''; ?>><?php echo $list->name; ?></option>
						<?php endforeach; ?>
					</select>
        </td>
			</tr>
			<tr valign="top" class="fields<?php echo ( !$api_initiated ) ? ' hidden' : ''; ?>">
				<th scope="row"><label for="broes_fields">Fields</label></th>
				<td>
					<p id="api-list-id" class="description">
            This select box contains all of your fields. You may select one or more fields.
          </p>
					<select multiple="multiple" name="broes_fields[]" id="broes_fields" class="widefat" size="9" aria-describedby="api-list-id">
						<?php foreach($fields as $field) : ?>
							<option value="<?php echo $field->id; ?>"<?php echo ( !empty( $broes_fields ) && in_array( $field->id, $broes_fields ) ) ? ' selected="selected"' : ''; ?>><?php echo $field->label; ?></option>
						<?php endforeach; ?>
					</select>
        </td>
			</tr>
		</table>
		<?php submit_button(); ?>
		<div id="test-connection-form"<?php echo ( !$api_initiated ) ? ' class="hidden"' : ''; ?>>
			<h3>Test Connection</h3>
			<p class="description">
				You may test your connection by entering an email address and clicking the Test Connection button below.
			</p>
			<p>
				<input type="text" id="broes_test_email" name="broes_test_email" class="regular-text" placeholder="Enter email address" />
			</p>
	    <p>
	      <button name="test-connection" class="button button-small bronto-test-connection">Test Connection</button>
	    </p>
		</div>
	</form>
</div>
