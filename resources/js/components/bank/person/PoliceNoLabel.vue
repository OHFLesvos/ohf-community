<template>
    <div v-if="police_no || allowUpdate">
        <inline-value-editor
            ref="input"
            v-if="form"
            :value="editValue"
            :placeholder="$t('people.police_number')"
            :disabled="disabled"
            :busy="busy"
            @submit="saveEdit"
            @cancel="cancelEdit"
        />
        <template v-else>
            <font-awesome-icon icon="hashtag" />
            <template v-if="police_no">
                <small class="text-muted">{{ $t('people.police_number') }}:</small>
                <span class="pr-2">
                    <text-highlight :queries="highlightTerms">{{ police_no }}</text-highlight>
                </span>
            </template>
            <em
                v-else-if="allowUpdate"
                class="text-muted clickable"
                @click="startEdit"
            >
                {{ $t('people.click_to_add_police_number') }}
            </em>
        </template>
    </div>
</template>

<script>
import InlineValueEditor from '@/components/ui/InlineValueEditor'
import TextHighlight from 'vue-text-highlight'
import showSnackbar from '@/snackbar'
import peopleApi from '@/api/people'
export default {
    components: {
        InlineValueEditor,
        TextHighlight
    },
    props: {
        person: {
            required: true
        },
        allowUpdate: Boolean,
        highlightTerms: {
            type: Array,
            required: false,
            default: () => []
        },
        disabled: Boolean
    },
    data () {
        return {
            form: false,
            busy: false,
            police_no: this.person.police_no_formatted != null && this.person.police_no_formatted.length > 0 ? this.person.police_no_formatted : null,
            editValue: ''
        }
    },
    methods: {
        startEdit () {
            this.form = true
            this.editValue = this.police_no
        },
        cancelEdit () {
            this.form = false
        },
        async saveEdit (value) {
            if (value == null || value.length == 0) {
                this.$refs.input.focus();
                return
            }
            this.busy = true
            this.editValue = value
            try {
                let data = await peopleApi.updatePoliceNo(this.person.id, value)
                this.police_no = data.police_no
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
