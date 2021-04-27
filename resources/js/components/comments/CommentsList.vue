<template>
    <div>
        <!-- Error -->
        <b-alert v-if="error" variant="danger" show>
            {{ error }}
        </b-alert>

        <!-- Loading -->
        <p v-if="!loaded && !error">
            <font-awesome-icon icon="spinner" spin />
            {{ $t("Loading...") }}
        </p>

        <!-- Comments -->
        <template v-else-if="comments.length > 0">
            <div v-for="comment in comments" :key="comment.id">
                <comment-editor
                    v-if="editComment == comment.id"
                    :comment="comment"
                    :disabled="busy"
                    @submit="updateComment({ ...comment, ...$event })"
                    @cancel="editComment = null"
                />
                <comment-card
                    v-else
                    :comment="comment"
                    :busy="busy"
                    @edit="
                        editComment = comment.id;
                        editor = false;
                    "
                    @delete="deleteComment(comment)"
                />
            </div>
        </template>
        <p v-else>
            <em>{{ $t("No comments found.") }}</em>
        </p>

        <!-- New comment -->
        <template v-if="apiCreateMethod && editComment == null">
            <comment-editor
                v-if="editor"
                :disabled="busy"
                @submit="addComment($event)"
                @cancel="closeEditor()"
            />
            <p v-else>
                <b-button
                    variant="primary"
                    :disabled="busy"
                    @click="
                        openEditor();
                        editComment = null;
                    "
                >
                    <font-awesome-icon icon="plus-circle" />
                    {{ $t("Add comment") }}
                </b-button>
            </p>
        </template>
    </div>
</template>

<script>
import commentsApi from "@/api/comments";
import { BButton } from "bootstrap-vue";
import CommentEditor from "@/components/comments/CommentEditor";
import CommentCard from "@/components/comments/CommentCard";
import { showSnackbar } from "@/utils";
export default {
    components: {
        BButton,
        CommentEditor,
        CommentCard
    },
    props: {
        apiListMethod: {
            type: Function,
            required: true
        },
        apiCreateMethod: {
            type: Function,
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
        };
    },
    mounted() {
        this.loadComments();
    },
    watch: {
        comments(val) {
            this.$emit("count", val.length);
        }
    },
    methods: {
        async loadComments() {
            this.error = null;
            try {
                let data = await this.apiListMethod();
                this.comments = data.data;
                this.loaded = true;
            } catch (err) {
                this.error = err;
            }
        },
        openEditor() {
            this.editor = true;
        },
        closeEditor() {
            this.editor = false;
        },
        async addComment(comment) {
            this.error = null;
            this.busy = true;
            try {
                let data = await this.apiCreateMethod(comment);
                this.comments.push(data.data);
                this.closeEditor();
                showSnackbar(data.message);
            } catch (err) {
                this.error = err;
            }
            this.busy = false;
        },
        async updateComment(comment) {
            this.error = null;
            this.busy = true;
            try {
                let data = await commentsApi.update(comment.id, comment);
                const idx = this.comments.findIndex(
                    elem => elem.id === comment.id
                );
                if (idx >= 0) {
                    this.$set(this.comments, idx, data.data);
                }
                this.editComment = null;
                showSnackbar(data.message);
            } catch (err) {
                this.error = err;
            }
            this.busy = false;
        },
        async deleteComment(comment) {
            if (confirm(this.$t("Really delete this comment?"))) {
                this.busy = true;
                try {
                    let data = await commentsApi.delete(comment.id);
                    const idx = this.comments.findIndex(
                        elem => elem.id === comment.id
                    );
                    if (idx >= 0) {
                        this.comments.splice(idx, 1);
                    }
                    showSnackbar(data.message);
                } catch (err) {
                    this.error = err;
                }
                this.busy = false;
            }
        }
    }
};
</script>
