<template>
    <div class="mt-3">
        <donors-table
            v-if="tags != null"
            :tags="tags"
            :tag="tag"
        >
            <template v-slot:primary-cell="data">
                <router-link :to="{ name: 'fundraising.donors.show', params: { id: data.item.id } }">
                    {{ data.value }}
                </router-link>
            </template>
        </donors-table>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
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
        this.fetchTags()
    },
    methods: {
        async fetchTags () {
            let data = await donorsApi.listTags()
            this.tags = data.data.reduce((map, obj) => {
                map[obj.slug] = obj.name
                return map
            }, {})
        }
    }
}
</script>
