import Snackbar from 'node-snackbar'

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
		if (!msg) {
			msg = `Error ${err.response.status}: ${err.response.statusText}`
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

export function isDateString(value) {
	return value.match('^[0-9]{4}-[0-9]{2}-[0-9]{2}$')
}

export function dateOfBirthToAge(dateOfBirth) {
	var today = new Date();
	var birthDate = new Date(dateOfBirth);
	var age = today.getFullYear() - birthDate.getFullYear();
	var m = today.getMonth() - birthDate.getMonth();
	if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
		age--;
	}
	return age;
}

export function todayDate() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1; //January is 0!

	var yyyy = today.getFullYear();
	if(dd < 10) {
		dd='0' + dd;
	}
	if(mm < 10) {
		mm='0' + mm;
	}
	return yyyy + '-' + mm + '-' + dd;
}

export function isAlphaNumeric(value) {
	return /^[a-zA-Z0-9]+$/.test(value)
}

export function roundWithDecimals(value, decimals) {
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}
