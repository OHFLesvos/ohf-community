import Snackbar from 'node-snackbar'
import palette from 'google-palette'

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
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals)
}

/**
 * Converts Hex color (#rrggbbaa) to RGBA.
 * See https://css-tricks.com/converting-color-spaces-in-javascript/
 *
 * @param string hex color code
 */
export function hexAToRGBA(h) {
    let r = 0, g = 0, b = 0, a = 1;

    if (h.length == 5) {
      r = "0x" + h[1] + h[1];
      g = "0x" + h[2] + h[2];
      b = "0x" + h[3] + h[3];
      a = "0x" + h[4] + h[4];

    } else if (h.length == 9) {
      r = "0x" + h[1] + h[2];
      g = "0x" + h[3] + h[4];
      b = "0x" + h[5] + h[6];
      a = "0x" + h[7] + h[8];
    }
    a = +(a / 255).toFixed(3);

    return "rgba(" + +r + "," + +g + "," + +b + "," + a + ")";
}

export function ucFirst(value) {
    return value.replace(/^\w/, c => c.toUpperCase())
}

/**
 * Assign colors to chart.js datasets
 */
export function applyColorPaletteToDatasets(datasets) {
    const colorPalette = palette('tol', Math.min(datasets.length, 12))
    for (var i = 0; i < datasets.length; i++) {
        const hexcolor = '#' + colorPalette[i %  colorPalette.length]
        datasets[i].backgroundColor = hexAToRGBA(hexcolor + '80')
        datasets[i].borderColor = hexcolor
        datasets[i].borderWidth = 1
    }
}
