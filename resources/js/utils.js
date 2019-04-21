var Snackbar = require('node-snackbar');

window.showSnackbar = function(text, actionText, actionClass, callback) {
	var args = {
		text: text,
		duration: 3000,
		pos: 'bottom-center',
		actionText: actionText ? actionText : null,
		actionTextColor: null,
		customClass: actionClass ? actionClass : null, 
	};
	if (callback) {
		args['onActionClick'] = callback;
		args['duration'] = 5000;
	}
	Snackbar.show(args);
}

window.ajaxError = function(jqXHR, textStatus) {
	var message;
	if (jqXHR.responseJSON.message) {
		if (jqXHR.responseJSON.errors) {
			message = "";
			var errors = jqXHR.responseJSON.errors;
			Object.keys(errors).forEach(function(key) {
				message += errors[key] + "\n";
			});
		} else {
			message = jqXHR.responseJSON.message;
		}
	} else {
		message = textStatus + ': ' + jqXHR.responseText;
	}
	alert(message);
}
