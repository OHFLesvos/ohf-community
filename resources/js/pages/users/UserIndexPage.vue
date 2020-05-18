<template>
    <base-table
        id="usersTable"
        :fields="fields"
        :api-method="list"
        default-sort-by="name"
        :empty-text="$t('app.no_users_found')"
        :items-per-page="25"
    >
        <template v-slot:cell(avatar)="data">
            <img
                :src="data.item.avatar_url_site_header"
                alt="Gravatar"
                style="width: 30px; height: 30px;"
            >
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
import moment from 'moment'
import BaseTable from '@/components/table/BaseTable'
import EmailLink from '@/components/common/EmailLink'
import usersApi from '@/api/user_management/users'
export default {
    components: {
        BaseTable,
        EmailLink
    },
    data() {
        return {
            fields: [
                {
                    key: 'avatar',
                    label: this.$t('userprofile.avatar'),
                    class: 'fit align-middle text-center'
                },
                {
                    key: 'name',
                    label: this.$t('app.name'),
                    sortable: true,
                    class: 'align-middle'
                },
                {
                    key: 'email',
                    label: this.$t('app.email'),
                    class: 'align-middle d-none d-sm-table-cell'
                },
                {
                    key: 'roles',
                    label: this.$t('app.roles'),
                    class: 'align-middle d-none d-sm-table-cell'
                },
                {
                    key: 'provider_name',
                    label: this.$t('app.oauth'),
                    class: 'align-middle fit d-none d-md-table-cell',
                    sortable: true,
                },
                {
                    key: 'is_2fa_enabled',
                    label: this.$t('userprofile.2FA'),
                    class: 'align-middle d-none d-md-table-cell text-center fit',
                },
                {
                    key: 'is_super_admin',
                    label: this.$t('app.admin'),
                    class: 'align-middle d-none d-md-table-cell text-center fit',
                    sortable: true,
                    sortDirection: 'desc',
                },
                {
                    key: 'created_at',
                    label: this.$t('app.registered'),
                    class: 'd-none d-sm-table-cell fit',
                    tdClass: 'align-middle',
                    sortable: true,
                    sortDirection: 'desc',
                    formatter: value => {
                        return moment(value).fromNow()
                    }
                }
            ]
        }
    },
    methods: {
        list: usersApi.listWithRoles
    }
}
</script>
