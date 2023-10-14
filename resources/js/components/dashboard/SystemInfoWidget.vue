<template>
    <BaseWidget :title="$t('System Information')" icon="microchip">
        <ValueTable :items="items"/>
    </BaseWidget>
</template>
<script>
import BaseWidget from "@/components/dashboard/BaseWidget.vue"
import ValueTable from "@/components/dashboard/ValueTable.vue"
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
            items: Object.entries(this.data).map(e => ({ key: e[0], value: e[1] })),
        }
    }
};
</script>
