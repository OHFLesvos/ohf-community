<template>
    <b-container>
        <ProjectForm
            :title="$t('Create project')"
            :disabled="isBusy"
            @submit="handleRegister"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import projectsApi from "@/api/accounting/projects";
import ProjectForm from "@/components/accounting/ProjectForm.vue";
export default {
    title() {
        return this.$t("Create project");
    },
    components: {
        ProjectForm
    },
    data() {
        return {
            isBusy: false
        };
    },
    methods: {
        async handleRegister(formData) {
            this.isBusy = true;
            try {
                await projectsApi.store(formData);
                showSnackbar(this.$t("Project added."));
                this.$router.push({ name: "accounting.projects.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "accounting.projects.index" });
        }
    }
};
</script>
