<template>
    <b-container>
        <base-table
            ref="table"
            id="responsibilities-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="name"
            :empty-text="$t('No responsibilities defined.')"
            no-filter
            :tbody-tr-class="rowClass"
        >
            <template v-slot:cell(name)="data">
                <router-link
                    :to="{
                        name: 'cmtyvol.responsibilities.edit',
                        params: { id: data.item.slug }
                    }"
                >
                    {{ data.value }}
                </router-link>
            </template>
        </base-table>
        <ButtonGroup :items="[
            {
                to: { name: 'cmtyvol.responsibilities.create' },
                variant: 'primary',
                icon: 'plus-circle',
                text: $t('Add'),
                show: can('create-community-volunteer-responsibility')
            },
        ]"/>
    </b-container>
</template>

<script>
import responsibilitiesApi from "@/api/cmtyvol/responsibilities";

import BaseTable from "@/components/table/BaseTable.vue";
import ButtonGroup from "@/components/common/ButtonGroup.vue";

export default {
    title() {
        return this.$t("Responsibilities");
    },
    components: {
        BaseTable,
        ButtonGroup,
    },
    data() {
        return {
            fields: [
                {
                    key: "name",
                    label: this.$t("Name"),
                    tdClass: "align-middle"
                },
                {
                    key: "description",
                    label: this.$t("Description"),
                    tdClass: "align-middle pre-formatted"
                },
                {
                    key: "capacity",
                    label: this.$t("Capacity"),
                    tdClass: (v, _, item) => ["align-middle", item.is_capacity_exhausted ? 'table-danger' : null],
                    formatter: (v, _, item) => v == null ? '∞' : `${item.count_active} / ${v}`
                },
                {
                    key: "available",
                    label: this.$t("Available"),
                    tdClass: "align-middle",
                    formatter: v => v ? this.$t('Yes') : this.$t('No')
                },
            ]
        };
    },
    methods: {
        async fetchData(ctx) {
            return responsibilitiesApi.list(ctx);
        },
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (!item.available) return 'text-muted table-secondary'
        }
    }
};
</script>
