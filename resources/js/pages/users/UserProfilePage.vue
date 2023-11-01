<template>
    <b-container v-if="user" class="mt-3">

        <b-row class="align-items-center">
            <b-col cols="auto">
                <UserAvatar
                    :value="user.name"
                    :src="avatar_url"
                    size="4em"
                />
            </b-col>
            <b-col>
                <h2 class="display-4 p-0">{{ user.name }}</h2>
            </b-col>
        </b-row>
        <hr>

        <b-row>

            <b-col md="6">

                <!-- Profile -->
                <UserProfileDialog
                    :user="user"
                    :languages="languages"
                    @update="fetchData"
                />

                <template v-if="!isOauthActive">

                    <!-- Change password -->
                    <ChangePasswordDialog/>

                    <!-- Two-factor auth -->
                    <TFADialog :user="user"/>

                </template>

            </b-col>

            <b-col md="6">

                <!-- Account Information -->
                <b-card class="shadow-sm mb-4" :header="$t('Account Information')">
                    <b-card-text v-html="$t('Your account has been created on {created} and and last updated on {updated}.', {
                        created: `<strong>${dateTimeFormat(user.created_at)}</strong>`,
                        updated: `<strong>${dateTimeFormat(user.updated_at)}</strong>`,
                    })"></b-card-text>
                </b-card>

                <!-- Roles -->
                <b-card v-if="user.roles.length > 0 || user.is_super_admin" md="6" class="shadow-sm mb-4" :header="$t('Your roles')" no-body>
                    <b-list-group flush>
                        <b-list-group-item v-if="user.is_super_admin" variant="warning">
                            {{ $t('This user is an administrator and has therefore all permissions.') }}
                        </b-list-group-item>
                        <b-list-group-item v-for="role in user.roles" :key="role.id">
                            {{ role.name }}
                        </b-list-group-item>
                    </b-list-group>
                </b-card>

                <!-- Account Removal -->
                <AccountDeleteDialog
                    :canDelete="canDelete"
                    @delete="$router.push({ name: 'userprofile.deleted' })"
                />

            </b-col>

        </b-row>
    </b-container>
    <b-container v-else>
        <AlertWithRetry
            :value="errorText"
            @retry="fetchData"
        />
        <p v-if="!errorText">{{ $t("Loading...") }}</p>
    </b-container>
</template>

<script>
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import UserAvatar from "@/components/user_management/UserAvatar.vue"
import UserProfileDialog from "@/components/userprofile/UserProfileDialog.vue"
import ChangePasswordDialog from "@/components/userprofile/ChangePasswordDialog.vue"
import TFADialog from "@/components/userprofile/TFADialog.vue"
import AccountDeleteDialog from "@/components/userprofile/AccountDeleteDialog.vue"
import userprofileApi from "@/api/userprofile"
export default {
    components: {
        AlertWithRetry,
        UserAvatar,
        UserProfileDialog,
        ChangePasswordDialog,
        TFADialog,
        AccountDeleteDialog
    },
    title() {
        return this.$t('User Profile')
    },
    data() {
        return {
            user: null,
            languages: {},
            isBusy: false,
            errorText: null,
            canDelete: false,
            avatar_url: null,
        }
    },
    computed: {
        isOauthActive() {
            return !!this.user.provider_name
        },
    },
    async created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.errorText = null
            try {
                let data = await userprofileApi.list()
                this.user = data.user
                this.avatar_url = data.avatar_url
                this.languages = data.languages
                this.canDelete = data.can_delete
            } catch (err) {
                this.errorText = err
            }
        },
    }
}
</script>

