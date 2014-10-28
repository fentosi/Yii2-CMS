function addAnswer(btn) {
	var ans = $('#poll-answers');
	
	$('.field-poll-answers').removeClass('has-error').find('.help-block').html('');	
		
	if (ans && $(btn).find('i').hasClass('glyphicon-plus') && ans.val().trim().length > 0) {

		$.ajax({
			url: 'add-answer',
			type: 'post',
			async: true,
			cache: false,
			dataType: 'json',
			data: {answer: ans.val().trim(), _csrf : yii.getCsrfToken()},
			beforeSend: function () {
				$('.field-poll-answers').removeClass('has-error').find('.help-block').html('');
				ans.prop('disabled', true);
				$(btn).find('i').removeClass('glyphicon-plus').addClass('fa fa-spinner fa-spin');
			},
			complete: function () {
				ans.prop('disabled', false);
				$(btn).find('i').removeClass('fa fa-spinner fa-spin').addClass('glyphicon-plus');			
			},
			success: function(r){
	    		if (r.ok && r.text.trim() != '') {
	
					$('#table-poll-answers').append(r.text);
	    			
	    			ans.val('');
	    			ans.focus();
	    			
					$('.poll-sortable').nestedSortable({
						'items': 'li',
					});
	    			
	    			renumberTable($('#table-poll-answers'));
	    		} else {
	    			$('.field-poll-answers').addClass('has-error').find('.help-block').html(r.text);
	    		}
	  		}
	  	});	

	
	}
}

function removeAnswer(btn) {
	if (confirm('Are you sure you want to delete?')) {
		if (typeof btn !== undefined) {
			var tr = $(btn).parentsUntil('li').parent();
			if (typeof tr !== "undefined") {
				tr.remove();
			}
			
			renumberTable($('#table-poll-answers'));
		}	
	}
}

function renumberTable(table) {
	if (typeof table !== "undefined" && table.length > 0) {
		var i = 0;
		table.find('li').each(function () {
			$(this).find('div.col-xs-1:first').html('<div class="text-center input-sm">'+(++i)+'</div>');
		});
	} 
}