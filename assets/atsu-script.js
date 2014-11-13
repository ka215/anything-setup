jQuery(document).ready(function($){
	
	// リストモードのアクションハンドラ
	$('input[name="submit"]').on('click', function(e){
		var parse_id = $(this).attr('id').split('-');
		var action = parse_id[1];
		var option_name = $(this).attr('data-option-name');
		if (action == 'edit') {
			e.preventDefault();
			location.href += '&mode=configure&optnm='+option_name;
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
	
	var pv_key = $('#field_type');
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
console.info(conf_items);
		if (conf_items.field_name != '' && conf_items.label != '' && conf_items.enable_field == 'true') {
			is_preview = true;
		}
		if (is_preview) {
			if (!poic.head.hasClass('active')) {
				poic.head.removeAttr('data-toggle');
				poic.head.trigger('click');
			}
			var type_format = conf_items.field_type.split('_');
			var html_source = '<table class="form-table table-poic"><tr valign="top"><th scope="row">%label%</th><td><fieldset class="poic"><legend class="screen-reader-text"><span>%label%</span></legend>%elements%<p class="description">%helper_text%</p></fieldset></td></tr></table>';
			switch(type_format[0]) {
				case 'string': 
					html_elements = '<input type="text" name="'+conf_items.field_name+'" value="'+conf_items.default_string_value+'" placeholder="'+conf_items.placeholder+'">';
					html_source = html_source.replace('%label%', conf_items.label).replace('%elements%', html_elements).replace('%helper_text%', conf_items.helper_text);
					break;
				case 'password': 
					break;
				case 'integer': 
					break;
				case 'textarea': 
					break;
				case 'boolean': 
					break;
				case 'select': 
					break;
				case 'radio': 
					break;
				case 'check': 
					break;
				case 'hidden': 
					break;
				default:
					break;
			}
			poic.body.html(html_source + '<div class="poic-source"><code>' + html_elements + '</code></div>');
		} else {
			if (poic.head.hasClass('active')) { 
				poic.head.trigger('click');
				poic.head.attr('data-toggle', 'disabled');
			}
		}
	}
	if (typeof getCurrentFormData('field_type') != 'undefined') {
		createPreviewOptionItemComponent();
	}
	$('#field_type').on('change', function(){
		createPreviewOptionItemComponent();
	});
	$('#field_name, #field_type, #label, #enable_field').on('blur', function(){
		createPreviewOptionItemComponent();
	});
	
	
	// helper text for tooltip
	$('.configure-items>li>div').children('input,textarea,select').on('focus', function(){
		var label = $(this).parent().prev('label');
		var tooltip = $(this).parent().children('.baloon_box');
		var parent_size = { width: $(this).width(), height: $(this).height() }, label_size = { width: label.width(), height: label.height() }, tooltip_size = { width: tooltip.width(), height: tooltip.height() };
		if (tooltip_size.height > 0) {
			var m_y = (parent_size.height + tooltip_size.height + label_size.height + 12) * -1;
			var m_x = $(this).attr('type') == 'radio' || $(this).attr('type') == 'checkbox' ? (parent_size.width/2 - tooltip_size.width/2) / 2 : (parent_size.width/2 - tooltip_size.width/2);
			tooltip.css({ top: m_y+'px', left: m_x+'px' }).fadeIn('normal');
			$(this).on('blur', function(){
				tooltip.fadeOut('fast');
			});
		}
	});
	
});
