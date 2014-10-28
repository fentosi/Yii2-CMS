function showHide(_id, _val) {
	var e = $('#'+_id);
	_val = parseInt(_val);
		
	if (e.length > 0) {
		if (_val == 0) {
			e.addClass('hidden').removeClass('show');
		} else {
			e.addClass('show').removeClass('hidden');		
		}
	}
}

function copyData(_e, _target_id) {
	var target = $('#'+_target_id);
	
	if (target.length > 0 && target.val() == '') {
		target.val($(_e).val().trim());
	}
}