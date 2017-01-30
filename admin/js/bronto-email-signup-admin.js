(function( $ ) {
	'use strict';

	$(window).on('load', function() {

		var container = $('.bronto-email-signup');
		var sortableList = $('.sortable ul');
		var sortableSelect = $('.sortable select');
		sortableList.sortable();
		toggleForm(container, false);
		$('.sortable-form button').on('click', function(e) {
			e.preventDefault();
			var selected = $(this).parents('.sortable-form').find('option:selected');
			var val = selected.val();
			var name = selected.data('name');
			selected.prop('disabled', true).prop('selected', false);
			sortableList.append(newListItem(val, name));
		});
		$('body').on('click', '.sortable .remove', function() {
			var selected = $(this).parents('li');
			var val = selected.data('value');
			var name = selected.data('name');
			selected.remove();
			sortableSelect.find('option[value="' + val + '"]').prop('disabled', false);
		});

		var validator = $('#bronto-email-signup-form').validate({
			rules: {
				broes_api_key: 'required'
			}
		});
		$('body').on('click', '#bronto-email-signup-form .button', function(e) {
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

			var form = $(this).parents('#bronto-email-signup-form'),
				data = {
					action: action,
					_ajax_nonce: broes.nonce,
					'api_key': form.find('#broes_api_key').val(),
					'webform_url': form.find('#broes_webform_url').val(),
					'webform_secret': form.find('#broes_webform_secret').val(),
					'contact': form.find('input[name=broes_contact]:checked').val(),
					'list_ids': form.find('#broes_list_ids').val(),
					'fields': form.find('input[name="broes_fields[]"]').map(function(){return $(this).val();}).get(),
					'required_fields': form.find('input[name="broes_required_fields[]"]:checked').map(function(){return $(this).val();}).get(),
					'email': form.find('#broes_test_email').val(),
					'cta': form.find('#broes_cta').val(),
					'success_message': form.find('#broes_success_message').val(),
					'registered_message': form.find('#broes_registered_message').val()
				};

			if (!validator.form()) return false;
			toggleForm(container, true);
			$.post(
				broes.ajax_url,
				data,
				function(response) {
					var html;
					var form = (action == 'broes_add_contact') ? $('#test-connection-form') : $('.wrap');
					toggleForm(container, false);
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
					dismissNotice();
					form.prepend(html);
					if (action == 'broes_update_settings') {
						if ($('#bronto-email-signup-form .hidden').length)
							window.location.reload(true);
						$('body').animate({
							scrollTop: 0
						}, 350, 'linear');
					}
				},
				'json'
			);
		});

		$('body').on('click', '.notice-dismiss', function() {
			dismissNotice();
		});
	});

	function dismissNotice() {
		$('.notice').remove();
	}

	function newListItem(val, name) {
		var html = '<li data-name="' + name + '" data-value="' + val + '">';
		html += '<div class="field sort">';
		html += '<input type="hidden" name="broes_fields[]" value="' + val + '">';
		html += '<span>' + name + '</span>';
		html += '<span class="remove dashicons dashicons-no-alt"></span>';
		html += '</div>';
		html += '<div class="field">';
		html += '<input type="checkbox" id="' + name + '-required" name="broes_required_fields[]" value="' + val + '">';
		html += '<label for="' + name + '-required">Required</label>';
		html += '</div>';
		html += '</li>';
		return html;
	}

	function toggleForm(el, disabled) {
		if (disabled) {
			el.find('form').addClass('disabled');
			el.find('fieldset').prop('disabled');
			el.find('.loading').removeClass('hidden');
		} else {
			el.find('form').removeClass('disabled');
			el.find('fieldset').removeAttr('disabled');
			el.find('.loading').addClass('hidden');
		}
	}
})( jQuery );
