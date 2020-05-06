<template>
    <b-form-group :label="!hideLabel ? label : null">
        <b-form-input
            v-model.trim="modelValue"
            ref="input"
            :placeholder="hideLabel ? label : null"
            list="country-list"
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
            countries: []
        }
    },
    watch: {
        modelValue (val) {
            this.$emit('input', val)
        }
    },
    created() {
        axios.get(this.route('api.countries'))
            .then(res => this.countries = Object.values(res.data))
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
