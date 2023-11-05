<template>
    <b-container>
        <TabNav :items="tabNavItems"/>
        <router-view />
    </b-container>
</template>

<script>
import TabNav from "@/components/layout/TabNav.vue";
export default {
    title() {
        return this.$t("System Information");
    },
    components: {
        TabNav,
    },
    data() {
        return {
            tabNavItems: [
                {
                    to: { name: "system-info" },
                    icon: "microchip",
                    text: this.$t("System Information")
                },
                {
                    to: { name: "changelog" },
                    icon: "list",
                    text: this.$t("Changelog"),
                }
            ],
        };
    },
};
</script>
