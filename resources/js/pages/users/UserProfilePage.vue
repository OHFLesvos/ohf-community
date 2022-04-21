<template>
    <b-container v-if="hasBeenDeleted">
        <h2 class="display-4 p-0">{{ $t('Account Deletion') }}</h2>

        <b-alert variant="info" show>
            <font-awesome-icon icon="info-circle"/>
            {{ $t('Your account has been deleted.') }}
        </b-alert>

        <div class="text-center mt-4">
            <a :href="route('login')">{{ $t('Return to login') }}</a>
        </div>
    </b-container>
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
                    <TfaConfiguration :user="user"/>
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
                <b-card
                    class="shadow-sm mb-4"
                    :header="$t('Account Removal')"
                    body-class="pb-1"
                    footer-class="text-right"
                >
                    <p>{{ $t('If you no longer plan to use this service, you can remove your account and delete all associated data.') }}</p>
                    <template #footer>
                        <b-button
                            type="button"
                            variant="danger"
                            :disabled="isBusy"
                            @click="confirmDelete"
                        >
                            <font-awesome-icon icon="user-times"/>
                            {{ $t('Delete account') }}
                        </b-button>
                    </template>
                </b-card>
            </b-col>

        </b-row>
    </b-container>
    <b-container v-else>
        {{ $t("Loading...") }}
    </b-container>
</template>

<script>
import UserAvatar from "@/components/user_management/UserAvatar"
import UserProfileDialog from "@/components/userprofile/UserProfileDialog"
import ChangePasswordDialog from "@/components/userprofile/ChangePasswordDialog"
import TfaConfiguration from "@/components/userprofile/TfaConfiguration"
import userprofileApi from "@/api/userprofile"
import { showSnackbar } from '@/utils'
export default {
    components: {
        UserAvatar,
        UserProfileDialog,
        ChangePasswordDialog,
        TfaConfiguration,
    },
    title() {
        return this.$t('User Profile')
    },
    data() {
        return {
            user: null,
            languages: {},
            isBusy: false,
            hasBeenDeleted: false,
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
            let data = await userprofileApi.list()
            this.user = data.user
            this.languages = data.languages
        },
        async confirmDelete() {
            if (confirm(this.$t('Do you really want to delete your account and lose access to all data?'))) {
                this.isBusy = true
                try {
                    let data = await userprofileApi.delete()
                    showSnackbar(data.message)
                    this.hasBeenDeleted = true
                } catch (err) {
                    alert(err)
                }
                this.isBusy = false
            }
        },
    }
}
</script>

