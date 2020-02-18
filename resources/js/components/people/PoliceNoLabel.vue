<template>
    <div>
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
            <font-awesome-icon icon="hashtag"/>
            <template v-if="police_no">
                <small class="text-muted">{{ $t('people.police_number') }}:</small>
                <span class="pr-2">
                    <text-highlight :queries="highlightTerms">{{ police_no }}</text-highlight>
                </span>
            </template>
            <em
                v-else
                @click="startEdit"
                class="text-muted clickable"
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
import { handleAjaxError } from '@/utils'
export default {
    components: {
        InlineValueEditor,
        TextHighlight
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
        highlightTerms: {
            type: Array,
            required: false,
            default: []
        },
        disabled: Boolean
    },
    data() {
        return {
            form: false,
            busy: false,
            police_no: this.value != null && this.value.length > 0 ? this.value : null,
            editValue: ''
        }
    },
    methods: {
        startEdit() {
            this.form = true
            this.editValue = this.police_no
        },
        cancelEdit() {
            this.form = false
        },
        saveEdit(val) {
            if (val == null || val.length == 0) {
                this.$nextTick(() => {
                    this.$refs.input.focus();
                })
                return
            }
            this.busy = true
            this.editValue = val
            axios.patch(this.apiUrl, {
                    'police_no': val
                })
                .then(response => {
                    var data = response.data
                    this.police_no = data.police_no
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