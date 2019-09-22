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

window.handleAjaxError = function(err){
    var msg;
    if (err.response.data.message) {
        msg = err.response.data.message;
    }
    if (err.response.data.errors) {
        msg += "\n" + Object.entries(err.response.data.errors).map(([k, v]) => {
            return v.join('. ');
        });
    } else if (err.response.data.error) {
        msg = err.response.data.error;
    }
    alert('Error: ' + msg);
}