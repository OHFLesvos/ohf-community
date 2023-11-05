<template>
    <div>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <template v-else-if="content">
            <b-card :header="$t('Changelog')" class="mb-3">
                <div v-html="content"></div>
            </b-card>
        </template>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </div>
</template>

<script>
import dashboardApi from "@/api/dashboard";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
export default {
    title() {
        return this.$t("Changelog");
    },
    components: {
        AlertWithRetry,
    },
    data() {
        return {
            content: null,
            error: null
        };
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.content = null
            this.error = null
            try {
                let data = await dashboardApi.changelog();
                this.content = data.replace('<h1>Changelog</h1>', '').replaceAll('<h2>', '<h2 class="display-4">')
            } catch (err) {
                this.error = err
            }
        }
    }
};
</script>
