<template>
    <b-card
        class="mb-3"
        footer-class="d-flex justify-content-between align-items-center py-1"
    >
        <b-card-text class="pre-formatted">{{ comment.content }}</b-card-text>
        <template v-slot:footer>
            <small>
                <a
                    v-if="comment.user_url"
                    :href="comment.user_url"
                    target="_blank"
                >{{ userName }}</a><span v-else>{{ userName }}</span>,
                <span @click="dateFromNow = !dateFromNow">
                    {{ formattedDate }}
                </span>
            </small>
            <span>
                <b-button
                    v-if="comment.can_update"
                    variant="link"
                    size="sm"
                    :disabled="busy"
                    @click="$emit('edit')"
                >
                    <font-awesome-icon icon="pencil-alt" />
                </b-button>
                <b-button
                    v-if="comment.can_delete"
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
export default {
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
        userName() {
            return this.comment.user_name ? this.comment.user_name : this.$t('Unknown')
        },
        formattedDate() {
            let str = this.toDateString(this.comment.created_at)
            if (this.comment.created_at != this.comment.updated_at) {
                const updated = this.$t('Updated :time.', {
                    time: this.toDateString(this.comment.updated_at)
                })
                str += ` (${updated})`
            }
            return str
        },
    },
    methods: {
        toDateString(date) {
            return this.dateFromNow
                ? this.timeFromNow(date)
                : this.dateTimeFormat(date)
        }
    }
}
</script>
