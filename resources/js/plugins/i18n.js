import Vue from "vue";

import VueInternationalization from "vue-i18n";
Vue.use(VueInternationalization);

import Locale from "@/vue-i18n-locales.generated";
import lang from "@/plugins/locale";

import moment from 'moment/min/moment-with-locales';
moment.locale(lang);

export default new VueInternationalization({
    locale: lang,
    messages: Locale,
    silentTranslationWarn: true, // TODO: find a way to have it disabled in production
});
