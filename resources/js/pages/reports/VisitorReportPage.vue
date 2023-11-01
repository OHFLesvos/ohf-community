<template>
    <b-container>
        <VisitorReport/>
    </b-container>
</template>

<script>
import VisitorReport from "@/components/visitors/VisitorReport.vue";

export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Visitor check-ins");
    },
    components: {
        VisitorReport,
    },
};
</script>
