<template>
  <base-table v-bind="$props">
    <template v-slot:cell(first_name)="data">
        <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template v-slot:cell(last_name)="data">
        <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template v-slot:cell(company)="data">
        <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template v-slot:cell(email)="data">
        <email-link :value="data.value"></email-link>
    </template>
    <template v-slot:cell(phone)="data">
        <phone-link :value="data.value"></phone-link>
    </template>
  </base-table>
</template>

<script>
    import BaseTable from '@/components/BaseTable.vue';
    export default {
        extends: BaseTable,
    }
</script>