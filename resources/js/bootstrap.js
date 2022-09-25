import jquery from "jquery";
import 'bootstrap'
import popperJs from "popper.js";
import bsCustomFileInput from "bs-custom-file-input";

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = jquery;
    window.Popper = popperJs;

    // bs-custom-file-input
    jquery(document).ready(function() {
        bsCustomFileInput.init();
    });
} catch (e) {
    // Noop
}
