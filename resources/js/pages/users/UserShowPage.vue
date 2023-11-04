<template>
    <alert-with-retry
        v-if="error"
        :value="error"
        @retry="fetchData"
    />
    <b-container v-else-if="user">

        <div class="d-sm-flex justify-content-between align-items-center">
            <b-row class="align-items-center mb-3">
                <b-col cols="auto">
                    <UserAvatar
                        :value="user.name"
                        :src="user.avatar_url"
                        size="4em"
                    />
                </b-col>
                <b-col>
                    <h2 class="display-4 p-0">{{ user.name }}</h2>
                </b-col>
            </b-row>
            <div class="mb-3">
                <b-button
                    v-if="user.can_update"
                    type="button"
                    variant="primary"
                    :to="{ name: 'users.edit', params: { id: user.id }}"
                >
                    <font-awesome-icon icon="edit"/>
                    {{  $t('Edit') }}
                </b-button>
            </div>
        </div>


        <b-alert
            v-if="user.is_current_user"
            variant="info"
            show
        >
            <font-awesome-icon icon="info-circle"/>
            {{ $t('This is your own user account.') }}
        </b-alert>

        <b-row>
            <b-col md>

                <b-card :header="$t('User Profile')" class="shadow-sm mb-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">{{ $t('Name') }}</dt>
                        <dd class="col-sm-8">{{ user.name }}</dd>
                        <dt class="col-sm-4">{{ $t('Email address') }}</dt>
                        <dd class="col-sm-8"><a :href="`mailto:${user.email}`">{{ user.email }}</a></dd>
                        <dt class="col-sm-4">{{ $t('Registered') }}</dt>
                        <dd class="col-sm-8">
                            {{ user.created_at | dateTimeFormat }}<br>
                            <small class="text-muted">{{ user.created_at | timeFromNow }}</small>
                        </dd>
                        <dt class="col-sm-4">{{ $t('Updated') }}</dt>
                        <dd class="col-sm-8">
                            {{ user.updated_at | dateTimeFormat }}<br>
                            <small class="text-muted">{{ user.updated_at | timeFromNow }}</small>
                        </dd>
                    </dl>
                </b-card>

                <!-- OAuth provider -->
                <b-card
                    v-if="user.provider_name"
                    :header="$t('OAuth provider')"
                    class="shadow-sm mb-4"
                    footer-class="text-right"
                >
                    <b-card-text>{{ $t('Managed by {provider}', {provider: user.provider_name.capitalize()}) }}</b-card-text>
                    <template #footer>
                        <b-button
                            type="button"
                            variant="secondary"
                            :disabled="isBusy"
                            @click="disableOAuth"
                        >
                            <font-awesome-icon icon="unlink"/>
                            {{ $t('Unlink') }}
                        </b-button>
                    </template>
                </b-card>

                <!-- Two-Factor Authentication -->
                <b-card
                    v-if="user.is_2fa_enabled"
                    :header="$t('Two-Factor Authentication')"
                    class="shadow-sm mb-4"
                    footer-class="text-right"
                >
                    <b-card-text>{{ $t('Two-Factor Authentication is enabled.') }}</b-card-text>
                    <template #footer>
                        <b-button
                            type="button"
                            variant="secondary"
                            :disabled="isBusy"
                            @click="disable2FA"
                        >
                            <font-awesome-icon icon="unlock"/>
                            {{ $t('Disable') }}
                        </b-button>
                    </template>
                </b-card>

                <!-- Roles -->
                <b-card :header="$t('Roles')" class="shadow-sm mb-4" no-body>
                    <b-list-group flush>
                        <b-list-group-item v-for="role in roles" :key="role.id" :to="{ name: 'roles.show', params: { id: role.id } }">
                            {{ role.name }}
                        </b-list-group-item>
                        <b-list-group-item v-if="roles.length == 0">
                            <em>{{ $t('No roles assigned.') }}</em>
                        </b-list-group-item>
                    </b-list-group>
                </b-card>

                <!-- Role Administrator -->
                <b-card
                    v-if="administeredRoles.length > 0"
                    :header="$t('Role Administrator')"
                    class="shadow-sm mb-4"
                    no-body
                >
                    <b-list-group flush>
                        <b-list-group-item v-for="role in administeredRoles" :key="role.id" :to="{ name: 'roles.show', params: { id: role.id } }">
                            {{ role.name }}
                        </b-list-group-item>
                    </b-list-group>
                </b-card>

            </b-col>
            <b-col md>

                <!-- Permissions -->
                <b-card :header="$t('Permissions')" class="shadow-sm mb-4" no-body>
                    <b-list-group flush>
                        <b-list-group-item v-if="user.is_super_admin" variant="warning">
                            {{ $t('This user is an administrator and has therefore all permissions.') }}
                        </b-list-group-item>
                        <b-list-group-item v-for="(v,k) in permissions" :key="k">
                            <b-row>
                                <b-col lg="4">{{ k ? k : $t('General') }}</b-col>
                                <b-col lg>
                                    <ul class="px-0 mb-0">
                                        <li class="ml-4 ml-lg-0 mt-1 mt-lg-0" v-for="x in v" :key="x">{{ x }}</li>
                                    </ul>
                                </b-col>
                            </b-row>
                        </b-list-group-item>
                        <b-list-group-item v-if="Object.keys(permissions).length == 0">
                            <em>{{ $t('No permissions assigned.') }}</em>
                        </b-list-group-item>
                    </b-list-group>
                </b-card>

            </b-col>
        </b-row>
    </b-container>
    <b-container v-else>
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import usersApi from "@/api/user_management/users";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import UserAvatar from "@/components/user_management/UserAvatar.vue"
import { showSnackbar } from '@/utils'
export default {
    title() {
        return this.$t("User");
    },
    components: {
        AlertWithRetry,
        UserAvatar,
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            user: null,
            error: null,
            isBusy: false,
            permissions: {},
            roles: [],
            administeredRoles: [],
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
            this.user = null
            this.error = null
            try {
                let data = await usersApi.find(this.id)
                this.user = data.data
                this.roles = data.data.relationships.roles?.data ?? []
                this.administeredRoles = data.data.relationships.administeredRoles?.data ?? []
                this.permissions = data.permissions
            } catch (err) {
                this.error = err
            }
        },
        async disableOAuth() {
            if (confirm(this.$t('Do you really want to disable OAuth for {name}?', {name: this.user.name } ))) {
                this.isBusy = true
                try {
                    let data = await usersApi.disableOAuth(this.id)
                    this.user.provider_name = null
                    showSnackbar(data.message)
                } catch (ex) {
                    alert(ex)
                }
                this.isBusy = false
            }
        },
        async disable2FA() {
            if (confirm(this.$t('Do you really want to disable Two-Factor Authentication for {name}?', {name: this.user.name } ))) {
                this.isBusy = true
                try {
                    let data = await usersApi.disable2FA(this.id)
                    this.user.is_2fa_enabled = false
                    showSnackbar(data.message)
                } catch (ex) {
                    alert(ex)
                }
                this.isBusy = false
            }
        },
    }
}
</script>
