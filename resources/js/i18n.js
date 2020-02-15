import Vue from 'vue'

import VueInternationalization from 'vue-i18n'
Vue.use(VueInternationalization)

import Locale from './vue-i18n-locales.generated';
const hl = window.document.head.querySelector('meta[name="lang"]');
const lang = hl ? hl.content : 'en'

export default new VueInternationalization({
    locale: lang,
    messages: Locale
});