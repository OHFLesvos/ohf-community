<template>
    <b-input-group>
        <b-form-input
            v-model="modelValue"
            :placeholder="placeholder"
            :state="validationState"
            trim
            autofocus
            @keydown.enter="submit"
        />
        <b-input-group-append>
            <b-button
                variant="outline-secondary"
                @click="submit"
                :disabled="submitDisabled"
            >
                <font-awesome-icon icon="search"/>
            </b-button>
        </b-input-group-append>
        <b-form-invalid-feedback>
            {{ validatorMessage }}
        </b-form-invalid-feedback>
    </b-input-group>
</template>

<script>
import { BFormInput, BFormInvalidFeedback, BInputGroup, BInputGroupAppend, BButton } from 'bootstrap-vue'
export default {
    components: {
        BFormInput,
        BFormInvalidFeedback,
        BInputGroup,
        BInputGroupAppend,
        BButton
    },
    props: {
        value: {
            type: String,
            required: false,
            default: ''
        },
        placeholder: {
            type: String,
            required: false,
            default: 'Enter code manually'
        },
        validator: {
            type: Function,
            required: false
        },
        validatorMessage: {
            type: String,
            required: false,
            default: 'Invalid input.'
        }
    },
    data() {
        return {
            modelValue: this.value
        }
    },
    computed: {
        validationState() {
            if (this.validator && this.modelValue.length > 0) {
                return this.validator(this.modelValue)
            }
            return null
        },
        submitDisabled() {
            return !this.validates()
        }
    },
    watch: {
        modelValue(val) {
            this.$emit('input', val)
        }
    },
    methods: {
        validates() {
            return !this.validator || this.validator(this.modelValue)
        },
        submit() {
            if (this.validates()) {
                this.$emit('submit')
            }
        }
    }
}
</script>