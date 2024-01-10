<template>
    <b-container fluid class="mt-3">
        <DonorsTable
            v-if="tags != null"
            :tags="tags"
            :tag="tag"
        >
            <template v-slot:primary-cell="data">
                <router-link :to="{ name: 'fundraising.donors.show', params: { id: data.item.id } }">
                    {{ data.value }}
                </router-link>
            </template>
        </DonorsTable>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
        <div class="d-flex">
            <ButtonGroup :items="navButtons"/>
            <DonorsExportDialog v-if="can('view-fundraising-entities')"/>
        </div>
    </b-container>
</template>

<script>
import DonorsTable from '@/components/fundraising/DonorsTable.vue'
import donorsApi from '@/api/fundraising/donors'
import ButtonGroup from "@/components/common/ButtonGroup.vue";
import DonorsExportDialog from "@/components/fundraising/DonorsExportDialog.vue";
export default {
    title() {
        return this.$t("Donors");
    },
    components: {
        DonorsTable,
        ButtonGroup,
        DonorsExportDialog,
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
            tags: null,
            navButtons: [
                {
                    to: { name: "fundraising.donors.create" },
                    variant: "primary",
                    icon: "plus-circle",
                    text: this.$t("Add"),
                    show: this.can("manage-fundraising-entities")
                },
            ]
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
