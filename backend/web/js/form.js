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
	    			
	    			makeSortable($('.sortable'));

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

function addFieldValue(btn) {
	html = '<li>'+
'	<div class="row">'+
'		<div class="col-xs-9">'+
'			<input type="text" value="" class="form-control input-sm">'+
'		</div>'+
'		<div class="col-xs-3">'+
'			<span onclick="" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="L‡that—"><i class="glyphicon glyphicon-eye-open"></i></span>'+
'			<span onclick="removeFieldValue(this);" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="TšrlŽs"><i class="glyphicon glyphicon-remove"></i></span>'+
'		</div>'+
'	</div>'+
'</li>';

	$('#table-form-fields-values').append(html);
	
	makeSortable($('.sortable'));
}

function makeSortable(elem) {
	elem.nestedSortable({
				forcePlaceholderSize: true,
				forceHelperSize: true,
				handle: 'div',
				listType: 'ul',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				tolerance: 'pointer',
				toleranceElement: '> div',
				maxLevels: 4,
				disableParentChange: true,
				protectRoot: true,
				isTree: false,
				expandOnHover: 700,
				startCollapsed: false
			});
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