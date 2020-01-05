<template>
    <div>
        <inline-value-editor
            v-if="form"
            :value="editValue"
            :placeholder="lang['people::people.remarks']"
            :disabled="disabled"
            :busy="busy"
            @submit="saveEdit"
            @cancel="cancelEdit"
        >
        </inline-value-editor>
        <template v-else>
            <icon name="comment-dots"></icon>
            <span class="text-info clickable" v-if="remarks" @click="startEdit">
                {{ remarks }}
            </span>
            <em class="text-muted clickable" v-else @click="startEdit">
                {{ lang['people::people.click_to_add_remarks'] }}
            </em>
        </template>
    </div>
</template>

<script>
    import InlineValueEditor from './InlineValueEditor'
    import showSnackbar from '@app/snackbar'
    import { handleAjaxError } from '@app/utils'
    export default {
        components: {
            InlineValueEditor
        },
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
                editValue: ''
            }
        },
        methods: {
            startEdit() {
                this.form = true
                this.editValue = this.remarks
            },
            cancelEdit() {
                this.form = false
            },
            saveEdit(val) {
                this.busy = true
                this.editValue = val
                axios.patch(this.apiUrl, {
                        'remarks': val
                    })
                    .then(response => {
                        var data = response.data
                        this.remarks = data.remarks
                        this.form = false
                        showSnackbar(data.message)
                    })
                    .catch(err => handleAjaxError(err))
                    .then(() => this.busy = false)
            }
        }
    }
</script>

<style scoped>
    .clickable {
        cursor: pointer;
    }
</style>