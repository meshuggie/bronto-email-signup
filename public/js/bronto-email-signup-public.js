(function( $ ) {
	'use strict';

	$(window).on('load', function() {

		$('.bronto-email-signup').each(function() {
			var validator = $(this).validate();
			var mobileNumber = $(this).find('input[name="mobileNumber"]');
			var email = $(this).find('input[name="email"]');
			if (mobileNumber.length) {
				mobileNumber.rules('add', {
					required: true,
					number: true
				});
			} else {
				email.rules('add', {
					required: true,
					email: true
				});
			}

			$(this).on('submit', function(e) {
				e.preventDefault();
				if (!validator.form()) return false;

				var container = $(this);
				var data = $(this).serializeArray().reduce(function(obj, item) {
			    obj[item.name] = item.value;
			    return obj;
				}, {});
				data.action = 'broes_add_contact';
				data._ajax_nonce = broes.nonce;
				data.expected_inputs = broes.expected_inputs;

				$.post(
					broes.ajax_url,
					data,
					function(response) {
						var html;
						if ( response.result == 'success' ) {
							html = '<p class="success">' + broes.success_message + '</p>';
						} else {
							html = '<p class="error">' + response.message + '</p>';
						}
						container.find('.response').html(html);
					},
					'json'
				);
			});
		});
	});
})( jQuery );
