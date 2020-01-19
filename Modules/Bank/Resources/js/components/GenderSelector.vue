<template>
    <span>
        <icon
            v-if="busy"
            name="spinner"
            :spin="true"
        />
        <icon
            v-else-if="gender == 'm'"
            name="male"
        />
        <icon
            v-else-if="gender == 'f'"
            name="female"
        />
        <template v-else-if="canUpdate">
            <button
                class="btn btn-warning btn-sm"
                title="Male"
                :disabled="disabled"
                @click="setGender('m')"
            >
                <icon name="male"/>
            </button>
            <button
                class="btn btn-warning btn-sm"
                title="Female"
                :disabled="disabled"
                @click="setGender('f')"
            >
                <icon name="female"/>
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
                })
                .catch(handleAjaxError)
                .then(() => {
                    this.busy = false
                })
        }
    }
}
</script>