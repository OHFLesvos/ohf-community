<template>
  <base-table v-bind="$props">
    <template slot="[first_name]" slot-scope="data">
        <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template slot="[last_name]" slot-scope="data">
        <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template slot="[company]" slot-scope="data">
        <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template slot="[email]" slot-scope="data">
        <email-link :value="data.value"></email-link>
    </template>
    <template slot="[phone]" slot-scope="data">
        <phone-link :value="data.value"></phone-link>
    </template>
  </base-table>
</template>

<script>
    import BaseTable from '../../../../../../resources/js/components/BaseTable.vue';
    export default {
        extends: BaseTable,
    }
</script>