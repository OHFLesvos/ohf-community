<template>
    <b-container v-if="user">

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

        <b-row>

            <!-- Profile -->
            <b-col sm="6">
                <UserProfileDialog
                    :user="user"
                    :languages="languages"
                    @update="fetchData"
                />
            </b-col>

            <template v-if="!isOauthActive">

                <!-- Change Password -->
                <b-col sm="6">
                    <ChangePasswordDialog/>
                </b-col>

                <!-- Two-Factor Authentication -->
                <b-col sm="6">
                    <TFADialog :user="user"/>
                </b-col>

            </template>

            <!-- Roles -->
            <b-col v-if="user.roles.length > 0" sm="6">
                <b-card class="shadow-sm mb-4" :header="$t('Your roles')" no-body>
                    <b-list-group flush>
                        <b-list-group-item v-for="role in user.roles" :key="role.id">
                            {{ role.name }}
                        </b-list-group-item>
                    </b-list-group>
                </b-card>
            </b-col>

            <!-- Account Information -->
            <b-col sm="6">
                <b-card class="shadow-sm mb-4" :header="$t('Account Information')">
                    <b-card-text v-html="$t('Your account has been created on {created} and and last updated on {updated}.', {
                        created: `<strong>${dateTimeFormat(user.created_at)}</strong>`,
                        updated: `<strong>${dateTimeFormat(user.updated_at)}</strong>`,
                    })"></b-card-text>
                </b-card>
            </b-col>

            <!-- Account Removal -->
            <b-col sm="6">
                <AccountDeleteDialog v-if="canDelete" @delete="$router.push({ name: 'userprofile.deleted' })"/>
                <b-alert v-else show variant="info">
                    {{ $t('Account cannot be deleted as it is the only remaining account with super-admin privileges.') }}
                </b-alert>
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
import AlertWithRetry from '@/components/alerts/AlertWithRetry'
import UserAvatar from "@/components/user_management/UserAvatar"
import UserProfileDialog from "@/components/userprofile/UserProfileDialog"
import ChangePasswordDialog from "@/components/userprofile/ChangePasswordDialog"
import TFADialog from "@/components/userprofile/TFADialog"
import AccountDeleteDialog from "@/components/userprofile/AccountDeleteDialog"
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
                this.languages = data.languages
                this.canDelete = data.can_delete
            } catch (err) {
                this.errorText = err
            }
        },
    }
}
</script>

