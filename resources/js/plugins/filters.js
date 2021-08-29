import Vue from "vue";

import { numberFormat, decimalNumberFormat } from "@/utils/numbers";
Vue.filter("numberFormat", numberFormat);
Vue.filter("decimalNumberFormat", decimalNumberFormat);

import { dateFormat, dateTimeFormat, timeFormat, timeFromNow } from "@/utils/date";
Vue.filter("dateFormat", dateFormat);
Vue.filter("dateTimeFormat", dateTimeFormat);
Vue.filter("timeFormat", timeFormat);
Vue.filter("timeFromNow", timeFromNow);
