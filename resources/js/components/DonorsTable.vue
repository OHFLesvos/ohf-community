<template>
  <base-table v-bind="$props">
    <!-- <template slot="table-caption">There are {{ items.length }} donors in the database.</template> -->
    <template slot="[first_name]" slot-scope="data">
        <a :href="data.item.url">{{ data.value }}</a>
    </template>
    <template slot="[last_name]" slot-scope="data">
        <a :href="data.item.url">{{ data.value }}</a>
    </template>
    <template slot="[company]" slot-scope="data">
        <a :href="data.item.url">{{ data.value }}</a>
    </template>
    <template slot="[email]" slot-scope="data">
        <a :href="mailtoHref(data.value)" v-if="data.value != ''">{{ data.value }}</a>
    </template>
    <template slot="[phone]" slot-scope="data">
        <a :href="telHref(data.value)" v-if="data.value != ''">{{ data.value }}</a>
    </template>
  </base-table>
</template>

<script>
    import BaseTable from './BaseTable.vue';
    export default {
        extends: BaseTable,
    }
</script>