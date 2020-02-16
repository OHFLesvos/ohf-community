<template>
    <span>
        <b-form-group :label="$t('people.nationality')">
            <b-form-input
                v-model="nationality"
                list="country-list"
            />
        </b-form-group>
        <b-form-datalist id="country-list" :options="countries"/>
    </span>
</template>

<script>
import { BFormGroup, BFormInput, BFormDatalist } from 'bootstrap-vue'
export default {
    components: {
        BFormGroup,
        BFormInput,
        BFormDatalist
    },
    props: {
        value: {
            type: String,
        },
        countries: {
            type: Array,
            required: true,
        }
    },
    computed: {
        nationality: {
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