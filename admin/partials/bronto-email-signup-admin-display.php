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

<div class="wrap bronto-email-signup">
	<?php
	screen_icon(); ?>
	<h2>Bronto Email Signup</h2>
	<img src="<?php echo plugins_url( 'bronto-email-signup/public/dist/images/bronto-loading.svg' ); ?>" class="loading">
	<form method="post" id="bronto-email-signup-form" class="disabled" action="<?php echo admin_url( 'admin.php?page=bronto-email-signup-options' ); ?>">
		<fieldset disabled>
			<?php settings_fields('broes_settings'); ?>
	    <?php do_settings_sections( 'broes_settings' ); ?>
			<?php if ($this->api_initiated) : ?>
			<h4>Select and save your integration options below.</h4>
			<p>
				Once you've saved your options, you may embed the email signup form into a post or page by using the shortcode.<br>
				<code>[broes_signup_form prefix-id="your-custom-id"]</code>
			</p>
			<p>
				You may also embed a webform into a post or page by using this shortcode (src is the "Public Link To This Webform" as seen on the Webform Preview screen).<br>
				<code>[broes_webform prefix-id="your-custom-id" src=""]</code>
			</p>
			<p>
				You can also use the <b>Bronto Email Signup</b> widget, available on the <a href="<?php echo admin_url( 'widgets.php' ); ?>">Widgets page</a>, which allows you to add a sidebar widget with optional text above and below the form.
			</p>
			<?php else : ?>
			<h4>Before gaining access to the other settings, you must enter you API Key.</h4>
			<?php endif; ?>
			<hr />
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
				<tr valign="top" class="list_ids<?php echo ( !$this->api_initiated ) ? ' hidden' : ''; ?>">
					<th scope="row" colspan="2">
						<hr />
						<h3>Signup Settings</h3>
					</th>
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
					<td class="sortable broes-sortable">
						<p id="api-list-id" class="description">
	            The select box below contains all of your Bronto fields. You may add one or more fields to your form, and sort the fields by dragging them. You may also, optionally, mark each field as a required field.
	          </p>
						<ul>
							<?php $i=0; ?>
							<?php foreach($this->broes_fields as $key => $broes_field) : ?>
								<?php
								$field_key = array_search(
									$broes_field['id'],
									array_map(function($e) {
										return $e->id;
									}, $this->fields)
								);
								$field = $this->fields[$field_key];
								?>
								<li data-name="<?php echo $field->label; ?>" data-value="<?php echo $field->id; ?>">
								  <div class="field sort">
								    <input type="hidden" name="broes_fields[<?php echo $key; ?>][id]" value="<?php echo $field->id; ?>">
								    <span class="field-label"><?php echo $field->label; ?></span>
								    <span class="remove dashicons dashicons-no-alt"></span>
								  </div>
								  <div class="field">
								    <input type="checkbox" id="<?php echo $field->label; ?>-required" name="broes_fields[<?php echo $key; ?>][required]" value="1"<?php echo ( array_key_exists( 'required', $broes_field ) ) ? ' checked="checked"' : ''; ?>>
								    <label for="<?php echo $field->label; ?>-required">Required</label>
								  </div>
									<div class="field field-sort hidden">
								    <input type="hidden" id="<?php echo $field->label; ?>-sort" name="broes_fields[<?php echo $key; ?>][sort]" value="<?php echo $i; ?>">
								  </div>
								  <?php if ( $field->type == 'text' ) : ?>
								  <div class="field field-hidden">
								    <input type="checkbox" id="<?php echo $field->label; ?>-hidden" name="broes_fields[<?php echo $key; ?>][hidden]" value="1"<?php echo ( array_key_exists( 'hidden', $broes_field ) ) ? ' checked="checked"' : ''; ?>>
								    <label for="<?php echo $field->label; ?>-hidden">Hidden</label>
								  </div>
								  <div class="field field-value<?php echo ( !array_key_exists( 'hidden', $broes_field ) ) ? ' hidden' : ''; ?>">
										<label for="<?php echo $field->label; ?>-value">Value</label>
								    <input type="text" id="<?php echo $field->label; ?>-value" name="broes_fields[<?php echo $key; ?>][value]"<?php echo ( array_key_exists( 'value', $broes_field ) ) ? ' value="' . $broes_field['value'] . '"' : ''; ?>>
								  </div>
								  <?php endif; ?>
								</li>
								<?php $i++; ?>
							<?php endforeach; ?>
						</ul>
						<div class="sortable-form">
							<select id="broes_fields" aria-describedby="api-list-id">
								<?php foreach($this->fields as $field) : ?>
									<?php if ( empty( $this->broes_fields ) || !in_array( $field->id, $this->broes_fields ) ) : ?>
									<option data-name="<?php echo $field->label; ?>" data-type="<?php echo $field->type; ?>" value="<?php echo $field->id; ?>"><?php echo $field->label; ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
							<button class="row-add-button">Add</button>
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
				<tr valign="top"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
					<th scope="row"><label for="broes_cta">Signup CTA</label></th>
					<td>
	          <input type="text" id="broes_cta" name="broes_cta" class="regular-text" value="<?php echo esc_attr($this->broes_cta); ?>" placeholder="Enter CTA" />
	          <p class="description">
	            Enter a CTA for the form button.
	          </p>
	        </td>
				</tr>
				<tr valign="top"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
					<th scope="row"><label for="broes_success_message">Signup Thank You</label></th>
					<td>
						<textarea id="broes_success_message" class="large-text code" name="broes_success_message"><?php echo esc_textarea( $this->broes_success_message ); ?></textarea>
	          <p class="description">
	            Enter a success message to show the user after signup.
	          </p>
	        </td>
				</tr>
				<tr valign="top"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
					<th scope="row"><label for="broes_registered_message">Already Registered Message</label></th>
					<td>
						<textarea id="broes_registered_message" class="large-text code" name="broes_registered_message"><?php echo esc_textarea( $this->broes_registered_message ); ?></textarea>
	          <p class="description">
	            Enter an "Already Registered" message to show the user when attempting to reregister.
	          </p>
	        </td>
				</tr>
				<tr valign="top" class="list_ids<?php echo ( !$this->api_initiated ) ? ' hidden' : ''; ?>">
					<th scope="row" colspan="2">
						<hr />
						<h3>Manage Preferences Webform <small>(optional)</small></h3>
						<p class="description">
							Fill in the fields here to generate a link to a Manage Preferences Webform. This link will be shown to users that are already signed up. See <a href="https://helpdocs.bronto.com/bmp/#task/t_bmp_content_webform_manage_pref_add_your_site.html" target="_blank">this article</a> for more information.
						</p>
					</th>
				</tr>
				<tr valign="top"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
					<th scope="row"><label for="broes_webform_url">Webform URL</label></th>
					<td>
	          <input type="text" id="broes_webform_url" name="broes_webform_url" class="regular-text" value="<?php echo esc_attr($this->broes_webform_url); ?>" placeholder="Enter Webform URL" />
						<p class="description">
							Enter the Webform URL up to the "manpref/" part. Do not enter "{CONTACT}" or "{VALIDATION_HASH}". For example,
							<code>http://app.brontostaging.com/public/webform/lookup/d41d8cd98f00b204e9800998ecf8427e/d41d8cd98f00b204e9800998ecf8427e/manpref/</code>
						</p>
	        </td>
				</tr>
				<tr valign="top"<?php echo ( !$this->api_initiated ) ? ' class="hidden"' : ''; ?>>
					<th scope="row"><label for="broes_webform_secret">Webform Shared Secret</label></th>
					<td>
	          <input type="password" id="broes_webform_secret" name="broes_webform_secret" class="regular-text" value="<?php echo esc_attr($this->broes_webform_secret); ?>" placeholder="Enter Webform Secret" />
	        </td>
				</tr>
			</table>
			<?php submit_button(); ?>
			<hr />
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
		</fieldset>
	</form>
</div>
