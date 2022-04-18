<template>
    <BaseWidget :title="$t('Visitors')" icon="door-open" :to="{name: 'visitors.index'}">
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
                { key: this.$t('Total visitors today'), value: this.data.todays_visitors }
            ]
        }
    }
};
</script>
