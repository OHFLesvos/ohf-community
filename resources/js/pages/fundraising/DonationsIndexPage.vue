<template>
    <b-container fluid class="mt-3">
        <DonationsTable>
            <template v-slot:primary-cell="data">
                <router-link
                    v-if="data.value != '' && data.item.can_update"
                    :to="{ name: 'fundraising.donations.edit', params: { id: data.item.id } }"
                >
                    {{ data.value }}
                </router-link>
                <template v-else>
                    {{ data.value }}
                </template>
            </template>
            <template v-slot:donor-cell="data">
                <router-link
                    v-if="data.value !='' && data.item.donor_id"
                    :to="{ name: 'fundraising.donors.show', params: { id: data.item.donor_id } }"
                >
                    {{ data.value }}
                </router-link>
                <template v-else>
                    {{ data.value }}
                </template>
            </template>
        </DonationsTable>
        <div class="d-flex">
            <ButtonGroup :items="navButtons"/>
            <DonationsExportDialog v-if="can('view-fundraising-entities')"/>
        </div>
    </b-container>
</template>

<script>
import DonationsTable from '@/components/fundraising/DonationsTable.vue'
import ButtonGroup from "@/components/common/ButtonGroup.vue";
import DonationsExportDialog from "@/components/fundraising/DonationsExportDialog.vue";
export default {
    title() {
        return this.$t("Donations");
    },
    components: {
        DonationsTable,
        ButtonGroup,
        DonationsExportDialog,
    },
    data () {
        return {
            baseCurrency: null,
            currencies: {},
            channels: [],
            isBusy: false,
            navButtons: [
                {
                    to: { name: "fundraising.donations.import" },
                    icon: "upload",
                    text: this.$t("Import"),
                    show: this.can("manage-fundraising-entities")
                }
            ],
        }
    }
}
</script>
