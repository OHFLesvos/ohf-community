<template>
    <base-table
        id="usersTable"
        :fields="fields"
        :api-method="list"
        default-sort-by="name"
        :empty-text="$t('No users found.')"
        :items-per-page="25"
    >
        <template v-slot:cell(avatar_url)="data">
            <user-avatar
                :url="data.value"
                size="30"
            />
        </template>
        <template v-slot:cell(name)="data">
            <a :href="data.item.links.show">
                {{ data.value }}
            </a>
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
                <a :href="role.links.show">
                    {{ role.name }}
                </a>
                <br>
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
    </base-table>
</template>

<script>
import BaseTable from '@/components/table/BaseTable'
import EmailLink from '@/components/common/EmailLink'
import UserAvatar from '@/components/UserAvatar'
import usersApi from '@/api/user_management/users'
export default {
    components: {
        BaseTable,
        EmailLink,
        UserAvatar
    },
    data() {
        return {
            fields: [
                {
                    key: 'avatar_url',
                    label: this.$t('Avatar'),
                    class: 'fit align-middle text-center'
                },
                {
                    key: 'name',
                    label: this.$t('Name'),
                    sortable: true,
                    class: 'align-middle'
                },
                {
                    key: 'email',
                    label: this.$t('E-Mail Address'),
                    class: 'align-middle d-none d-sm-table-cell'
                },
                {
                    key: 'roles',
                    label: this.$t('Roles'),
                    class: 'align-middle d-none d-sm-table-cell'
                },
                {
                    key: 'provider_name',
                    label: this.$t('OAuth'),
                    class: 'align-middle fit d-none d-md-table-cell',
                    sortable: true,
                },
                {
                    key: 'is_2fa_enabled',
                    label: this.$t('2FA'),
                    class: 'align-middle d-none d-md-table-cell text-center fit',
                },
                {
                    key: 'is_super_admin',
                    label: this.$t('Admin'),
                    class: 'align-middle d-none d-md-table-cell text-center fit',
                    sortable: true,
                    sortDirection: 'desc',
                },
                {
                    key: 'created_at',
                    label: this.$t('Registered'),
                    class: 'd-none d-sm-table-cell fit',
                    tdClass: 'align-middle',
                    sortable: true,
                    sortDirection: 'desc',
                    formatter: this.timeFromNow
                }
            ]
        }
    },
    methods: {
        list: usersApi.listWithRoles
    }
}
</script>
