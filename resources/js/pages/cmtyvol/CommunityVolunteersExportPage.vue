<template>
    <b-container>
        Export
    </b-container>
</template>

<script>
import cmtyvolApi from "@/api/cmtyvol/cmtyvol";

export default {
    title() {
        return this.$t("Export community volunteer data");
    },
    components: {

    },
    data() {
        return {
            api: cmtyvolApi
        };
    }
};
</script>
