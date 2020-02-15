<template>
    <span>
        <p>{{ $t('people.gender') }}</p>
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
    </span>
</template>

<script>
import { BFormRadio, BFormRadioGroup } from 'bootstrap-vue'
export default {
    components: {
        BFormRadio,
        BFormRadioGroup
    },
    props: {
        value: {
            type: String,
        }
    },
    data() {
        return {
            gender: this.value
        }
    },
    watch: {
        gender(v) {
            this.$emit('input', this.gender)
        }
    }
}
</script>