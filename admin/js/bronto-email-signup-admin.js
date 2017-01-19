(function( $ ) {
	'use strict';

	$(window).on('load', function() {

		var validator = $('#bronto-email-signup-form').validate({
			rules: {
				broes_api_key: 'required'
			}
		});
		$('#bronto-email-signup-form .button').click(function(e) {
			e.preventDefault();
			var action;
			if ($(this).attr('name') == 'test-connection') {
				$('#broes_test_email').rules('add', {
					required: true,
					email: true
				});
				action = 'broes_add_contact';
			} else {
				$('#broes_test_email').rules('remove');
				action = 'broes_update_settings';
			}

			var container = $('#bronto-email-signup-form'),
				api_key = container.find('#broes_api_key').val(),
				list_ids = container.find('#broes_list_ids').val(),
				email = container.find('#broes_test_email').val(),
				data = {
					action: action,
					_ajax_nonce: broes.nonce,
					'api_key': api_key,
					'list_ids': list_ids,
					'email': email
				};

			if (!validator.form()) return false;
			$.post(
				broes.ajax_url,
				data,
				function(response) {
					var html;
					var container = (action == 'broes_add_contact') ? $('#test-connection-form') : $('.wrap');
					if ( response.result == 'success' ) {
						html = '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">';
						html += '<p>' + response.message + '</p>';
						html += '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
						html += '</div>';
					} else {
						html = '<div class="error">';
						html += '<p>' + response.message + '</p>';
						html += '</div>';
					}
					container.prepend(html);
					if (action == 'broes_update_settings') {
						$('body').animate({
							scrollTop: 0
						}, 350, 'swing');
					}
				},
				'json'
			);
		});

		$('body').on('click', '.notice-dismiss', function() {
			$(this).parents('.notice').remove();
		});
	});

})( jQuery );
