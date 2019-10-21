var Snackbar = require('node-snackbar');

export function showSnackbar(text, actionText, actionClass, callback) {
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

export function getAjaxErrorMessage(err) {
	var msg;
	if (err.response) {
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
	} else {
		msg = err
	}
	return msg;
}

export function handleAjaxError(err) {
	console.log(err)
    alert('Error: ' + getAjaxErrorMessage(err));
}
