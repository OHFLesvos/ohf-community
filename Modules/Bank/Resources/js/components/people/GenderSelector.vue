<template>
    <span>
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <font-awesome-icon
            v-else-if="gender == 'm'"
            icon="male"
        />
        <font-awesome-icon
            v-else-if="gender == 'f'"
            icon="female"
        />
        <template v-else-if="apiUrl != null">
            <button
                class="btn btn-warning btn-sm"
                title="Male"
                :disabled="disabled"
                @click="setGender('m')"
            >
                <font-awesome-icon icon="male"/>
            </button>
            <button
                class="btn btn-warning btn-sm"
                title="Female"
                :disabled="disabled"
                @click="setGender('f')"
            >
                <font-awesome-icon icon="female"/>
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
            required: false,
            default: null
        },
        value: {
            type: String,
            required: false,
            default: null
        },
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
                })
                .catch(handleAjaxError)
                .then(() => {
                    this.busy = false
                })
        }
    }
}
</script>