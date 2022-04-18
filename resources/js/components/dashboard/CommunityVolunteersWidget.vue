<template>
    <BaseWidget :title="$t('Community Volunteers')" icon="id-badge" :href="route('cmtyvol.index')">
        <ValueTable :items="items"/>
    </BaseWidget>
</template>
<script>
import BaseWidget from "@/components/dashboard/BaseWidget"
import ValueTable from "@/components/dashboard/ValueTable"
export default {
    components: {
        BaseWidget,
        ValueTable
    },
    props: {
        data: { required: true, type: Object },
    },
    data() {
        return {
            items: [
                { key: this.$t('Active community volunteers'), value: this.data.active }
            ]
        }
    }
};
</script>
