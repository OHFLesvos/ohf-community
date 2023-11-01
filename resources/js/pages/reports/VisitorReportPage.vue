<template>
    <div>
        <BreadcrumbsNav :items="[
            {
                text: $t('Visitor check-in'),
                to: {name: 'visitors.check_in'}
            },
            {
                text: $t('Reports'),
            }
        ]">
        </BreadcrumbsNav>
        <b-container>
            <VisitorReport/>
        </b-container>
    </div>
</template>

<script>
import BreadcrumbsNav from "@/components/layout/BreadcrumbsNav.vue";
import VisitorReport from "@/components/visitors/VisitorReport.vue";

export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Visitor check-ins");
    },
    components: {
        BreadcrumbsNav,
        VisitorReport,
    },
};
</script>
