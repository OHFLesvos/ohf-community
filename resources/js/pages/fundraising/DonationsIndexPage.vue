<template>
    <donations-table>
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

    </donations-table>
</template>

<script>
import DonationsTable from '@/components/fundraising/DonationsTable'
export default {
    components: {
        DonationsTable
    },
    data () {
        return {
            baseCurrency: null,
            currencies: {},
            channels: [],
            isBusy: false
        }
    }
}
</script>
