<template>
  <base-table v-bind="$props">
    <template v-slot:cell(date)="data">
        <a :href="data.item.edit_url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template v-slot:cell(donor)="data">
        <a :href="data.item.donor_url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template v-slot:head(thanked)>
        <font-awesome-icon icon="handshake" />
    </template>
    <template v-slot:cell(thanked)="data">
        <font-awesome-icon v-if="data.value" icon="check" />
    </template>
  </base-table>
</template>

<script>
import BaseTable from '@/components/BaseTable';
export default {
    extends: BaseTable,
    components: {
        BaseTable
    }
}
</script>
