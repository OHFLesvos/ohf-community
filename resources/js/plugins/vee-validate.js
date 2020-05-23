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

extend("decimal", {
    validate: (value, { decimals = '*', separator = '.' } = {}) => {
        if (value === null || value === undefined || value === '') {
            return {
                valid: false
            }
        }
        if (Number(decimals) === 0) {
            return {
                valid: /^-?\d*$/.test(value),
            }
        }
        const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
        const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);
        return regex.test(value)
    },
    message: i18n.t('validation.numeric', { attribute: '{_field_}' })
})

extend('required_without', {
    ...rules.required_if,
    // params: ['target']
    // TODO make it work with multiple fields
    validate: (value, args) => {
        let target_value = args.target;
        return Boolean(target_value || value);
    },
    message: i18n.t('validation.required_without', { attribute: '{_field_}', values: '{target}' })
});

Vue.component('validation-provider', ValidationProvider)
Vue.component('validation-observer', ValidationObserver)
