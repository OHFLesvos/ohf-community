<template>
    <div class="mt-4">
        <h3>{{ $t('app.comments') }}</h3>

        <error-alert
            v-if="error"
            :message="error"
        />

        <p v-if="!loaded">
            <font-awesome-icon
                icon="spinner"
                spin
            />
            {{ $t('app.loading') }}
        </p>

        <template
            v-else-if="comments.length > 0"
        >
            <div
                v-for="comment in comments"
                :key="comment.id"
            >
                <comment-editor
                    v-if="editComment == comment.id"
                    :comment="comment"
                    :disabled="busy"
                    @submit="updateComment({...comment, ...$event})"
                    @cancel="editComment = null"
                />
                <comment-card
                    :comment="comment"
                    :busy="busy"
                    @edit="editComment = comment.id; editor = false"
                    @delete="deleteComment(comment)"
                />
            </div>
        </template>
        <p v-else>
            <em>{{ $t('app.no_comments_found') }}</em>
        </p>

        <template v-if="apiCreateUrl">
            <comment-editor
                v-if="editor"
                :disabled="busy"
                @submit="addComment($event)"
                @cancel="closeEditor()"
            />
            <b-button
                v-else
                :disabled="busy"
                @click="openEditor(); editComment = null"
            >
                <font-awesome-icon icon="plus-circle" />
                {{ $t('app.add_comment') }}
            </b-button>
        </template>

    </div>
</template>

<script>
import axios from '@/plugins/axios'
import { BButton } from 'bootstrap-vue'
import CommentEditor from './CommentEditor'
import CommentCard from './CommentCard'
import { handleAjaxError, getAjaxErrorMessage, showSnackbar } from '@/utils'
import ErrorAlert from '@/components/alerts/ErrorAlert'
export default {
    components: {
        BButton,
        CommentEditor,
        CommentCard,
        ErrorAlert
    },
    props: {
        apiListUrl: {
            type: String,
            required: true
        },
        apiCreateUrl: {
            type: String,
            required: false
        }
    },
    data() {
        return {
            comments: [],
            editComment: null,
            editor: false,
            error: null,
            loaded: false,
            busy: false
        }
    },
    mounted () {
        this.loadComments()
    },
    methods: {
        loadComments() {
            this.error = null
            axios.get(this.apiListUrl)
                .then((res) => {
                    this.comments = res.data.data
                    this.loaded = true
                })
                .catch(err => this.error = getAjaxErrorMessage(err))
        },
        openEditor() {
            this.editor = true
        },
        closeEditor() {
            this.editor = false
        },
        addComment(comment) {
            this.error = null
            this.busy = true
            axios.post(this.apiCreateUrl, comment)
                .then((res) => {
                    this.comments.push(res.data.data)
                    this.closeEditor()
                    showSnackbar(res.data.message)
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        },
        updateComment(comment) {
            this.error = null
            this.busy = true
            axios.put(comment.update_url, comment)
                .then((res) => {
                    const idx = this.comments.findIndex(elem => elem.id === comment.id)
                    if (idx >= 0) {
                        this.$set(this.comments, idx, res.data.data)
                    }
                    this.editComment = null
                    showSnackbar(res.data.message)
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        },
        deleteComment(comment) {
            if (confirm(this.$t('app.confirm_delete_comment'))) {
                this.busy = true
                axios.delete(comment.delete_url)
                    .then((res) => {
                        const idx = this.comments.findIndex(elem => elem.id === comment.id)
                        if (idx >= 0) {
                            this.comments.splice(idx, 1)
                        }
                        showSnackbar(res.data.message)
                    })
                    .catch(handleAjaxError)
                    .finally(() => this.busy = false)
            }
        }
    }
}
</script>
