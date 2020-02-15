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
            type: String,
        }
    },
    data() {
        return {
            police_no: this.value
        }
    },
    computed: {
        policeNoPrefix() {
            let str = '05/'
            const len = this.police_no.length
            str += "000000000".substr(len)
            return str
        }
    },
    watch: {
        police_no(v) {
            this.$emit('input', this.police_no)
        }
    }
}
</script>