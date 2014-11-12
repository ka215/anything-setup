jQuery(document).ready(function($){
	
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
	
	var icons = {
		header: 'ui-icon-circle-arrow-e',
		activeHeader: 'ui-icon-circle-arrow-s'
	};
	var vsp = $('#collapse-VSP'), poic = $('#collapse-POIC'), cie = $('#collapse-CIE');
	$('#atsu-collapse').accordion({
		icons: icons, 
		header: 'h4', 
		heightStyle: 'content', 
		active: 2, 
		collapsible: false, 
	}).accordion('disable');
	
	$('#atsu-collapse h4').on('click', function(){
		if ($(this).attr('aria-controls') == 'collapse-VSP') {
			vsp.toggle('normal').attr('aria-hidden', 'false');
		} else {
			$('#atsu-collapse').accordion('disable');
		}
	});
	
	var $pv_key = $('#field_type');
	$pv_key.css('width', '20em');
	if (typeof $pv_key.children('option:selected').val() != 'undefined') {
		createPreviewOptionItemComponent($pv_key.children('option:selected').val());
	}
	$pv_key.on('change', function(){
		createPreviewOptionItemComponent($pv_key.children('option:selected').val());
	});
	
	function createPreviewOptionItemComponent(field_type) {
		var is_preview = false;
		if ($('#field_name').val() != '' && $('#label').val() != '' && $('#enable_field').children('option:selected').val() == 'true') {
			is_preview = true;
		}
		if (is_preview) {
			if (poic.attr('aria-hidden') == 'true') 
				poic.toggle('normal').attr('aria-hidden', 'false');
			poic.html(field_type);
		} else {
			if (poic.attr('aria-hidden') == 'false') 
				poic.toggle('normal').attr('aria-hidden', 'true');
		}
	}
	
	$('.configure-items>li>div').children('input,textarea,select').on('focus', function(){
		var label = $(this).parent().prev('label');
		var tooltip = $(this).parent().children('.baloon_box');
		var parent_size = { width: $(this).width(), height: $(this).height() }, label_size = { width: label.width(), height: label.height() }, tooltip_size = { width: tooltip.width(), height: tooltip.height() };
//		console.info(parent_size, label_size, tooltip_size);
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
