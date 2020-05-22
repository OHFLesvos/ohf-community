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
        return {
            valid: regex.test(value),
            data: {
                serverMessage: 'Only decimal values are available'
            }
        }
    },
    message: `{serverMessage}`
})

Vue.component('validation-provider', ValidationProvider)
Vue.component('validation-observer', ValidationObserver)
