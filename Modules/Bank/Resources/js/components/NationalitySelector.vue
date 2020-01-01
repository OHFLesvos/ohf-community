<template>
    <span class="form-inline d-inline">
        <icon name="spinner" :spin="true" v-if="busy"></icon>
        <template v-else-if="nationality != null">{{ nationality }}</template>
        <template v-else-if="canUpdate">
            <template v-if="form">
                <input
                    type="text"
                    placeholder="Choose nationality"
                    v-model="newNationality"
                    class="form-control form-control-sm"
                    ref="input"
                    @keydown.enter="setNationality"
                    @keydown.esc="form = false"
                />
                <button type="button" class="btn btn-primary btn-sm" @click="setNationality">
                    <icon name="check"></icon>
                </button>
                <button type="button" class="btn btn-secondary btn-sm" @click="form = false">
                    <icon name="times"></icon>
                </button>
            </template>
            <button class="btn btn-warning btn-sm" title="Set nationality" v-else @click="form = true">
                <icon name="globe"></icon>
            </button>
        </template>
    </span>
</template>

<script>
    import Icon from '@app/components/Icon'
    import showSnackbar from '@app/snackbar'
    import { handleAjaxError } from '@app/utils'
    export default {
        components: {
            Icon
        },
        props: {
            updateUrl: {
                type: String,
                required: true
            },
            value: {
                type: String,
                required: false,
                defaul: null
            },
            canUpdate: Boolean
        },
        data() {
            return {
                busy: false,
                form: false,
                nationality: this.value != null && this.value.length > 0 ? this.value : null,
                newNationality: null
            }
        },
        methods: {
            setNationality() {
                this.busy = true
                axios.patch(this.updateUrl, {
                        'nationality': this.newNationality
                    })
                    .then(response => {
                        var data = response.data
                        this.nationality = this.newNationality
                        this.form = false
                        showSnackbar(data.message);
                        // enableFilterSelect(); // TODO
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
        }
    }
</script>