function addField(btn) {
	$('.field-form-field').find('.has-error').removeClass('has-error').find('.help-block').html('');
	
	if ($(btn).find('i').hasClass('glyphicon-plus')) {

		$.ajax({
			url: 'add-field',
			type: 'post',
			async: true,
			cache: false,
			dataType: 'json',
			data: {
				'Field[name]': $('#field-name').val(), 
				'Field[type]': $('#field-type').val(),
				_csrf : yii.getCsrfToken()},
			beforeSend: function () {
				$('#field-name').prop('disabled', true);
				$('#field-type').prop('disabled', true);
				$(btn).find('i').removeClass('glyphicon-plus').addClass('fa fa-spinner fa-spin');
			},
			complete: function () {
				$('#field-name').prop('disabled', false);
				$('#field-type').prop('disabled', false);
				$(btn).find('i').removeClass('fa fa-spinner fa-spin').addClass('glyphicon-plus');
			},
			success: function(r){
	    		if (r.ok && r.text.trim() != '') {
					$('#table-form-fields').append(r.text);
	    			$('#field-name').val('');
	    			$('#field-type').val('');
	    			
	    			$('.form-sortable').sortable();

	    			$('.btn').tooltip();
	    		} else {
					for (i in r.error) {
						$('#field-'+i).parent().addClass('has-error');
						$('#field-'+i).next('.help-block').html(r.error[i]);
					}
	    		}
	  		}
	  	});
	}
}

function addFieldValue(btn, key) {
	row = $(btn).parents('.list-group-item');
	field = row.find('.add-field-value');
	
	if ($(btn).find('i').hasClass('glyphicon-plus')) {
		$.ajax({
			url: 'add-field-value',
			type: 'post',
			async: true,
			cache: false,
			dataType: 'json',
			data: {
				value: field.val(),
				key: key,
			},
			beforeSend: function () {
				field.prop('disabled', true);
				$(btn).find('i').removeClass('glyphicon-plus').addClass('fa fa-spinner fa-spin');
			},
			complete: function () {
				field.prop('disabled', false);
				$(btn).find('i').removeClass('fa fa-spinner fa-spin').addClass('glyphicon-plus');
			},
			success: function(r){
	    		if (r.ok && r.text.trim() != '') {
					field.val('');
					row.find('.field-value-sortable').append(r.text);
	    			$('.btn').tooltip();
	    		}
	  		}
	  	});
	}	
}

function removeField(btn) {
	if (confirm('Are you sure you want to delete?')) {
		if (typeof btn !== undefined) {
			var tr = $(btn).parentsUntil('li').parent();
			if (typeof tr !== "undefined") {
				tr.remove();
			}
			
		}	
	}
}

function changeStatus(btn) {
	status = $(btn).parent().find('.form-status').val();
	
	switch(status) {
		case '0':
			$(btn).parent().find('.form-status').val('1');
			$(btn).removeClass('btn-default').addClass('btn-primary');
			$(btn).find('i').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
		break;
	
		case '1':
			$(btn).parent().find('.form-status').val('0');
			$(btn).removeClass('btn-primary').addClass('btn-default');
			$(btn).find('i').removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
		break;
	}
}