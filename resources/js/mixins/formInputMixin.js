export default {
    props: {
        value: {
            type: String,
        },
        hideLabel: Boolean
    },
    computed: {
        modelValue: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit('input', val)
            }
        }
    },
    methods: {
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        focus () {
            this.$refs.input.focus()
        }
    }
}
