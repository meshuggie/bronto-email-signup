(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var serialize = require('form-serialize');
var count;
(function( $ ) {
	'use strict';

	$(window).on('load', function() {

		var container = $('.bronto-email-signup');
		var sortableList = $('.sortable ul');
		var sortableSelect = $('.sortable select');
		sortableList.sortable({
			stop: function(e, ui) {
				$('.sortable li').each(function(i) {
					var inputs = $(this).find('input').each(function() {
						var name = $(this).attr('name');
						var updated = name.replace(/broes_fields\[\d*\]\[(.*?)\]/, 'broes_fields[' + i + '][$1]');
						$(this).attr('name', updated);
					});
				});
			}
		});
		toggleForm(container, false);
		$('.sortable-form button').on('click', function(e) {
			e.preventDefault();
			var selected = $(this).parents('.sortable-form').find('option:selected');
			count = $('.sortable li').length;
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

},{"form-serialize":2}],2:[function(require,module,exports){
// get successful control from form and assemble into object
// http://www.w3.org/TR/html401/interact/forms.html#h-17.13.2

// types which indicate a submit action and are not successful controls
// these will be ignored
var k_r_submitter = /^(?:submit|button|image|reset|file)$/i;

// node names which could be successful controls
var k_r_success_contrls = /^(?:input|select|textarea|keygen)/i;

// Matches bracket notation.
var brackets = /(\[[^\[\]]*\])/g;

// serializes form fields
// @param form MUST be an HTMLForm element
// @param options is an optional argument to configure the serialization. Default output
// with no options specified is a url encoded string
//    - hash: [true | false] Configure the output type. If true, the output will
//    be a js object.
//    - serializer: [function] Optional serializer function to override the default one.
//    The function takes 3 arguments (result, key, value) and should return new result
//    hash and url encoded str serializers are provided with this module
//    - disabled: [true | false]. If true serialize disabled fields.
//    - empty: [true | false]. If true serialize empty fields
function serialize(form, options) {
    if (typeof options != 'object') {
        options = { hash: !!options };
    }
    else if (options.hash === undefined) {
        options.hash = true;
    }

    var result = (options.hash) ? {} : '';
    var serializer = options.serializer || ((options.hash) ? hash_serializer : str_serialize);

    var elements = form && form.elements ? form.elements : [];

    //Object store each radio and set if it's empty or not
    var radio_store = Object.create(null);

    for (var i=0 ; i<elements.length ; ++i) {
        var element = elements[i];

        // ingore disabled fields
        if ((!options.disabled && element.disabled) || !element.name) {
            continue;
        }
        // ignore anyhting that is not considered a success field
        if (!k_r_success_contrls.test(element.nodeName) ||
            k_r_submitter.test(element.type)) {
            continue;
        }

        var key = element.name;
        var val = element.value;

        // we can't just use element.value for checkboxes cause some browsers lie to us
        // they say "on" for value when the box isn't checked
        if ((element.type === 'checkbox' || element.type === 'radio') && !element.checked) {
            val = undefined;
        }

        // If we want empty elements
        if (options.empty) {
            // for checkbox
            if (element.type === 'checkbox' && !element.checked) {
                val = '';
            }

            // for radio
            if (element.type === 'radio') {
                if (!radio_store[element.name] && !element.checked) {
                    radio_store[element.name] = false;
                }
                else if (element.checked) {
                    radio_store[element.name] = true;
                }
            }

            // if options empty is true, continue only if its radio
            if (val == undefined && element.type == 'radio') {
                continue;
            }
        }
        else {
            // value-less fields are ignored unless options.empty is true
            if (!val) {
                continue;
            }
        }

        // multi select boxes
        if (element.type === 'select-multiple') {
            val = [];

            var selectOptions = element.options;
            var isSelectedOptions = false;
            for (var j=0 ; j<selectOptions.length ; ++j) {
                var option = selectOptions[j];
                var allowedEmpty = options.empty && !option.value;
                var hasValue = (option.value || allowedEmpty);
                if (option.selected && hasValue) {
                    isSelectedOptions = true;

                    // If using a hash serializer be sure to add the
                    // correct notation for an array in the multi-select
                    // context. Here the name attribute on the select element
                    // might be missing the trailing bracket pair. Both names
                    // "foo" and "foo[]" should be arrays.
                    if (options.hash && key.slice(key.length - 2) !== '[]') {
                        result = serializer(result, key + '[]', option.value);
                    }
                    else {
                        result = serializer(result, key, option.value);
                    }
                }
            }

            // Serialize if no selected options and options.empty is true
            if (!isSelectedOptions && options.empty) {
                result = serializer(result, key, '');
            }

            continue;
        }

        result = serializer(result, key, val);
    }

    // Check for all empty radio buttons and serialize them with key=""
    if (options.empty) {
        for (var key in radio_store) {
            if (!radio_store[key]) {
                result = serializer(result, key, '');
            }
        }
    }

    return result;
}

function parse_keys(string) {
    var keys = [];
    var prefix = /^([^\[\]]*)/;
    var children = new RegExp(brackets);
    var match = prefix.exec(string);

    if (match[1]) {
        keys.push(match[1]);
    }

    while ((match = children.exec(string)) !== null) {
        keys.push(match[1]);
    }

    return keys;
}

function hash_assign(result, keys, value) {
    if (keys.length === 0) {
        result = value;
        return result;
    }

    var key = keys.shift();
    var between = key.match(/^\[(.+?)\]$/);

    if (key === '[]') {
        result = result || [];

        if (Array.isArray(result)) {
            result.push(hash_assign(null, keys, value));
        }
        else {
            // This might be the result of bad name attributes like "[][foo]",
            // in this case the original `result` object will already be
            // assigned to an object literal. Rather than coerce the object to
            // an array, or cause an exception the attribute "_values" is
            // assigned as an array.
            result._values = result._values || [];
            result._values.push(hash_assign(null, keys, value));
        }

        return result;
    }

    // Key is an attribute name and can be assigned directly.
    if (!between) {
        result[key] = hash_assign(result[key], keys, value);
    }
    else {
        var string = between[1];
        // +var converts the variable into a number
        // better than parseInt because it doesn't truncate away trailing
        // letters and actually fails if whole thing is not a number
        var index = +string;

        // If the characters between the brackets is not a number it is an
        // attribute name and can be assigned directly.
        if (isNaN(index)) {
            result = result || {};
            result[string] = hash_assign(result[string], keys, value);
        }
        else {
            result = result || [];
            result[index] = hash_assign(result[index], keys, value);
        }
    }

    return result;
}

// Object/hash encoding serializer.
function hash_serializer(result, key, value) {
    var matches = key.match(brackets);

    // Has brackets? Use the recursive assignment function to walk the keys,
    // construct any missing objects in the result tree and make the assignment
    // at the end of the chain.
    if (matches) {
        var keys = parse_keys(key);
        hash_assign(result, keys, value);
    }
    else {
        // Non bracket notation can make assignments directly.
        var existing = result[key];

        // If the value has been assigned already (for instance when a radio and
        // a checkbox have the same name attribute) convert the previous value
        // into an array before pushing into it.
        //
        // NOTE: If this requirement were removed all hash creation and
        // assignment could go through `hash_assign`.
        if (existing) {
            if (!Array.isArray(existing)) {
                result[key] = [ existing ];
            }

            result[key].push(value);
        }
        else {
            result[key] = value;
        }
    }

    return result;
}

// urlform encoding serializer
function str_serialize(result, key, value) {
    // encode newlines as \r\n cause the html spec says so
    value = value.replace(/(\r)?\n/g, '\r\n');
    value = encodeURIComponent(value);

    // spaces should be '+' rather than '%20'.
    value = value.replace(/%20/g, '+');
    return result + (result ? '&' : '') + encodeURIComponent(key) + '=' + value;
}

module.exports = serialize;

},{}]},{},[1])