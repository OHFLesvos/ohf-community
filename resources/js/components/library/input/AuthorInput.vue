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
            label: this.$t('library.author'),
            vid: 'author',
            rules: { }
        }
    }
}
</script>
