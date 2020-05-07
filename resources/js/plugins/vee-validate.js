import Vue from 'vue'
import {
    ValidationProvider,
    ValidationObserver,
    extend,
    localize
} from 'vee-validate'

import * as rules from "vee-validate/dist/rules"
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule])
})

import i18n from '@/plugins/i18n'
import en from "vee-validate/dist/locale/en.json"
import de from "vee-validate/dist/locale/de.json"
localize({
    en,
    de
})
localize(i18n.locale)

// ISBN validation
import { isValidIsbn } from '@/utils'
extend('isbn', (value) => {
    if (isValidIsbn(value)) {
      return true
    }
    return i18n.t('validation.isbn', {attribute: i18n.t('validation.attributes.isbn')})
})

Vue.component('validation-provider', ValidationProvider)
Vue.component('validation-observer', ValidationObserver)
