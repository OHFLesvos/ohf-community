<template>
    <b-container v-if="visitor">
        <h2 class="display-4 mb-3">{{ $t("Edit visitor") }}</h2>
        <VisitorForm
            :value="visitor"
            :disabled="isBusy"
            @submit="handleUpdate"
            @cancel="handleCancel"
        />
    </b-container>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import visitorsApi from "@/api/visitors";
import VisitorForm from "@/components/visitors/VisitorForm";
import { showSnackbar } from "@/utils";
export default {
    components: {
        VisitorForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            isBusy: false,
            visitor: null
        };
    },
    async created() {
        this.fetch();
    },
    methods: {
        async fetch() {
            try {
                let data = await visitorsApi.find(this.id);
                this.visitor = data.data;
            } catch (err) {
                alert(err);
            }
        },
        async handleUpdate(formData) {
            this.isBusy = true;
            try {
                await visitorsApi.update(this.id, formData);
                showSnackbar(this.$t("Visitor updated."));
                this.$router.push({ name: "visitors.check_in" });
            } catch (err) {
                alert(err);
            }
            this.isBusy = false;
        },
        handleCancel() {
            this.$router.push({ name: "visitors.check_in" });
        }
    }
};
</script>
