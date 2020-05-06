<template>
    <b-form-group
        :label="!hideLabel ? label : null"
    >
        <b-form-radio-group
            v-model="gender"
            required
        >
            <b-form-radio value="m">
                <font-awesome-icon icon="male"/>
                {{ $t('app.male') }}
            </b-form-radio>
            <b-form-radio value="f">
                <font-awesome-icon icon="female"/>
                {{ $t('app.female') }}
            </b-form-radio>
        </b-form-radio-group>
    </b-form-group>
</template>

<script>
import { BFormGroup, BFormRadio, BFormRadioGroup } from 'bootstrap-vue'
export default {
    components: {
        BFormGroup,
        BFormRadio,
        BFormRadioGroup
    },
    props: {
        value: {
            type: String,
        },
        hideLabel: Boolean
    },
    data () {
        return {
            label: this.$t('people.gender')
        }
    },
    computed: {
        gender: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit('input', val)
            }
        }
    }
}
</script>
