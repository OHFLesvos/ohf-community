<template>
    <b-card
        class="mb-3"
        footer-class="d-flex justify-content-between align-items-center"
    >
        <b-card-text>
            <nl2br tag="span" :text="comment.content" />
        </b-card-text>
        <template v-slot:footer>
            <small>
                {{ comment.user_name }},
                <span @click="dateFromNow = !dateFromNow">
                    {{ formattedDate }}
                </span>
            </small>
            <span>
                <b-button
                    v-if="comment.update_url"
                    variant="link"
                    size="sm"
                    :disabled="busy"
                    @click="$emit('edit')"
                >
                    <font-awesome-icon icon="pencil-alt" />
                </b-button>
                <b-button
                    v-if="comment.delete_url"
                    variant="link"
                    size="sm"
                    :disabled="busy"
                    @click="$emit('delete')"
                >
                    <font-awesome-icon icon="trash" />
                </b-button>
            </span>
        </template>
    </b-card>
</template>

<script>
import moment from 'moment'
import Nl2br from 'vue-nl2br'
import { BCard, BCardText, BButton } from 'bootstrap-vue'
export default {
    components: {
        BCard,
        BCardText,
        BButton,
        Nl2br,
    },
    props: {
        comment: {
            required: true,
            type: Object
        },
        busy: Boolean
    },
    data() {
        return {
            dateFromNow: true
        }
    },
    computed: {
        formattedDate() {
            const date = moment(this.comment.created_at)
            return this.dateFromNow
                ? date.fromNow()
                : date.format("LLL")
        }
    }
}
</script>
