import Vue from "vue";

import { numberFormat, decimalNumberFormat } from "@/utils/numbers";

Vue.filter("numberFormat", numberFormat);
Vue.filter("decimalNumberFormat", decimalNumberFormat);
