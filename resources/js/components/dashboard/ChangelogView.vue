<template>
    <div>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <b-card v-else-if="content" class="mb-3">
            <div v-html="content"></div>
        </b-card>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </div>
</template>

<script>
import dashboardApi from "@/api/dashboard";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
export default {
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
