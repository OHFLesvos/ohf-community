<template>
    <b-container fluid>
        <BaseTable
            id="usersTable"
            :fields="fields"
            :api-method="list"
            default-sort-by="name"
            :empty-text="$t('No users found.')"
            :items-per-page="10"
        >
            <template #filter-append>
                <b-button :to="{ name: 'roles.index' }">
                    <font-awesome-icon icon="tags"/>
                    {{ $t('Roles') }}
                </b-button>
            </template>

            <template v-slot:cell(avatar_url)="data">
                <UserAvatar
                    :value="data.item.name"
                    :src="data.item.avatar_url"
                    :badge-icon="data.item.is_super_admin ? 'shield-alt' : null"
                />
            </template>
            <template v-slot:cell(name)="data">
                <router-link :to="{ name: 'users.show', params: { id: data.item.id } }">
                    {{ data.value }}
                </router-link>
            </template>
            <template v-slot:cell(email)="data">
                <email-link
                    v-if="data.item.email"
                    :value="data.item.email"
                    label-only
                />
            </template>
            <template v-slot:cell(roles)="data">
                <span
                    v-for="role in data.item.relationships.roles.data"
                    :key="role.id"
                >
                    <router-link :to="{ name: 'roles.show', params: { id: role.id } }">
                        {{ role.name }}
                    </router-link>
                    <br />
                </span>
            </template>
            <template v-slot:cell(is_2fa_enabled)="data">
                <span :class="data.value ? 'text-success' : 'text-muted'">
                    <font-awesome-icon :icon="data.value ? 'check' : 'times'" />
                </span>
            </template>
            <template v-slot:cell(is_super_admin)="data">
                <span :class="data.value ? 'text-info' : 'text-muted'">
                    <font-awesome-icon :icon="data.value ? 'check' : 'times'" />
                </span>
            </template>
        </BaseTable>
        <ButtonGroup :items="[
            {
                to: { name: 'users.create' },
                variant: 'primary',
                icon: 'plus-circle',
                text: $t('Add'),
                show: can('create-user')
            },
            {
                to: { name: 'reports.users.permissions' },
                text: $t('View Permissions'),
                icon: 'key',
                show: can('view-user-management'),
            }
        ]"/>
    </b-container>
</template>

<script>
import BaseTable from "@/components/table/BaseTable.vue";
import EmailLink from "@/components/common/EmailLink.vue";
import UserAvatar from "@/components/user_management/UserAvatar.vue";
import ButtonGroup from "@/components/common/ButtonGroup.vue";
import usersApi from "@/api/user_management/users";
export default {
    title() {
        return this.$t("Users");
    },
    components: {
        BaseTable,
        EmailLink,
        UserAvatar,
        ButtonGroup
    },
    data() {
        return {
            fields: [
                {
                    key: "avatar_url",
                    label: this.$t("Avatar"),
                    class: "fit align-middle text-center"
                },
                {
                    key: "name",
                    label: this.$t("Name"),
                    sortable: true,
                    class: "align-middle"
                },
                {
                    key: "email",
                    label: this.$t("Email address"),
                    class: "align-middle d-none d-sm-table-cell"
                },
                {
                    key: "roles",
                    label: this.$t("Roles"),
                    class: "align-middle d-none d-sm-table-cell"
                },
                {
                    key: "provider_name",
                    label: this.$t("OAuth"),
                    class: "align-middle fit d-none d-md-table-cell",
                    sortable: true
                },
                {
                    key: "is_2fa_enabled",
                    label: this.$t("2FA"),
                    class: "align-middle d-none d-md-table-cell text-center fit"
                },
                {
                    key: "is_super_admin",
                    label: this.$t("Admin"),
                    class:
                        "align-middle d-none d-md-table-cell text-center fit",
                    sortable: true,
                    sortDirection: "desc"
                },
                {
                    key: "created_at",
                    label: this.$t("Registered"),
                    class: "d-none d-sm-table-cell fit",
                    tdClass: "align-middle",
                    sortable: true,
                    sortDirection: "desc",
                    formatter: this.timeFromNow
                }
            ]
        };
    },
    methods: {
        list: usersApi.listWithRoles
    }
};
</script>
