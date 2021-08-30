<template>
    <b-container>
        <b-list-group class="shadow-sm">
            <b-list-group-item
                v-for="(report, idx) in reports.filter(report => report.show)"
                :key="idx"
                :to="report.to"
            >
                <font-awesome-icon :icon="report.icon" />
                {{ report.label }}
            </b-list-group-item>
        </b-list-group>
    </b-container>
</template>

<script>
import { can } from "@/plugins/laravel";
export default {
    title() {
        return this.$t("Reports");
    },
    data() {
        return {
            reports: [
                {
                    label: this.$t("Community Volunteers"),
                    to: { name: "cmtyvol.report" },
                    icon: "chart-bar",
                    show: can("view-community-volunteer-reports")
                },
                {
                    label: this.$t("Fundraising"),
                    to: { name: "reports.fundraising.donations" },
                    icon: "donate",
                    show: can("view-fundraising-reports")
                },
                {
                    label: this.$t("Visitor check-ins"),
                    to: { name: "reports.visitors.checkins" },
                    icon: "door-open",
                    show: can("register-visitors")
                }
            ]
        };
    }
};
</script>
