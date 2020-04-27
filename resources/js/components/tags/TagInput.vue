<template>
    <input
        ref="input"
        :value="valueString"
        :disabled="disabled"
        class="tags"
        autofocus
    >
</template>

<script>
import _ from 'lodash'
import axios from '@/plugins/axios'
import Tagify from '@yaireo/tagify'
import { getAjaxErrorMessage } from '@/utils'
export default {
    props: {
        value: {
            required: true,
            type: Array
        },
        suggestions: {
            required: false,
            type: Array,
            default: () => []
        },
        suggestionsUrl: {
            required: false,
            type: String
        },
        disabled: Boolean
    },
    data () {
        return {
            tagify: undefined
        }
    },
    computed: {
        valueString () {
            return JSON.stringify(this.value)
        }
    },
    mounted () {
        this.tagify = new Tagify(this.$refs.input, {
            whitelist: this.suggestions
        })

        this.$refs.input.addEventListener('change', () => {
            this.$emit('input', this.tagify.value.map(t => t.value))
        })

        if (this.suggestionsUrl) {
            this.tagify.on('input', _.debounce(e => this.fetchTags(e.detail.value), 300))
        }
    },
    methods: {
        fetchTags (value) {
            if (value.length <= 1) {
                return
            }

            this.tagify.settings.whitelist.length = 0 // reset the whitelist

            // show loading animation and hide the suggestions dropdown
            this.tagify.loading(true).dropdown.hide.call(this.tagify)

            axios.get(`${this.suggestionsUrl}?filter=${value}`)
                .then(res => {
                    // update inwhitelist Array in-place
                    const whitelist = res.data.data.map(t => t.name)
                    this.tagify.settings.whitelist.splice(0, whitelist.length, ...whitelist)
                    this.tagify.loading(false).dropdown.show.call(this.tagify, value) // render the suggestions dropdown
                })
                .catch(err => {
                    console.log(getAjaxErrorMessage(err))
                })
        }
    }
}
</script>
