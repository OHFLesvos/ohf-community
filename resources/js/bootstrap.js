import jquery from "jquery";
import 'bootstrap'
import popperJs from "popper.js";

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = jquery;
    window.Popper = popperJs;
} catch (e) {
    // Noop
}
