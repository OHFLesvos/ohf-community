<template>
    <b-form-group
        :label="!hideLabel ? label : null"
        :state="state"
        :invalid-feedback="invalidFeedback"
    >
        <b-form-input
            v-model.trim="modelValue"
            ref="input"
            :placeholder="hideLabel ? label : null"
            list="country-list"
            :state="state"
        />
        <b-form-datalist
            id="country-list"
            :options="countries"
        />
    </b-form-group>
</template>

<script>
import axios from '@/plugins/axios'
export default {
    props: {
        value: {
            required: true
        },
        hideLabel: Boolean
    },
    data () {
        return {
            modelValue: this.value,
            label: this.$t('people.nationality'),
            countries: [],
            state: null
        }
    },
    computed: {
        invalidFeedback () {
            return this.$t('validation.country_name', {attribute: this.label})
        }
    },
    watch: {
        modelValue (val) {
            this.$emit('input', val)
            this.state = this.$refs.input.checkValidity() && (!val || this.countries.indexOf(val) >= 0)
        }
    },
    created() {
        axios.get(`${this.route('api.countries')}?locale=en`)
            .then(res => this.countries = Object.values(res.data))
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
