<template>
    <b-container v-if="project" class="px-0">
        <project-form
            :project="project"
            :disabled="isBusy"
            @submit="handleUpdate"
            @cancel="handleCnacel"
            @delete="handleDelete"
        />
        <hr />
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
                {{ dateFormat(project.updated_at) }}
            </small>
        </p>
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import moment from "moment";
import { showSnackbar } from "@/utils";
import projectsApi from "@/api/accounting/projects";
import ProjectForm from "@/components/accounting/ProjectForm";
export default {
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
        handleCnacel() {
            this.$router.push({ name: "accounting.projects.index" });
        },
        dateFormat(value) {
            return moment(value).format("LLL");
        }
    }
};
</script>
