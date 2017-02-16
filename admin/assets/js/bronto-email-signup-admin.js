var serialize = require('form-serialize');
var count;
(function( $ ) {
	'use strict';

	$(window).on('load', function() {

		var container = $('.bronto-email-signup');
		var sortableList = $('.sortable ul');
		var sortableSelect = $('.sortable select');
		count = $('.sortable li').length;
		console.log(count);
		sortableList.sortable({
			stop: function(e, ui) {

			}
		});
		toggleForm(container, false);
		$('.sortable-form button').on('click', function(e) {
			e.preventDefault();
			var selected = $(this).parents('.sortable-form').find('option:selected');
			selected.prop('disabled', true).prop('selected', false);
			sortableList.append(newListItem( selected.val(), selected.data() ));
		});
		$('body').on('click', '.sortable .remove', function() {
			var selected = $(this).parents('li');
			var val = selected.data('value');
			var name = selected.data('name');
			selected.remove();
			sortableSelect.find('option[value="' + val + '"]').prop('disabled', false);
		});
		$('body').on('click', '.field-hidden input', function() {
			$(this).parents('.field-hidden').next('.field-value').toggleClass('hidden');
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

			var data = serialize($(this).parents('#bronto-email-signup-form')[0], { hash: true });
			data.action = action;
			data['_ajax_nonce'] = broes.nonce;

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

	function newListItem(val, data) {
		var html = '<li data-name="' + data.name + '" data-value="' + val + '">';
		html += '<div class="field sort">';
		html += '<input type="hidden" name="broes_fields[' + count + '][id]" value="' + val + '">';
		html += '<span>' + data.name + '</span>';
		html += '<span class="remove dashicons dashicons-no-alt"></span>';
		html += '</div>';
		html += '<div class="field">';
		html += '<input type="checkbox" id="' + data.name + '-required" name="broes_fields[' + count + '][required]" value="' + val + '">';
		html += '<label for="' + data.name + '-required">Required</label>';
		html += '</div>';
		if ( data.type == 'text' ) {
			html += '<div class="field field-hidden">';
			html += '<input type="checkbox" id="' + val + '-hidden" name="broes_fields[' + count + '][hidden]" value="' + val + '">';
			html += '<label for="' + val + '-hidden">Hidden</label>';
			html += '</div>';
			html += '<div class="field field-value hidden">';
			html += '<label for="' + val + '-value">Value</label>';
			html += '<input type="text" id="' + data.name + '-value" name="broes_fields[' + count + '][value]">';
			html += '</div>';
		}
		html += '</li>';
		count++;
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
