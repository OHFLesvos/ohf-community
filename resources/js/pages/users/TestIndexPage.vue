<template>
    <b-container fluid>
        <BaseTable
            id="rolesTable"
            :fields="fields"
            :api-method="list"
            default-sort-by="name"
            :empty-text="$t('No roles found.')"
            :items-per-page="10"
        >
            <template v-slot:cell(name)="data">
                <router-link :to="{ name: 'roles.show', params: { id: data.item.id } }">
                    {{ data.value }}
                </router-link>
            </template>
        </BaseTable>
        <ButtonGroup :items="[
            {
                to: { name: 'roles.create' },
                variant: 'primary',
                icon: 'plus-circle',
                text: $t('Add'),
                show: can('create-roles')
            },
        ]"/>
    </b-container>
</template>

<script>
import BaseTable from "@/components/table/BaseTable.vue";
import ButtonGroup from "@/components/common/ButtonGroup.vue";
import rolesApi from "@/api/user_management/roles";
export default {
    title() {
        return this.$t("Roles");
    },
    components: {
        BaseTable,
        ButtonGroup
    },
    data() {
        return {
            fields: [
                {
                    key: "name",
                    label: this.$t("Name"),
                    sortable: true,
                    class: "align-middle"
                },
                {
                    key: "num_users",
                    label: this.$t("Users"),
                    sortable: false,
                    class: "align-middle text-right fit",
                },
                {
                    key: "num_administrators",
                    label: this.$t("Role Administrators"),
                    sortable: false,
                    class: "align-middle text-right fit",
                },
                {
                    key: "num_permissions",
                    label: this.$t("Permissions"),
                    sortable: false,
                    class: "align-middle text-right fit",
                },
            ]
        };
    },
    methods: {
        list: rolesApi.list
    }
};
</script>
