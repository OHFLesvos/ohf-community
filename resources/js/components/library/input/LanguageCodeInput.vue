<template>
    <b-form-group
        :label="!hideLabel ? label : null"
        :state="state"
        :invalid-feedback="invalidFeedback"
    >
        <b-form-select
            v-model="modelValue"
            ref="input"
            :options="languages"
            :state="state"
        />
    </b-form-group>
</template>

<script>
import axios from '@/plugins/axios'
export default {
    props: {
        value: {
            type: String,
        },
        hideLabel: Boolean
    },
    data () {
        return {
            label: this.$t('app.language'),
            state: null,
            languages: []
        }
    },
    computed: {
        modelValue: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit('input', val)
                this.state = val && val.length > 0
                    ? this.languages.some(e => e.value == val)
                    : null
            }
        },
        invalidFeedback () {
            return null
        }
    },
    created () {
        this.loadLanguages()
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        },
        loadLanguages () {
            axios.get(this.route('api.languages'))
                .then(res => {
                    this.languages = Object.entries(res.data)
                        .map(e => {
                            return {
                                value: e[0],
                                text: e[1]
                            }
                        })
                    this.languages.unshift({
                        value: null,
                        text: this.$t('app.choose_language')
                    })
                })
        }
    }
}
</script>
