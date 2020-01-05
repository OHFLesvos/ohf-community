<template>
    <div>
        <template v-if="busy">
            <icon name="spinner" :spin="true"></icon>
            <em class="text-info">{{ newRemarks }}</em>
        </template>
        <template v-else>
            <template v-if="form">
                <div class="input-group">
                    <input
                        type="text"
                        :placeholder="lang['people::people.remarks']"
                        v-model="newRemarks"
                        class="form-control form-control-sm"
                        ref="input"
                        @keydown.enter="setRemarks"
                        @keydown.esc="cancelEdit"
                        :disabled="busy"
                    />
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary btn-sm" @click="setRemarks" :disabled="busy">
                            <icon name="check"></icon>
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" @click="cancelEdit" :disabled="busy">
                            <icon name="times"></icon>
                        </button>
                    </div>
                </div>
            </template>
            <em class="text-info clickable" v-else-if="remarks" @click="startEdit">{{ remarks }}</em>
            <em class="text-muted clickable" v-else @click="startEdit">{{ lang['people::people.click_to_add_remarks'] }}</em>
        </template>
    </div>
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
                required: true
            },
            canUpdate: Boolean,
            disabled: Boolean,
            lang: {
                type: Object,
                required: true
            },
        },
        data() {
            return {
                busy: false,
                form: false,
                remarks: this.value != null && this.value.length > 0 ? this.value : null,
                newRemarks: ''
            }
        },
        methods: {
            startEdit() {
                this.newRemarks = this.remarks
                this.form = true
                this.$nextTick(() => {
                    this.$refs.input.focus();
                })
            },
            cancelEdit() {
                this.form = false
            },
            setRemarks() {
                this.busy = true
                axios.patch(this.apiUrl, {
                        'remarks': this.newRemarks
                    })
                    .then(response => {
                        var data = response.data
                        this.remarks = this.newRemarks
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

<style scoped>
    .clickable {
        cursor: pointer;
    }
</style>