<template>
    <b-container v-if="project">
        <ProjectForm
            :project="project"
            :title="$t('Edit project')"
            :disabled="isBusy"
            @submit="handleUpdate"
            @cancel="handleCancel"
            @delete="handleDelete"
        />
        <p class="d-flex justify-content-between">
            <small>
                {{
                    $t("Used in {num} transactions.", {
                        num: project.num_transactions
                    })
                }}
            </small>
            <small>
                {{ $t("Last updated") }}:
                {{ project.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import { showSnackbar } from "@/utils";
import projectsApi from "@/api/accounting/projects";
import ProjectForm from "@/components/accounting/ProjectForm.vue";
export default {
    title() {
        return this.$t("Edit project");
    },
    components: {
        ProjectForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            project: null,
            isBusy: false
        };
    },
    watch: {
        $route() {
            this.fetch();
        }
    },
    async created() {
        this.fetch();
    },
    methods: {
        async fetch() {
            try {
                let data = await projectsApi.find(this.id);
                this.project = data.data;
            } catch (err) {
                alert(err);
            }
        },
        async handleUpdate(formData) {
            this.isBusy = true;
            try {
                await projectsApi.update(this.id, formData);
                showSnackbar(this.$t("Project updated."));
                this.$router.push({ name: "accounting.projects.index" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        async handleDelete() {
            this.isBusy = true;
            try {
                await projectsApi.delete(this.id);
                showSnackbar(this.$t("Project deleted."));
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
