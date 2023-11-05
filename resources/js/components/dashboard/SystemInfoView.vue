<template>
    <div>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <b-card v-else-if="data" no-body>
            <ValueTable :items="data"/>
        </b-card>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </div>
</template>

<script>
import dashboardApi from "@/api/dashboard";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import ValueTable from "@/components/dashboard/ValueTable.vue"
export default {
    components: {
        AlertWithRetry,
        ValueTable,
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
