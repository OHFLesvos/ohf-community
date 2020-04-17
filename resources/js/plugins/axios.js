/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Nprogress progress bar during axios callbacks
 */
import NProgress from 'nprogress'

NProgress.configure({
    showSpinner: false
});

// before a request is made start the nprogress
axios.interceptors.request.use(config => {
    NProgress.start()
    return config
  })

// before a response is returned stop nprogress
axios.interceptors.response.use(response => {
    NProgress.done()
    return response
  }, error => {
    NProgress.done()
    return Promise.reject(error);
  })

export default axios
