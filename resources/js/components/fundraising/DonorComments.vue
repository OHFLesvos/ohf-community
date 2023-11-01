<template>
    <b-container class="mt-3 px-0">
        <comments-list
            :key="id"
            :api-list-method="listComments"
            :api-create-method="canCreate ? storeComment : null"
            @count="$emit('count', { type: 'comments', value: $event })"
        />
    </b-container>
</template>

<script>
import CommentsList from '@/components/comments/CommentsList.vue'
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
