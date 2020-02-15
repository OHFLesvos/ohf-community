<template>
    <span>
        <b-form-group :label="lang['people.nationality']">
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
        lang: {
            type: Object,
            required: true,
        },
        value: {
            type: String,
        },
        countries: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {
            nationality: this.value
        }
    },
    watch: {
        nationality(v) {
            this.$emit('input', this.nationality)
        }
    }
}
</script>