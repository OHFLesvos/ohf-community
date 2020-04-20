<template>
  <base-table v-bind="$props">
    <template v-slot:cell(date)="data">
        <a :href="data.item.edit_url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template v-slot:cell(donor)="data">
        <a :href="data.item.donor_url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
  </base-table>
</template>

<script>
    import BaseTable from '@/components/BaseTable.vue';
    export default {
        extends: BaseTable,
    }
</script>
