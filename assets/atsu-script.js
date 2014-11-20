jQuery(document).ready(function($){
	
	// Enterキーによる Submit を停止
	$(document).on("keypress", "input:not(.allow_submit)", function(event) {
		return event.which !== 13;
	});
	
	// リストモードのアクションハンドラ
	$('input[name="submit"]').on('click', function(e){
		var parse_id = $(this).attr('id').split('-');
		var action = parse_id[1];
		var option_name = $(this).attr('data-option-name');
		if (action == 'edit') {
			e.preventDefault();
			location.href += '&mode=configure&optnm='+option_name+'#atsu-cie-head';
		} else {
			$('#atsu-admin-form').children('input[name="action"]').val(action);
			$('#atsu-admin-form').children('input[name="option_name"]').val(option_name);
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
	vsp.body.hide();
	poic.head.attr('data-toggle', 'disabled');
	poic.body.hide();
	cie.head.attr('data-toggle', 'disabled');
	switchAccordionIcon();
	
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
	$('[for="validate_regx"]').parent().append('<div class="preset_regx"><select name="preset_regx" id="preset_regx"><option value=""> Presets </option></select></div>');
	var regx_offset = $('#validate_regx').position().top, regx_width = $('#validate_regx').width();
	$('.preset_regx').css({ top: regx_offset-2+'px', left: 280+'px' }).show();
	var regx_preset = {};
	
	
	
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
