<template>
    <comments-list
        :key="id"
        :api-list-method="listComments"
        :api-create-method="canCreate ? storeComment : null"
        @count="$emit('count', { type: 'comments', value: $event })"
    />
</template>

<script>
import CommentsList from '@/components/comments/CommentsList'
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'
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
            let data = await cmtyvolApi.listComments(this.id)
            this.canCreate = data.meta.can_create
            return data
        },
        storeComment (data) {
            return cmtyvolApi.storeComment(this.id, data)
        }
    }
}
</script>
