<template>
    <BaseWidget :title="$t('Donation Management')" icon="donate" :to="{name: 'fundraising.index'}">
        <ValueTable :items="items"/>
    </BaseWidget>
</template>
<script>
import BaseWidget from "@/components/dashboard/BaseWidget.vue"
import ValueTable from "@/components/dashboard/ValueTable.vue"
export default {
    components: {
        BaseWidget,
        ValueTable
    },
    props: {
        data: { required: true, type: Object },
    },
    data() {
        return {
            items: [
                { key: this.$t('Registered donors'), value: this.data.num_donors },
                { key: this.$t('Donations this month'), value: this.data.num_donations_month },
                { key: this.$t('Donations this year'), value: this.data.num_donations_year },
                { key: this.$t('Donations in total'), value: this.data.num_donations_total },
            ]
        }
    }
};
</script>
