(function( $ ) {
	'use strict';

	$(window).on('load', function() {
		$('.bronto-email-signup').each(function() {
			var validator = $(this).validate({
				errorPlacement: function(error, element) {
					error.appendTo(element.parents('.form-group'));
				}
			});
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
			var requiredElements = $(this).find('[aria-required="true"]:not(span)').map(function(){return $(this);}).get();
			requiredElements.forEach(function(el) {
				el.rules('add', {
					required: true
				});
			});

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
						var result = response.result;
						var webformUrl = ( response.webformUrl !== '' ) ? '<br><a href="' + response.webformUrl + '" target="_blank">Manage your Preferences</a>' : '';

						var brontoSignup = new CustomEvent("brontoSignup", {
							detail: {
								response: result
							},
					    bubbles: true,
					    cancelable: true
						});
						container[0].dispatchEvent(brontoSignup);

						if ( response.result == 'error' ) {
							var message = (broes.registered_message !== '') ? broes.registered_message : response.message;
							html = '<p class="error">';
							html += message;
							html += webformUrl;
							html += '</p>';
						} else {
							var message = (broes.success_message !== '') ? broes.success_message : response.message;
							html = '<p class="success">';
							html += message;
							html += ( response.result == 'updated' ) ? webformUrl : '';
							html += '</p>';
						}
						container.find('.response').html(html);
					},
					'json'
				);
			});
		});
	});
})( jQuery );
