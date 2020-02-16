<template>
    <b-form-group
        :label="$t('people.police_number')"
        :description="$t('people.leading_zeros_added_automatically')"
    >
        <b-input-group :prepend="policeNoPrefix">
            <b-form-input
                v-model="police_no"
                type="number"
                no-wheel
                autocomplete="off"
            />
        </b-input-group>
    </b-form-group>
</template>

<script>
import { BFormGroup, BInputGroup, BFormInput } from 'bootstrap-vue'
export default {
    components: {
        BFormGroup,
        BInputGroup,
        BFormInput
    },
    props: {
        value: {
            type: [Number, String],
        }
    },
    computed: {
        police_no: {
            get() {
                return typeof this.value == 'number' ? this.value.toString() : this.value
            },
            set(val) {
                this.$emit('input', val)
            }
        },
        policeNoPrefix() {
            let str = '05/'
            const len = this.police_no.length
            str += "000000000".substr(len)
            return str
        }
    }
}
</script>