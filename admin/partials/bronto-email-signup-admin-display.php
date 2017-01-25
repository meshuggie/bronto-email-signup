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
	<form method="post" id="bronto-email-signup-form" action="<?php echo admin_url( 'admin.php?page=bronto-email-signup-options' ); ?>">
		<?php settings_fields('broes_settings'); ?>
    <?php do_settings_sections( 'broes_settings' ); ?>
		<?php if ($this->api_initiated) : ?>
		<h4>Select and save your integration options below.</h4>
		<p>
			Once you've saved your options, you may embed the form into a post or page by using the shortcode.<br>
			<code>[broes_signup_form prefix-id="your-custom-id"]</code>
		</p>
		<p>
			You can also use the <b>Bronto Email Signup</b> widget, available on the <a href="<?php echo admin_url( 'widgets.php' ); ?>">Widgets page</a>, which allows you to add a sidebar widget with optional text above and below the form.
		</p>
		<?php else : ?>
		<h4>Before gaining access to the other settings, you must enter you API Key.</h4>
		<?php endif; ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="broes_api_key">API Key</label></th>
				<td>
          <input type="text" id="broes_api_key" name="broes_api_key" class="regular-text" aria-describedby="api-key" value="<?php echo esc_attr($this->broes_api_key); ?>" placeholder="Enter SOAP API Access Token." />
					<?php if (!$this->api_initiated && !empty($this->broes_api_key)) : ?>
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
			<tr valign="top" class="contact<?php echo ( !$this->api_initiated ) ? ' hidden' : ''; ?>">
				<th scope="row"><label for="broes_contact">Contact Type</label></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span>Contact Type</span></legend>
						<label><input type="radio" name="broes_contact" value="email"<?php echo ($this->broes_contact == 'email' || $this->broes_contact == '') ? ' checked="checked"' : ''; ?>> <span>Email</span></label><br>
						<label><input type="radio" name="broes_contact" value="phone"<?php echo ($this->broes_contact == 'phone') ? ' checked="checked"' : ''; ?>> <span>Phone</span></label><br>
					</fieldset>
					<p class="description">
						In Bronto, you are required to register a new contact via either their email or phone number. You must pick one.
					</p>
				</td>
			</tr>
			<tr valign="top" class="list_ids<?php echo ( !$this->api_initiated ) ? ' hidden' : ''; ?>">
				<th scope="row"><label for="broes_list_ids">List ID's</label></th>
				<td>
					<p id="api-list-id" class="description">
            This select box contains all of your lists. You may select one or more lists.
          </p>
					<select multiple="multiple" name="broes_list_ids[]" id="broes_list_ids" class="widefat" size="9" aria-describedby="api-list-id">
						<?php foreach($this->lists as $list) : ?>
							<option value="<?php echo $list->id; ?>"<?php echo ( !empty( $this->broes_list_ids ) && in_array( $list->id, $this->broes_list_ids ) ) ? ' selected="selected"' : ''; ?>><?php echo $list->name; ?></option>
						<?php endforeach; ?>
					</select>
        </td>
			</tr>
			<tr valign="top" class="fields<?php echo ( !$this->api_initiated ) ? ' hidden' : ''; ?>">
				<th scope="row"><label for="broes_fields">Fields</label></th>
				<td>
					<p id="api-list-id" class="description">
            This select box contains all of your fields. You may select one or more fields.
          </p>
					<select multiple="multiple" name="broes_fields[]" id="broes_fields" class="widefat" size="9" aria-describedby="api-list-id">
						<?php foreach($this->fields as $field) : ?>
							<option value="<?php echo $field->id; ?>"<?php echo ( !empty( $this->broes_fields ) && in_array( $field->id, $this->broes_fields ) ) ? ' selected="selected"' : ''; ?>><?php echo $field->label; ?></option>
						<?php endforeach; ?>
					</select>
        </td>
			</tr>
			<tr valign="top"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
				<th scope="row"><label for="broes_success_message">Signup Thank You</label></th>
				<td>
          <input type="text" id="broes_success_message" name="broes_success_message" class="regular-text" value="<?php echo esc_attr($this->broes_success_message); ?>" placeholder="Enter Thank You Message" />
          <p class="description">
            Enter a success message to show the user after signup.
          </p>
        </td>
			</tr>
		</table>
		<?php submit_button(); ?>
		<div id="test-connection-form"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
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
