<template>
    <span>
        <icon name="spinner" :spin="true" v-if="busy"></icon>
        <icon name="male" v-else-if="gender == 'm'"></icon>
        <icon name="female" v-else-if="gender == 'f'"></icon>
        <template v-else-if="canUpdate">
            <button class="btn btn-warning btn-sm" @click="setGender('m')" title="Male" :disabled="disabled">
                <icon name="male"></icon>
            </button>
            <button class="btn btn-warning btn-sm" @click="setGender('f')" title="Female" :disabled="disabled">
                <icon name="female"></icon>
            </button>
        </template>
    </span>
</template>

<script>
    import showSnackbar from '@app/snackbar'
    import { handleAjaxError } from '@app/utils'
    export default {
        props: {
            apiUrl: {
                type: String,
                required: true
            },
            value: {
                type: String,
                required: false,
                default: null
            },
            canUpdate: Boolean,
            disabled: Boolean
        },
        data() {
            return {
                busy: false,
                gender: this.value
            }
        },
        methods: {
            setGender(value) {
                this.busy = true
                axios.patch(this.apiUrl, {
                        'gender': value
                    })
                    .then(response => {
                        var data = response.data
                        this.gender = value
                        showSnackbar(data.message);
                        // enableFilterSelect(); // TODO
                    })
                    .catch(handleAjaxError)
                    .then(() => {
                        this.busy = false
                    })
            }
        }
    }
</script>