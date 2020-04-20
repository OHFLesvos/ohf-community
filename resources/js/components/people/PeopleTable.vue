<template>
  <base-table v-bind="$props">
    <template v-slot:cell(name)="data">
        <template v-if="data.item.url != null">
            <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
        </template>
        <template v-else>
            {{ data.value }}
        </template>
    </template>
    <template v-slot:cell(family_name)="data">
        <template v-if="data.item.url != null">
            <a :href="data.item.url" v-if="data.value != ''">{{ data.value }}</a>
        </template>
        <template v-else>
            {{ data.value }}
        </template>
    </template>
  </base-table>
</template>

<script>
import BaseTable from '@/components/BaseTable'
export default {
    extends: BaseTable,
    components: {
        BaseTable
    }
}
</script>
