<template>
    <div class="mt-3">
        <comments-list
            :key="id"
            :api-list-method="listComments"
            :api-create-method="canCreate ? storeComment : null"
            @count="$emit('count', { type: 'comments', value: $event })"
        />
    </div>
</template>

<script>
import CommentsList from '@/components/comments/CommentsList'
import donorsApi from '@/api/fundraising/donors'
export default {
    components: {
        CommentsList
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            canCreate: false
        }
    },
    methods: {
        async listComments () {
            let data = await donorsApi.listComments(this.id)
            this.canCreate = data.meta.can_create
            return data
        },
        storeComment (data) {
            return donorsApi.storeComment(this.id, data)
        }
    }
}
</script>
