jQuery(document).ready(function($){
	
	// Enterキーによる Submit を停止
	$(document).on("keypress", "input:not(.allow_submit)", function(event) {
		return event.which !== 13;
	});
	
	// 全Submitボタンのアクションハンドラ
	$('input[type="submit"]').on('click', function(e){
		e.preventDefault();
		var parse_id = $(this).attr('id').split('-');
		var action = typeof parse_id[1] != 'undefined' ? parse_id[1] : parse_id[0];
		var option_name = typeof $(this).attr('data-option-name') != 'undefined' ? $(this).attr('data-option-name') : $('#atsu-admin-form').children('input[name="option_name"]').val();
		if (action == 'edit') {
			var redirect_url = location.href.replace(/mode\=[A-Za-z0-9]+(|&)/, 'mode=configure');
			//location.href = redirect_url + '&optnm='+option_name+'#atsu-cie-head';
			location.href = redirect_url + '&optnm='+option_name;
		} else {
			$('#atsu-admin-form').children('input[name="action"]').val(action);
			$('#atsu-admin-form').children('input[name="option_name"]').val(option_name);
			if (action == 'add_item') {
				//$('#atsu-admin-form').action = location.href.replace(/#[A-Za-z0-9\-_]+$/, '#atsu-vsp-head');
			}
			if (action == 'update_order') {
				var options_order = getCurrentOptionOrder(true);
				for (var i=0, oo_len=options_order.length; i<oo_len; i++) {
					$('#atsu-admin-form').append('<input type="hidden" name="atsu_setting_options[options_order][]" value="'+options_order[i]+'">');
				}
			}
			$('#atsu-admin-form').trigger('submit');
		}
	});
	
	// コンフィグモードのアクションハンドラ
	var icons = {
		normal: 'dashicons-arrow-right-alt2',
		active: 'dashicons-arrow-down-alt2'
	};
	var vsp = { head: $('#atsu-vsp-head'), body: $('#atsu-vsp-body')}, poic = { head: $('#atsu-poic-head'), body: $('#atsu-poic-body') }, cie = { head: $('#atsu-cie-head'), body: $('#atsu-cie-body') };
	
	$('.accordion-toggle').on('click', function(){
		if ($(this).data('toggle') != 'disabled') {
			$(this).next().slideToggle('fast');
			$(this).toggleClass('active');
			switchAccordionIcon();
		}
		if (vsp.head.hasClass('active')) {
			loadVSPContent();
		}
	});
	function switchAccordionIcon() {
		$('.accordion-toggle').each(function(){
			var icon = $(this).children('.accordion-icon').children('.dashicons');
			if ($(this).hasClass('active')) {
				icon.removeClass(icons.normal).addClass(icons.active);
			} else {
				icon.removeClass(icons.active).addClass(icons.normal);
			}
		});
	}
	// accordion init
	$(window).on('load', function(){
		vsp.body.hide();
		poic.head.attr('data-toggle', 'disabled');
		poic.body.hide();
		cie.head.attr('data-toggle', 'disabled');
		switchAccordionIcon();
		
		var urlObj = parseURL(location.href);
//console.info(urlObj);
		if (typeof urlObj.query != 'undefined') {
			if (typeof urlObj.query.mode == 'undefined') {
				location.href = urlObj.path + urlObj.search + '&mode=list';
			}
		}
		
		$('.button-brick.group-update_item').hide();
	});
	
	// sortable init
	$('#sortable').sortable({
		axis: 'y', 
		containment: 'parent', 
		items: 'tr', 
		placeholder: 'sortable-helper', 
	});
	$('#sortable').disableSelection();
	
	// sortable コントローラボタンのハンドラ
	$('#sortable button').on('click', function(e){
		var parse_ary = $(this).attr('id').split('-');
		e.preventDefault();
		if (parse_ary[1] == 'edit_item') {
			$('#atsu-admin-form').children('input[name="action"]').val('update_item');
			var item_name = $(this).attr('data-option-item');
			var item_order = $(this).attr('data-order-index');
			var post_data = {
				action: 'atsu_ajax_handler', 
				handler: parse_ary[1], 
				'option_name': $('#atsu-admin-form input[name="option_name"]').val(), 
				'item_name': $(this).attr('data-option-item'), 
				'item_order': $(this).attr('data-order-index'), 
				nonce: $('#atsu-admin-form input[name="_wpnonce"]').val(), 
			};
			$.ajax({
				type: 'POST', 
				url: '/wp-admin/admin-ajax.php', 
				data: post_data, 
				dataType: 'json' 
			}).done(function(res, stat){
				if (stat == 'success') {
					if (res['field_name'] != '') {
						$('input#field_name').removeAttr('value data-default').val(res['field_name']);
						if ($(document).find('#before_field_name').length == 0) {
							$('input#field_name').after('<input type="hidden" name="atsu_setting_options[before_field_name]" id="before_field_name" value="'+ res['field_name'] +'">');
						} else {
							$('input#before_field_name').removeAttr('value data-default').val(res['field_name']);
						}
						$('input#default_string_value').removeAttr('value data-default').val(res[1] != '' ? res[1] : '');
						$('input#placeholder').removeAttr('value data-default').val(res[2] != '' ? res[2] : '');
						$('input#validate_regx').removeAttr('value data-default').val(res[3] != '' ? res[3] : '');
						$('input#label').removeAttr('value data-default').val(res[4] != '' ? res[4] : '');
						$('textarea#helper_text').removeAttr('value data-default').val(res[5] != '' ? res[5] : '');
						$('select#enable_field>option').each(function(){
							if ($(this).val() == String(res[6])) {
								$(this).attr('selected', 'selected');
							} else {
								$(this).removeAttr('selected');
							}
						});
						if (res[7].length > 0) {
							for (var key in res[7]) {
								$('input#'+ key).val(res[7][key]);
							}
						}
						if (res['field_order'] > 0) {
							$('input#field_order').removeAttr('value data-default').attr('data-default', res['field_order']).val(res['field_order']).prop('valueAsNumber', res['field_order']);
						}
						$('.button-brick.group-add_item').hide();
						$('.button-brick.group-update_item').removeClass('hide-content').show();
						$('select#field_type>option').each(function(){
							if ($(this).val() == res[0]) {
								$(this).attr('selected', 'selected');
							} else {
								$(this).removeAttr('selected');
							}
						}).trigger('change');
						$(document).scrollTop($('#atsu-poic-head').position().top + 40);
					}
				} else {
					// ajax error
				}
			}).always(function(){
				// Do nothing
			});
			
			
		} else if (parse_ary[1] == 'delete_item') {
			$(this).parent().parent().fadeOut('fast', function(){
				$(this).remove();
			});
		} else {
			return false;
		}
	});
	
	function getCurrentOptionOrder(and_update){
		var now_order = 0, order_items = [];
		$('#sortable button[id^="atsu-edit_item-iconbtn-"]').each(function(){
			order_items[now_order] = $(this).attr('data-option-item');
			now_order++;
			if (and_update) {
				$(this).attr('data-order-index', now_order);
			}
		});
		return order_items;
	}
	
	function getCurrentFormData(field_name){
		var conf_items = {};
		$('#atsu-admin-form input, #atsu-admin-form textarea, #atsu-admin-form select').each(function(){
			var conf_item_name = $(this).attr('name').replace('[]', '').replace(/^atsu_setting_options\[(.*)+\]$/g, '$1');
			if ($(this).attr('type') != 'hidden' && $(this).attr('type') != 'submit' && $(this).attr('type') != 'button') {
				if ($(this).attr('type') == 'radio' || $(this).attr('type') == 'checkbox') {
					if (typeof conf_items[conf_item_name] == 'undefined') {
						conf_items[conf_item_name] = [];
					}
					if (typeof $(this).filter(':checked').val() != 'undefined') {
						conf_items[conf_item_name].push($(this).filter(':checked').val());
					}
				} else if ($(this).context.tagName == 'select') {
					conf_items[conf_item_name] = $(this).val();
				} else {
					conf_items[conf_item_name] = $(this).val();
				}
			}
		});
		if (field_name != '' && field_name != 'all') {
			return conf_items[field_name];
		} else {
			return conf_items;
		}
	}
	function createPreviewOptionItemComponent() {
		var is_preview = false;
		var conf_items = getCurrentFormData('');
		if (conf_items.field_name != '' && conf_items.label != '' && conf_items.enable_field == 'true') {
			is_preview = true;
		}
		if (is_preview) {
			if (!poic.head.hasClass('active')) {
				poic.head.removeAttr('data-toggle');
				poic.head.trigger('click');
			}
			var type_format = conf_items.field_type.split('_');
			var html_elements, add_attr = '';
			var html_source = '<table class="form-table table-poic"><tr valign="top"><th scope="row">%label%</th><td><fieldset class="poic"><legend class="screen-reader-text"><span>%label%</span></legend>%elements%<p class="description">%helper_text%</p></fieldset></td></tr></table>';
			switch(type_format[0]) {
				case 'string': 
					html_elements = '<input type="%input_type%" name="%prepend%'+conf_items.field_name+'%append%" value="'+conf_items.default_string_value+'" placeholder="'+conf_items.placeholder+'" size="'+conf_items.field_size+'"%optional_attributes%>';
					html_elements = html_elements.replace(/%input_type%/m, 'text');
					break;
				case 'password': 
					html_elements = '<input type="password" name="%prepend%'+conf_items.field_name+'%append%" value="'+conf_items.default_string_value+'" placeholder="'+conf_items.placeholder+'" size="'+conf_items.field_size+'"%optional_attributes%>';
					break;
				case 'integer': 
					html_elements = '<input type="%input_type%" name="%prepend%'+conf_items.field_name+'%append%" value="'+conf_items.default_string_value+'" placeholder="'+conf_items.placeholder+'" size="'+conf_items.field_size+'"%optional_attributes%>';
					html_elements = html_elements.replace(/%input_type%/m, 'number');
					break;
				case 'textarea': 
					html_elements = '<textarea name="%prepend%'+conf_items.field_name+'%append%" cols="'+conf_items.cols+'" rows="'+conf_items.rows+'" placeholder="'+conf_items.placeholder+'"%optional_attributes%>'+conf_items.default_string_value+'</textarea>';
					break;
				case 'boolean': 
					var defval = conf_items.default_string_value;
					if (typeof defval != 'undefined' && defval != '') {
						if (defval.match(/^(true|false|0|1)$/i) != '') 
							defval = defval.replace(/^(true|0)$/i, 0).replace(/^(false|1)$/i, 1);
					}
					html_elements = '<select name="%prepend%'+conf_items.field_name+'%append%"%optional_attributes%>'+"\n";
					html_elements += '<option'+(defval == 0 ? ' selected="selected"' : '')+' value="true">Yes</option>'+"\n";
					html_elements += '<option'+(defval == 1 ? ' selected="selected"' : '')+' value="false">No</option>'+"\n";
					html_elements += '</select>';
					break;
				case 'select': 
					var opt_values = conf_items.item_value.split(',');
					var opt_dispnm = conf_items.item_display_name.split(',');
					var opt_count = opt_values.length > opt_dispnm.length ? opt_values.length : opt_dispnm.length;
					var def_values = conf_items.default_string_value.split(',');
					html_elements = '<select name="%prepend%'+conf_items.field_name+'%append%"%optional_attributes%>'+"\n";
					if (opt_count > 1) {
						for (var i=0; i<opt_count; i++) {
							var set_value = typeof opt_values[i] != 'undefined' && opt_values[i] != '' ? opt_values[i] : i;
							var set_display = typeof opt_dispnm[i] != 'undefined' && opt_dispnm[i] != '' ? opt_dispnm[i] : opt_values[i];
							var is_selected = false;
							def_values.forEach(function(e){
								if (e.match(/[a-zA-Z]+/i) != '') {
									if (e == set_value) 
										is_selected = true;
								}
							});
							if (!is_selected) {
								for (idx in def_values) {
									if (i == def_values[idx]) {
										is_selected = true;
										break;
									}
								}
							}
							html_elements += '<option'+(is_selected ? ' selected="selected"' : '')+' value="'+set_value+'">'+set_display+'</option>'+"\n";
						}
					}
					html_elements += '</select>';
					if (type_format[1] == 'multi') {
						add_attr += ' multiple';
						var row_size = typeof conf_items.rows != 'undefined' && conf_items.rows > 0 ? conf_items.rows : opt_count;
						add_attr += ' size="'+row_size+'"';
					} else {
						var row_size = typeof conf_items.rows != 'undefined' && conf_items.rows > 1 ? conf_items.rows : 1;
						add_attr += row_size > 1 ? ' size="'+row_size+'"' : '';
					}
					break;
				case 'radio': 
					var opt_values = conf_items.item_value.split(',');
					var opt_dispnm = conf_items.item_display_name.split(',');
					var opt_count = opt_values.length > opt_dispnm.length ? opt_values.length : opt_dispnm.length;
					var def_value = conf_items.default_string_value;
					html_elements = '';
					for (var i=0; i<opt_count; i++) {
						var set_value = typeof opt_values[i] != 'undefined' && opt_values[i] != '' ? opt_values[i] : i;
						var set_display = typeof opt_dispnm[i] != 'undefined' && opt_dispnm[i] != '' ? opt_dispnm[i] : opt_values[i];
						var is_checked = false;
						if (def_value != '') {
							if (def_value.match(/[a-zA-Z]+/i) != '') {
								if (def_value == set_value) 
									is_checked = true;
							}
							if (!is_checked) {
								if (i == Number(def_value)) 
									is_checked = true;
							}
						}
						var elm_id = conf_items.field_name+'-'+(i+1);
						html_elements += '<input type="radio" name="%prepend%'+conf_items.field_name+'%append%" id="'+elm_id+'" value="'+set_value+'"'+(is_checked ? ' checked="checked"' : '')+'><label for="'+elm_id+'">'+set_display+'</label>'+"\n";
					}
					break;
				case 'check': 
					var opt_values = conf_items.item_value.split(',');
					var opt_dispnm = conf_items.item_display_name.split(',');
					var opt_count = opt_values.length > opt_dispnm.length ? opt_values.length : opt_dispnm.length;
					var def_values = conf_items.default_string_value != '' ? conf_items.default_string_value.split(',') : [];
					html_elements = '';
					for (var i=0; i<opt_count; i++) {
						var set_value = typeof opt_values[i] != 'undefined' && opt_values[i] != '' ? opt_values[i] : i;
						var set_display = typeof opt_dispnm[i] != 'undefined' && opt_dispnm[i] != '' ? opt_dispnm[i] : opt_values[i];
						var is_checked = false;
						if (def_values.length > 0) {
							def_values.forEach(function(e){
								if (e.match(/[a-zA-Z]+/i) != '') {
									if (e === set_value) 
										is_checked = true;
								}
							});
							if (!is_checked) {
								for (idx in def_values) {
									if (i === Number(def_values[idx])) {
										is_checked = true;
										break;
									}
								}
							}
						}
						var elm_id = conf_items.field_name+'-'+(i+1);
						html_elements += '<input type="checkbox" name="%prepend%'+conf_items.field_name+'%append%" id="'+elm_id+'" value="'+set_value+'"'+(is_checked ? ' checked="checked"' : '')+'><label for="'+elm_id+'">'+set_display+'</label>'+"\n";
					}
					break;
				case 'hidden': 
					html_elements = '<input type="hidden" name="%prepend%'+conf_items.field_name+'%append%" value="'+conf_items.default_string_value+'"%optional_attributes%>';
					html_source = '<div>%elements%</div>';
					break;
				default:
					break;
			}
			if (conf_items.validate_regx != '') {
				add_attr += ' pattern="'+conf_items.validate_regx+'"'
			}
			if (conf_items.extra.some(function(v){ return v === 'require' })) {
				require_badge =  ' <span class="badge badge-notice">require</span>';
				add_attr += ' required';
			} else {
				require_badge = '';
			}
			html_elements = html_elements.replace(/%optional_attributes%/gm, add_attr);
			var plane_source = html_elements.replace(/%(prepend|append)%/gm, '').replace(/\"/gm, '&quot;').replace(/>/gm, '&gt;').replace(/</gm, '&lt;').replace(/\n/gm, '<br>');
			html_elements = html_elements.replace(/%prepend%/gm, 'poic[').replace(/%append%/gm, ']');
			html_source = html_source.replace('%label%', conf_items.label+require_badge).replace('%elements%', html_elements).replace('%helper_text%', conf_items.helper_text);
			poic.body.html(html_source + '<div class="poic-source"><code>' + plane_source + '</code></div>');
		} else {
			if (poic.head.hasClass('active')) { 
				poic.head.trigger('click');
				poic.head.attr('data-toggle', 'disabled');
			}
		}
	}
	if (typeof getCurrentFormData('field_type') != 'undefined') {
		// init action for CIE brick
		handleConfigurationItemsEditing();
		createPreviewOptionItemComponent();
	}
	$('#field_type').on('change', function(){
		handleConfigurationItemsEditing();
		createPreviewOptionItemComponent();
	});
	$('#field_name, #field_type, #label, #enable_field, #default_string_value, #helper_text, #placeholder, #unit, #validate_regx, #item_display_name, #item_value, #field_size, #cols, #rows').on('blur', function(){
		createPreviewOptionItemComponent();
	});
	$('input[id=extra-0]').on('click', function(){
		createPreviewOptionItemComponent();
	});
	
	// for CIE brick
	function handleConfigurationItemsEditing() {
		var field_type = getCurrentFormData('field_type').split('_');
		switch(field_type[0]) {
			case 'string':
				var exclude_field = ['item_value', 'item_display_name', 'unit', 'cols', 'rows'];
				break;
			case 'password':
				var exclude_field = ['item_value', 'item_display_name', 'unit', 'cols', 'rows'];
				break;
			case 'integer':
				var exclude_field = ['item_value', 'item_display_name', 'cols', 'rows'];
				break;
			case 'textarea':
				var exclude_field = ['item_value', 'item_display_name', 'field_size', 'unit'];
				break;
			case 'boolean':
				var exclude_field = ['item_value', 'item_display_name', 'placeholder', 'validate_regx', 'field_size', 'unit', 'cols', 'rows'];
				break;
			case 'select':
				var exclude_field = ['placeholder', 'validate_regx', 'field_size', 'unit', 'cols'];
				if (field_type[1] == 'single') 
					exclude_field.push('rows');
				break;
			case 'radio':
				var exclude_field = ['placeholder', 'validate_regx', 'field_size', 'unit', 'cols', 'rows'];
				break;
			case 'check':
				var exclude_field = ['placeholder', 'validate_regx', 'field_size', 'unit', 'cols', 'rows'];
				break;
			case 'hidden':
				var exclude_field = ['extra', 'item_value', 'item_display_name', 'placeholder', 'helper_text', 'field_size', 'unit', 'cols', 'rows'];
				break;
			default:
				var exclude_field = [];
				break;
		}
		$('.configure-items>li').each(function(){
			var chk_str = $(this).children('label').attr('for');
			var elm = $(this).find('#'+chk_str);
			if (typeof elm.attr('data-default') == 'undefined') {
				if (elm.val() != '') 
					elm.attr('data-default', elm.val());
			}
			if (exclude_field.some(function(val){ return val === chk_str })) {
				elm.val('');
				$(this).hide();
			} else {
				if (typeof elm.attr('data-default') != 'undefined' && chk_str !== 'field_type') 
					elm.val(elm.attr('data-default'));
				$(this).show();
			}
		});
		$('.baloon_box').fadeOut('fast');
	}
	
	// preset regx handler
	// after php output, jquery append is good (non ajax)
	if ($(document).find('#validate_regx').length != 0) {
		$('[for="validate_regx"]').parent().append('<div class="preset_regx"><select name="preset_regx" id="preset_regx"><option value=""> Presets </option></select></div>');
		var regx_offset = $('#validate_regx').position(), regx_width = $('#validate_regx').width();
		$('.preset_regx').css({ top: regx_offset.top-2+'px', left: 280+'px' }).show();
		var regx_preset = {};
	}
	
	// VSP brick handler
	function loadVSPContent() {
console.info('Now load!');
		
		
	}
	
	
	// helper text for tooltip
	$('.configure-items>li>div').children('input,textarea,select').on('focus', function(){
		var label = $(this).parent().prev('label');
		var tooltip = $(this).parent().children('.baloon_box');
		tooltip.fadeOut('fast');
		var parent_size = { width: $(this).width(), height: $(this).height() }, label_size = { width: label.width(), height: label.height() }, tooltip_size = { width: tooltip.width(), height: tooltip.height() };
		if (tooltip_size.height > 0) {
//			var m_y = (parent_size.height + tooltip_size.height + label_size.height + 12) * -1;
			var m_y = (tooltip_size.height + 12) * -1;
			var m_x = $(this).attr('type') == 'radio' || $(this).attr('type') == 'checkbox' ? (parent_size.width/2 - tooltip_size.width/2) / 2 : (parent_size.width/2 - tooltip_size.width/2);
			tooltip.css({ top: m_y+'px', left: m_x+'px' }).fadeIn('normal');
		}
	});
	$('.configure-items>li>div').children('input,textarea,select').on('blur', function(){
		$('.baloon_box').fadeOut('fast');
	});
});

// non jQuery lib-function
function parseURL(url, property){
	var parse_data, tmp_ary, elm = document.createElement('a');
	elm.href = url == '' ? location.href : url;
	tmp_ary = elm.search.substr(1).split('&');
	var queries = {};
	for (var i=0,len=tmp_ary.length; i<len; i++) {
		var kav = tmp_ary[i].split('=');
		queries[kav[0]] = kav[1];
	}
	parse_data = {
		full: elm.href, 
		protocol: elm.protocol, 
		host: elm.hostname, 
		relative: elm.relative, 
		path: elm.pathname, 
		directory: elm.directory, 
		file: elm.file, 
		search: elm.search, 
		query: queries, 
		hash: elm.hash, 
		fragment: elm.hash
	};
	if (property == '' || typeof parse_data[property] == 'undefined') {
		return parse_data;
	} else {
		return parse_data[property];
	}
}

