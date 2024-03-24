<template>
    <b-container>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <b-row>
            <b-col sm="6">
                <BaseWidget :title="$t('Donors')" icon="users" :to="{name: 'fundraising.donors.index'}">
                    <template v-if="data">
                        <ValueTable :items="donorsData" :alignAllItemsRight="true"/>
                        <b-list-group flush v-if="data.last_registered_donation">
                            <b-list-group-item :to="{ name: 'fundraising.donors.show', params: { id: data.last_registered_donor.id } }">
                                {{ $t('Last registered donor') }}:
                                <span class="float-right">{{ data.last_registered_donor.full_name }}</span><br>
                                <small class="float-right text-right">{{ dateFormat(this.data.last_registered_donor.created_at) }}</small>
                            </b-list-group-item>
                        </b-list-group>
                    </template>
                    <b-list-group v-else flush>
                        <b-list-group-item><b-skeleton width="85%"></b-skeleton></b-list-group-item>
                        <b-list-group-item><b-skeleton width="65%"></b-skeleton></b-list-group-item>
                        <b-list-group-item><b-skeleton width="50%"></b-skeleton></b-list-group-item>
                        <b-list-group-item><b-skeleton width="85%"></b-skeleton></b-list-group-item>
                    </b-list-group>
                </BaseWidget>
            </b-col>
            <b-col sm="6">
                <BaseWidget :title="$t('Donations')" icon="donate" :to="{name: 'fundraising.donations.index'}">
                    <template v-if="data">
                        <ValueTable :items="donationsData" :alignAllItemsRight="true"/>
                        <b-list-group flush v-if="data.last_registered_donation">
                            <b-list-group-item :to="{ name: 'fundraising.donors.show.donations', params: { id: data.last_registered_donation.donor_id } }">
                                {{ $t('Last registered donation') }}:
                                <span class="float-right">{{ data.last_registered_donation.amount }} {{ data.last_registered_donation.currency }}</span><br>
                                <small class="float-right text-right">{{ data.last_registered_donation.donor }}, {{ dateFormat(this.data.last_registered_donation.created_at) }}</small>
                            </b-list-group-item>
                        </b-list-group>
                    </template>
                    <b-list-group v-else flush>
                        <b-list-group-item><b-skeleton width="85%"></b-skeleton></b-list-group-item>
                        <b-list-group-item><b-skeleton width="65%"></b-skeleton></b-list-group-item>
                        <b-list-group-item><b-skeleton width="50%"></b-skeleton></b-list-group-item>
                        <b-list-group-item><b-skeleton width="85%"></b-skeleton></b-list-group-item>
                    </b-list-group>
                </BaseWidget>
            </b-col>
        </b-row>
        <b-row>
            <b-col
                v-for="(button, idx) in buttons.filter(btn => btn.show)" :key="idx"
                sm="6" md="6" lg="3" class="mb-4"
            >
                <b-button :key="button.text" :to="button.to" class="d-block">
                    <font-awesome-icon :icon="button.icon"/>
                    {{ button.text }}
                </b-button>
            </b-col>
        </b-row>
    </b-container>
</template>
<script>
import reportApi from '@/api/fundraising/report'
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import BaseWidget from "@/components/dashboard/BaseWidget.vue"
import ValueTable from "@/components/dashboard/ValueTable.vue"
export default {
    title() {
        return this.$t("Donation Management");
    },
    components: {
        AlertWithRetry,
        BaseWidget,
        ValueTable,
    },
    data() {
        return {
            buttons: [
                {
                    to: { name: "fundraising.donors.index" },
                    icon: "users",
                    text: this.$t("Manage donors"),
                    show: this.can("view-fundraising-entities")
                },
                {
                    to: { name: "fundraising.donations.index" },
                    icon: "donate",
                    text: this.$t("Manage donations"),
                    show: this.can("view-fundraising-entities")
                },
                {
                    to: { name: "fundraising.donations.import" },
                    icon: "upload",
                    text: this.$t("Import"),
                    show: this.can("manage-fundraising-entities")
                },
                {
                    to: { name: "fundraising.export" },
                    icon: "download",
                    text: this.$t("Export"),
                    show: this.can("view-fundraising-entities")
                }
            ],
            error: null,
            data: null
        }
    },
    computed: {
        donorsData() {
            return [
                { key: this.$t('New donors this month'), value: this.data.num_new_donors_month },
                { key: this.$t('New donors this year'), value: this.data.num_new_donors_year },
                { key: this.$t('Registered donors'), value: this.data.num_donors },
            ];
        },
        donationsData() {
            return [
                { key: this.$t('Donations this month'), value: this.data.num_donations_month },
                { key: this.$t('Donations this year'), value: this.data.num_donations_year },
                { key: this.$t('Donations in total'), value: this.data.num_donations_total },
            ];
        },
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.error = null
            try {
                let data = await reportApi.summary()
                this.data =data;
            } catch (err) {
                this.error = err
            }
        },
    }
}
</script>
