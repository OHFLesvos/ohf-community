<template>
    <div>
        <donors-table
            v-if="tags != null"
            :tags="tags"
            :tag="tag"
        />
        <template v-else>
            {{ $t('app.loading') }}
        </template>
    </div>
</template>

<script>
import DonorsTable from '@/components/fundraising/DonorsTable'
import donorsApi from '@/api/fundraising/donors'
export default {
    components: {
        DonorsTable
    },
    props: {
        tag: {
            require: false,
            type: String,
            default: null
        }
    },
    data () {
        return {
            tags: null
        }
    },
    async created () {
        let data = await donorsApi.listTags()
        this.tags = data.data.reduce((map, obj) => {
            map[obj.slug] = obj.name
            return map
        }, {})
    }
}
</script>
