<template>
    <form @submit.prevent="submit()">
        <p>
            <b-form-textarea
                v-model.trim="content"
                rows="3"
                max-rows="6"
                :placeholder="$t('Add comment')"
                :disabled="disabled"
                autofocus
            ></b-form-textarea>
        </p>
        <p>
            <b-button
                type="submit"
                variant="primary"
                :disabled="disabled || content.length == 0"
            >
                <font-awesome-icon icon="check" />
                {{ $t('Save') }}
            </b-button>
            <b-button
                type="button"
                variant="secondary"
                :disabled="disabled"
                @click="cancel()"
            >
                <font-awesome-icon icon="times" />
                {{ $t('Cancel') }}
            </b-button>
        </p>
    </form>
</template>

<script>
import { BButton } from 'bootstrap-vue'
export default {
    components: {
        BButton
    },
    props: {
        comment: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data() {
        return {
            content: this.comment ? this.comment.content : ''
        }
    },
    methods: {
        submit() {
            this.$emit('submit', {
                'content': this.content
            })
        },
        cancel() {
            this.$emit('cancel')
        }
    }
}
</script>
