<template>
    <b-form-group :label="!hideLabel ? label : null">
        <b-input-group prepend="05/">
            <!-- TODO auto fill zeros -->
            <b-form-input
                v-model.trim="modelValue"
                ref="input"
                :placeholder="hideLabel ? label : null"
                type="number"
                autocomplete="off"
                no-wheel
            />
        </b-input-group>
    </b-form-group>
</template>

<script>
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
            label: this.$t('people.police_number')
        }
    },
    watch: {
        modelValue (val) {
            this.$emit('input', val)
        }
    },
    methods: {
        focus () {
            this.$refs.input.focus()
        }
    }
}
</script>
