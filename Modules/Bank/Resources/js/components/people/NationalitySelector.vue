<template>
    <span class="form-inline d-inline">
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <template v-else-if="nationality != null">
            {{ nationality }}
        </template>
        <template v-else-if="apiUrl != null">
            <template v-if="form">
                <input
                    ref="input"
                    v-model="newNationality"
                    type="text"
                    placeholder="Choose nationality"
                    class="form-control form-control-sm"
                    @keydown.enter="setNationality"
                    @keydown.esc="form = false"
                />
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="setNationality"
                >
                    <font-awesome-icon icon="check"/>
                </button>
                <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    @click="form = false"
                >
                    <font-awesome-icon icon="times"/>
                </button>
            </template>
            <button
                v-else
                class="btn btn-warning btn-sm"
                title="Set nationality"
                :disabled="disabled"
                @click="form = true"
            >
                <font-awesome-icon icon="globe"/>
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
            form: false,
            nationality: this.value != null && this.value.length > 0 ? this.value : null,
            newNationality: null
        }
    },
    watch: {
        form(val, oldVal) {
            if (val && !oldVal) {
                this.$nextTick(() => {
                    this.$refs.input.focus();
                })
            }
            if (!val && oldVal) {
                this.newNationality = null
            }
        }
    },
    methods: {
        setNationality() {
            this.busy = true
            axios.patch(this.apiUrl, {
                    'nationality': this.newNationality
                })
                .then(response => {
                    var data = response.data
                    this.nationality = this.newNationality
                    this.form = false
                    showSnackbar(data.message);
                })
                .catch(err => {
                    handleAjaxError(err);
                    this.busy = false
                    this.$nextTick(() => {
                        this.$refs.input.select();
                    })
                })
                .then(() => {
                    this.busy = false
                })
        }
    }
}
</script>