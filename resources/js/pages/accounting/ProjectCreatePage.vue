<template>
    <b-container class="px-0">
        <project-form
            :disabled="isBusy"
            @submit="handleRegister"
            @cancel="handleCnacel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import projectsApi from "@/api/accounting/projects";
import ProjectForm from "@/components/accounting/ProjectForm";
export default {
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
        handleCnacel() {
            this.$router.push({ name: "accounting.projects.index" });
        }
    }
};
</script>
