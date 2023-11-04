<template>
    <b-container fluid>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <template v-else-if="role">
            <div class="d-sm-flex justify-content-between align-items-center">
                <h2 class="display-4">{{ role.name }}</h2>
                <div class="mb-3">
                    <b-button
                        v-if="role.can_update"
                        type="button"
                        variant="primary"
                        :to="{ name: 'roles.edit', params: { id: role.id }}"
                    >
                        <font-awesome-icon icon="edit"/>
                        {{  $t('Edit') }}
                    </b-button>
                </div>
            </div>

            <b-alert
                v-if="isAdministrator"
                variant="info"
                show
            >
                <font-awesome-icon icon="info-circle"/>
                {{ $t('You are administrator of this role.') }}
            </b-alert>

            <div class="mb-3">
                {{ $t('Created') }}: {{ role.created_at|dateFormat }}<br>
                {{ $t('Last updated') }}: {{ role.updated_at|dateFormat }}
            </div>

            <b-row>
                <b-col md>
                    <b-card no-body class="mb-3">
                        <b-card-header>
                            {{ $t('Permissions') }}
                            <b-badge>{{ Object.values(permissions).reduce((a,b) => a + b.length , 0) }}</b-badge>
                        </b-card-header>
                        <b-list-group flush>
                            <TwoColListGroupItem v-for="(p,k) in permissions" :key="k" :title="k">
                                <div v-for="e in p" :key="e">{{ e }}</div>
                            </TwoColListGroupItem>
                            <b-list-group-item v-if="Object.values(permissions).length == 0">
                                <em>{{ $t('No permissions assigned.') }}</em>
                            </b-list-group-item>
                        </b-list-group>
                    </b-card>
                    <b-card no-body class="mb-3">
                        <b-card-header>
                            {{ $t('Role Administrators') }}
                            <b-badge>{{ administrators.length }}</b-badge>
                        </b-card-header>
                        <b-list-group flush>
                            <b-list-group-item v-for="user in administrators" :key="user.id" :to="{ name: 'users.show', params: { id: user.id } }">
                                {{ user.name }}
                            </b-list-group-item>
                            <b-list-group-item v-if="administrators.length == 0">
                                <em>{{ $t('No users assigned.') }}</em>
                            </b-list-group-item>
                        </b-list-group>
                    </b-card>
                </b-col>
                <b-col md>
                    <b-card no-body class="mb-3">
                        <b-card-header>
                            {{ $t('Users') }}
                            <b-badge>{{ users.length }}</b-badge>
                        </b-card-header>
                        <b-list-group flush>
                            <b-list-group-item v-for="user in users" :key="user.id" :to="{ name: 'users.show', params: { id: user.id } }">
                                {{ user.name }}
                            </b-list-group-item>
                            <b-list-group-item v-if="users.length == 0">
                                <em>{{ $t('No users assigned.') }}</em>
                            </b-list-group-item>
                        </b-list-group>
                    </b-card>
                </b-col>
            </b-row>
        </template>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </b-container>
</template>

<script>
import rolesApi from "@/api/user_management/roles";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import TwoColListGroupItem from "@/components/ui/TwoColListGroupItem.vue";
export default {
    title() {
        return this.$t("Role");
    },
    components: {
        AlertWithRetry,
        TwoColListGroupItem,
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            role: null,
            error: null,
            isBusy: false,
            isAdministrator: false,
            permissions: {},
            users: [],
            administrators: [],
        }
    },
    watch: {
        $route() {
            this.fetchData()
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.role = null
            this.error = null
            try {
                let data = await rolesApi.find(this.id)
                this.role = data.data
                this.permissions = data.permissions
                this.users = data.users
                this.administrators = data.administrators
                this.isAdministrator = data.is_administrator
                // this.users = data.data.relationships.roles?.data ?? []
                // this.administeredRoles = data.data.relationships.administeredRoles?.data ?? []
            } catch (err) {
                this.error = err
            }
        },
    }
}
</script>
