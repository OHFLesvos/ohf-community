<template>
    <alert-with-retry
        v-if="error"
        :value="error"
        @retry="fetchData"
    />
    <b-container v-else-if="user">

        <b-row class="align-items-center">
            <b-col cols="auto">
                <UserAvatar
                    :value="user.name"
                    size="4em"
                />
            </b-col>
            <b-col>
                <h2 class="display-4 p-0">{{ user.name }}</h2>
            </b-col>
        </b-row>
        <hr>

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
                        <dt class="col-sm-4">{{ $t('E-Mail Address') }}</dt>
                        <dd class="col-sm-8"><a :href="`mailto:${user.email}`">{{ user.email }}</a></dd>

                        <template v-if="user.provider_name">
                            <dt class="col-sm-4">{{ $t('OAuth provider') }}</dt>
                            <dd class="col-sm-8">
                                {{ user.provider_name.capitalize() }}<br>
                                <b-button
                                    size="sm"
                                    type="button"
                                    variant="secondary"
                                    :disabled="isBusy"
                                    @click="removeOauth"
                                >
                                    <font-awesome-icon icon="unlink"/>
                                    {{ $t('Remove') }}
                                </b-button>
                            </dd>
                        </template>

                        <template v-if="user.is_2fa_enabled">
                            <dt class="col-sm-4">{{ $t('Two-Factor Authentication') }}</dt>
                            <dd class="col-sm-8">
                                {{ ('Two-Factor Authentication is enabled.') }}<br>
                                <b-button
                                    size="sm"
                                    type="button"
                                    variant="secondary"
                                    :disabled="isBusy"
                                    @click="remove2FA"
                                >
                                    <font-awesome-icon icon="unlock"/>
                                    {{ $t('Disable') }}
                                </b-button>
                            </dd>
                        </template>

                        <dt class="col-sm-4">{{ $t('Registered') }}</dt>
                        <dd class="col-sm-8">
                            {{ user.created_at | dateTimeFormat }}
                            <small class="text-muted pl-2">{{ user.created_at | timeFromNow }}</small>
                        </dd>
                        <dt class="col-sm-4">{{ $t('Updated') }}</dt>
                        <dd class="col-sm-8">
                            {{ user.updated_at | dateTimeFormat }}
                            <small class="text-muted pl-2">{{ user.updated_at | timeFromNow }}</small>
                        </dd>
                    </dl>
                </b-card>

                <!-- Roles -->
                <b-card :header="$t('Roles')" class="shadow-sm mb-4" no-body>
                    <b-list-group flush>
                        <b-list-group-item v-for="role in roles" :key="role.id" :href="route('roles.show', role)">
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
                        <b-list-group-item v-for="role in administeredRoles" :key="role.id" :href="route('roles.show', role)">
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

        <hr>
        <p>
            <b-button
                type="button"
                variant="secondary"
                :to="{ name: 'users.index' }"
            >
                <font-awesome-icon icon="list"/>
                {{  $t('Overview') }}
            </b-button>
            <b-button
                type="button"
                variant="primary"
                :href="route('users.edit', user.id)"
            >
                <font-awesome-icon icon="edit"/>
                {{  $t('Edit') }}
            </b-button>
        </p>

    </b-container>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import usersApi from "@/api/user_management/users";
import AlertWithRetry from '@/components/alerts/AlertWithRetry'
import UserAvatar from "@/components/user_management/UserAvatar"
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
                this.permissions = data.permissions
                this.roles = data.roles
                this.administeredRoles = data.administeredRoles
            } catch (err) {
                this.error = err
            }
        },
        async removeOauth() {
            if (confirm(this.$t('Do you really want to disable OAuth for {name}?', {name: this.user.name } ))) {
                this.isBusy = true
                // ['route' => ['users.disableOAuth', $user], 'method' => 'put']
                this.isBusy = false
            }
        },
        async remove2FA() {
            if (confirm(this.$t('Do you really want to disable Two-Factor Authentication for {name}?', {name: this.user.name } ))) {
                this.isBusy = true
                // ['route' => ['users.disable2FA', $user], 'method' => 'put']
                this.isBusy = false
            }
        },
    }
}
</script>
