<template>
    <form @submit.prevent="submit" class="form-row">
        <div class="col">
            <input
                :value="value"
                ref="input"
                class="tags"
                :disabled="disabled"
                autofocus
            >
        </div>
        <div class="col-auto">
            <b-button
                variant="primary"
                size="sm"
                type="submit"
                key="submit"
                :disabled="disabled"
            >
                <font-awesome-icon icon="check" />
            </b-button>
            <b-button
                variant="secondary"
                size="sm"
                :disabled="disabled"
                key="cancel"
                @click="$emit('cancel')"
            >
                <font-awesome-icon icon="times" />
            </b-button>
        </div>
    </form>
</template>

<script>
import _ from 'lodash'
import axios from '@/plugins/axios'
import Tagify from '@yaireo/tagify'
import { BButton } from 'bootstrap-vue'
import { getAjaxErrorMessage } from '@/utils'
export default {
    components: {
        BButton
    },
    props: {
        tags: {
            required: true,
            type: Array
        },
        suggestions: {
            required: false,
            type: Array,
            default: () => []
        },
        disabled: Boolean,
        suggestionsUrl: {
            required: false,
            type: String
        }
    },
    data () {
        return {
            value: JSON.stringify(this.tags),
            tagify: undefined
        }
    },
    mounted () {
        this.tagify = new Tagify(this.$refs.input, {
            whitelist: this.suggestions
        })

        if (this.suggestionsUrl) {
            this.tagify.on('input', _.debounce(e => this.fetchTags(e.detail.value), 300))
        }
    },
    methods: {
        submit () {
            this.$emit('submit', this.tagify.value.map(t => t.value))
        },
        fetchTags (value) {
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
