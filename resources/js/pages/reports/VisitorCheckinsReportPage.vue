<template>
    <b-container>
        <VisitorCheckinsReportTable/>
    </b-container>
</template>

<script>
import VisitorCheckinsReportTable from "@/components/visitors/VisitorCheckinsReportTable.vue";
export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Visitor check-ins");
    },
    components: {
        VisitorCheckinsReportTable,
    },
};
</script>
