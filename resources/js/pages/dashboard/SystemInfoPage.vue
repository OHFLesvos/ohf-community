<template>
    <b-container>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <template v-else-if="data">
            <BaseWidget :title="$t('System Information')" icon="microchip">
                <ValueTable :items="data"/>
            </BaseWidget>
        </template>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </b-container>
</template>

<script>
import dashboardApi from "@/api/dashboard";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import BaseWidget from "@/components/dashboard/BaseWidget.vue"
import ValueTable from "@/components/dashboard/ValueTable.vue"
export default {
    title() {
        return this.$t("System Information");
    },
    components: {
        AlertWithRetry,
        BaseWidget,
        ValueTable
    },
    data() {
        return {
            data: null,
            error: null
        };
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.data = null
            this.error = null
            try {
                let data = await dashboardApi.systemInfo();
                this.data = Object.entries(data).map(e => ({ key: e[0], value: e[1] }))
            } catch (err) {
                this.error = err
            }
        }
    }
};
</script>
