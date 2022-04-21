export default {
    methods: {
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
    }
}
