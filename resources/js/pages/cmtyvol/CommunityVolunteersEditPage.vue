<template>
    <b-container>
        <AlertWithRetry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <div v-else-if="cmtyvol">
            <CmtyvolForm
                :cmtyvol="cmtyvol"
                :title="$t('Edit community volunteer')"
                :disabled="isBusy"
                @submit="handleUpdate"
                @cancel="handleCancel"
                @delete="handleDelete"
            />
            <p class="text-right">
                <small>
                    {{ $t("Created") }}:
                    {{ cmtyvol.created_at | dateTimeFormat }} </small
                ><br />
                <small>
                    {{ $t("Last updated") }}:
                    {{ cmtyvol.updated_at | dateTimeFormat }}
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
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'

import AlertWithRetry from "@/components/alerts/AlertWithRetry.vue";
import CmtyvolForm from "@/components/cmtyvol/CmtyvolForm.vue";

export default {
    title() {
        return this.$t("Edit community volunteer");
    },
    components: {
        AlertWithRetry,
        CmtyvolForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            error: null,
            cmtyvol: null,
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
            this.cmtyvol = null
            try {
                let data = await cmtyvolApi.find(this.id);
                this.cmtyvol = data.data;
            } catch (err) {
                this.error = err
            }
        },
        async handleUpdate(formData) {
            this.isBusy = true;
            try {
                await cmtyvolApi.update(this.id, formData);
                showSnackbar(this.$t("Community volunteer updated."));
                this.$router.push({ name: "cmtyvol.show", params: { id: this.id } });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async handleDelete() {
            this.isBusy = true;
            try {
                await cmtyvolApi.delete(this.id);
                showSnackbar(this.$t("Community volunteer deleted."));
                this.$router.push({ name: "cmtyvol.overview" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "cmtyvol.show", params: { id: this.id } });
        }
    }
};
</script>
