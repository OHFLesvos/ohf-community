<template>
    <div v-if="remarks || allowUpdate">
        <inline-value-editor
            v-if="form"
            :value="editValue"
            :placeholder="$t('people.remarks')"
            :disabled="disabled"
            :busy="busy"
            @submit="saveEdit"
            @cancel="cancelEdit"
        />
        <template v-else>
            <font-awesome-icon icon="comment-dots" />
            <template v-if="allowUpdate">
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
                    {{ $t('people.click_to_add_remarks') }}
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
import InlineValueEditor from '@/components/ui/InlineValueEditor'
import showSnackbar from '@/snackbar'
import peopleApi from '@/api/people'
export default {
    components: {
        InlineValueEditor
    },
    props: {
        person: {
            required: true
        },
        allowUpdate: Boolean,
        disabled: Boolean
    },
    data () {
        return {
            busy: false,
            form: false,
            remarks: this.person.remarks != null && this.person.remarks.length > 0 ? this.person.remarks : null,
            editValue: ''
        }
    },
    methods: {
        startEdit () {
            this.form = true
            this.editValue = this.remarks
        },
        cancelEdit () {
            this.form = false
        },
        async saveEdit (value) {
            this.busy = true
            this.editValue = value
            try {
                let data = await peopleApi.updateRemarks(this.person.id, value)
                this.remarks = data.remarks
                this.form = false
                showSnackbar(data.message)
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    }
}
</script>

<style scoped>
.clickable {
    cursor: pointer;
}
</style>
