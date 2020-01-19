<template>
    <div v-if="apiUrl != null || remarks">
        <inline-value-editor
            v-if="form"
            :value="editValue"
            :placeholder="lang['people::people.remarks']"
            :disabled="disabled"
            :busy="busy"
            @submit="saveEdit"
            @cancel="cancelEdit"
        />
        <template v-else>
            <font-awesome-icon icon="comment-dots"/>
            <template v-if="apiUrl != null">
                <span
                    v-if="remarks"
                    class="text-info clickable"
                    @click="startEdit"
                >
                    {{ remarks }}
                </span>
                <em
                    v-else @click="startEdit"
                    class="text-muted clickable"
                >
                    {{ lang['people::people.click_to_add_remarks'] }}
                </em>
            </template>
            <span
                v-else-if="remarks"
                class="text-info"
            >
                {{ remarks }}
            </span>
        </template>
    </div>
</template>

<script>
import InlineValueEditor from '@app/components/ui/InlineValueEditor'
import showSnackbar from '@app/snackbar'
import { handleAjaxError } from '@app/utils'
export default {
    components: {
        InlineValueEditor
    },
    props: {
        apiUrl: {
            type: String,
            required: false,
            default: null
        },
        value: {
            required: true
        },
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