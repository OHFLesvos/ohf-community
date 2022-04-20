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
                <validation-observer
                    ref="profileForm"
                    v-slot="{ handleSubmit }"
                    slim
                >
                    <b-form @submit.stop.prevent="handleSubmit(updateProfile)">
                        <b-card class="shadow-sm mb-4" :header="$t('Profile')" body-class="pb-2" footer-class="text-right">

                            <validation-provider
                                :name="$t('Name')"
                                vid="name"
                                :rules="{ required: true }"
                                v-slot="validationContext"
                            >
                                <b-form-group
                                    :label="$t('Name')"
                                    label-class="required"
                                    :state="getValidationState(validationContext)"
                                    :invalid-feedback="validationContext.errors[0]"
                                >
                                    <b-form-input
                                        v-model="user.name"
                                        autocomplete="off"
                                        required
                                        :disabled="isBusy"
                                        :state="getValidationState(validationContext)"
                                    />
                                </b-form-group>
                            </validation-provider>

                            <validation-provider
                                :name="$t('E-Mail Address')"
                                vid="email"
                                :rules="{ email: true }"
                                v-slot="validationContext"
                            >
                                <b-form-group
                                    :label="$t('E-Mail Address')"
                                    :label-class="!isOauthActive ? 'required' : null"
                                    :state="getValidationState(validationContext)"
                                    :invalid-feedback="validationContext.errors[0]"
                                >
                                    <b-form-input
                                        v-model="user.email"
                                        type="email"
                                        autocomplete="off"
                                        :disabled="isOauthActive || isBusy"
                                        :required="!isOauthActive"
                                        :state="getValidationState(validationContext)"
                                    />
                                </b-form-group>
                            </validation-provider>
                            <p v-if="isOauthActive">
                                {{ $t('OAuth provider') }}: <strong>{{ user.provider_name.capitalize() }}</strong>
                            </p>
                            <validation-provider
                                :name="$t('Language')"
                                vid="locale"
                                :rules="{ required: true }"
                                v-slot="validationContext"
                            >
                                <b-form-group
                                    :label="$t('Language')"
                                    label-class="required"
                                    :state="getValidationState(validationContext)"
                                    :invalid-feedback="validationContext.errors[0]"
                                >
                                    <b-select
                                        v-model="user.locale"
                                        required
                                        :disabled="isBusy"
                                        :options="languageOptions"
                                        :state="getValidationState(validationContext)"
                                    />
                                </b-form-group>
                            </validation-provider>

                            <template #footer>
                                <b-button
                                    type="submit"
                                    variant="primary"
                                    :disabled="isBusy"
                                >
                                    <font-awesome-icon icon="check"/>
                                    {{ $t('Update') }}
                                </b-button>
                            </template>
                        </b-card>
                    </b-form>
                </validation-observer>
            </b-col>

            <template v-if="!isOauthActive">

                <!-- Change Password -->
                <b-col sm="6">
                    <validation-observer
                        ref="passwordForm"
                        v-slot="{ handleSubmit }"
                        slim
                    >
                        <b-form @submit.stop.prevent="handleSubmit(updatePassword)">
                            <b-card class="shadow-sm mb-4" :header="$t('Change Password')" body-class="pb-2" footer-class="text-right">

                                <validation-provider
                                    :name="$t('Old Password')"
                                    vid="old_password"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        :label="$t('Old Password')"
                                        label-class="required"
                                        :state="getValidationState(validationContext)"
                                        :invalid-feedback="validationContext.errors[0]"
                                    >
                                        <b-form-input
                                            v-model="old_password"
                                            type="password"
                                            autocomplete="off"
                                            required
                                            :disabled="isBusy"
                                            :state="getValidationState(validationContext)"
                                        />
                                    </b-form-group>
                                </validation-provider>

                                <validation-provider
                                    :name="$t('New password')"
                                    vid="password"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        :label="$t('New password')"
                                        label-class="required"
                                        :state="getValidationState(validationContext)"
                                        :invalid-feedback="validationContext.errors[0]"
                                    >
                                        <b-form-input
                                            v-model="password"
                                            type="password"
                                            autocomplete="off"
                                            required
                                            :disabled="isBusy"
                                            :state="getValidationState(validationContext)"
                                        />
                                    </b-form-group>
                                </validation-provider>

                                <validation-provider
                                    :name="$t('Confirm password')"
                                    vid="password_confirmation"
                                    :rules="{ required: true }"
                                    v-slot="validationContext"
                                >
                                    <b-form-group
                                        :label="$t('Confirm password')"
                                        label-class="required"
                                        :state="getValidationState(validationContext)"
                                        :invalid-feedback="validationContext.errors[0]"
                                    >
                                        <b-form-input
                                            v-model="password_confirmation"
                                            type="password"
                                            autocomplete="off"
                                            required
                                            :disabled="isBusy"
                                            :state="getValidationState(validationContext)"
                                        />
                                    </b-form-group>
                                </validation-provider>

                                <template #footer>
                                    <b-button
                                        type="submit"
                                        variant="primary"
                                        :disabled="isBusy"
                                    >
                                        <font-awesome-icon icon="check"/>
                                        {{ $t('Update password') }}
                                    </b-button>
                                </template>

                            </b-card>
                        </b-form>
                    </validation-observer>
                </b-col>

                <!-- Two-Factor Authentication -->
                <b-col sm="6">
                    <b-card class="shadow-sm mb-4" :header="$t('Two-Factor Authentication')" body-class="pb-1" footer-class="text-right">
                        <template v-if="!isTfaConfigured">
                            <b-alert variant="info" show>
                                <font-awesome-icon icon="info-circle"/>
                                {{ $t('Improve the security of your account by enabling Two-Factor Authentication.') }}
                            </b-alert>
                            <b-alert variant="warning" show>
                                <font-awesome-icon icon="exclamation-triangle"/>
                                {{ $t('Two-Factor Authentication is not enabled.') }}
                            </b-alert>
                        </template>
                        <template v-else>
                            <p>{{ $t('Two-Factor Authentication is enabled') }}</p>
                        </template>
                        <template #footer>
                            <a v-if="!isTfaConfigured" href="route('userprofile.view2FA')" class="btn btn-primary">
                                <font-awesome-icon icon="check"/>
                                {{ $t('Enable') }}
                            </a>
                            <a v-else href="route('userprofile.view2FA')" class="btn btn-primary">
                                <font-awesome-icon icon="times"/>
                                {{ $t('Disable') }}
                            </a>
                        </template>
                    </b-card>
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
import UserAvatar from "@/components/user_management/UserAvatar";
import userprofileApi from "@/api/userprofile";
import { showSnackbar } from '@/utils'
import moment from 'moment'
export default {
    components: {
        UserAvatar
    },
    title() {
        return this.$t('User Profile')
    },
    data() {
        return {
            user: null,
            old_password: '',
            password: '',
            password_confirmation: '',
            languages: {},
            isBusy: false,
            hasBeenDeleted: false,
        }
    },
    computed: {
        isOauthActive() {
            return !!this.user.provider_name
        },
        isTfaConfigured() {
            return !!this.user.tfa_secret
        },
        languageOptions() {
            return Object.entries(this.languages).map(e => ({ value: e[0], text: e[1]}))
        }
    },
    async created() {
        let data = await userprofileApi.list();
        this.user = data.user;
        this.languages = data.languages;
    },
    methods: {
        async updateProfile() {
            this.isBusy = true
            try {
                let data = await userprofileApi.updateProfile({
                    name: this.user.name,
                    email: this.user.email,
                    locale: this.user.locale,
                })
                this.$i18n.locale = this.user.locale
                moment.locale(this.user.locale);
                showSnackbar(data.message)
                this.$nextTick(() => {
                    this.$refs.profileForm.reset();
                });
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async updatePassword() {
            this.isBusy = true
            try {
                let data = await userprofileApi.updatePassword({
                    old_password: this.old_password,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                this.old_password = this.password = this.password_confirmation = ''
                showSnackbar(data.message)
                this.$nextTick(() => {
                    this.$refs.passwordForm.reset();
                });
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
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

