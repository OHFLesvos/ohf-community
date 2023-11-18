<template>
    <b-container>
        <AlertWithRetry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <div v-else-if="responsibility">
            <ResponsibilityForm
                :responsibility="responsibility"
                :title="$t('Edit responsibility')"
                :disabled="isBusy"
                @submit="handleUpdate"
                @cancel="handleCancel"
                @delete="handleDelete"
            />
            <p class="text-right">
                <small>
                    {{ $t("Created") }}:
                    {{ responsibility.created_at | dateTimeFormat }} </small
                ><br />
                <small>
                    {{ $t("Last updated") }}:
                    {{ responsibility.updated_at | dateTimeFormat }}
                </small>
            </p>
        </div>
        <div v-else>
            {{ $t("Loading...") }}
        </div>
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import responsibilitiesApi from "@/api/cmtyvol/responsibilities";

import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";
import ResponsibilityForm from "@/components/cmtyvol/ResponsibilityForm.vue";

export default {
    title() {
        return this.$t("Edit responsibility");
    },
    components: {
        AlertWithRetry,
        ResponsibilityForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            error: null,
            responsibility: null,
            isBusy: false
        };
    },
    watch: {
        $route() {
            this.fetchData();
        }
    },
    async created() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            this.error = null
            this.responsibility = null
            try {
                let data = await responsibilitiesApi.find(this.id);
                this.responsibility = data.data;
            } catch (err) {
                this.error = err
            }
        },
        async handleUpdate(formData) {
            this.isBusy = true;
            try {
                await responsibilitiesApi.update(this.id, formData);
                showSnackbar(this.$t("Responsibility updated."));
                this.$router.push({ name: "cmtyvol.responsibilities.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async handleDelete() {
            this.isBusy = true;
            try {
                await responsibilitiesApi.delete(this.id);
                showSnackbar(this.$t("Responsibility deleted."));
                this.$router.push({ name: "cmtyvol.responsibilities.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "cmtyvol.responsibilities.index" });
        }
    }
};
</script>
