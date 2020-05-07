<template>
    <validation-provider
        :name="label"
        :vid="vid"
        :rules="rules"
        v-slot="validationContext"
    >
        <b-form-group
            :label="!hideLabel ? label : null"
            :state="getValidationState(validationContext)"
            :invalid-feedback="validationContext.errors[0]"
        >
            <b-form-input
                v-model="modelValue"
                trim
                ref="input"
                :placeholder="hideLabel ? label : null"
                autocomplete="off"
                :state="getValidationState(validationContext)"
                pattern="^(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$"
            />
        </b-form-group>
    </validation-provider>
</template>

<script>
import formInputMixin from '@/mixins/formInputMixin'
export default {
    mixins: [formInputMixin],
    data () {
        return {
            label: this.$t('library.isbn'),
            vid: 'isbn',
            rules: {
                regex: /^(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$/,
                isbn: true
            }
        }
    }
}
</script>
