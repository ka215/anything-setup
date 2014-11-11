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
	
	
});
