<template>
    <b-container v-if="isLoaded">
        <validation-observer
            ref="form"
            v-slot="{ handleSubmit }"
            slim
        >
            <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
                <b-card
                    class="shadow-sm mb-4"
                    :header="$t('Two-Factor Authentication')"
                    body-class="pb-1"
                    footer-class="d-flex justify-content-between"
                >
                    <b-row>
                        <b-col md>
                            <template v-if="isEnabled">
                                <b-alert variant="success" show>
                                    <font-awesome-icon icon="check"/>
                                    {{ $t('Two-Factor Authentication is enabled.') }}
                                </b-alert>
                            </template>
                            <template v-else>
                                <b-alert variant="warning" show>
                                    <font-awesome-icon icon="exclamation-triangle"/>
                                    {{ $t('Two-Factor Authentication is not enabled.') }}
                                </b-alert>
                                <p>{{ $t('Two-Factor Authentication improves the security of your account by requiring an additional code when logging in. This random code is being regenerated every minute on a second device (e.g. your Android or iOS-based smartphone). Therefore, even if your password falls into the wrong hands, a second factor is still required to login successfully into this application.') }}</p>
                                <p>{{ $t('A mobile app is required to generate the Two-Factor code. Such apps can be found in the app store of your mobile device. We recommend:') }}</p>
                                <ul>
                                    <li v-for="link in otpApps" :key="link.href">
                                        <a target="_blank" :href="link.href">{{ link.text }}</a>
                                    </li>
                                </ul>
                            </template>
                        </b-col>
                        <b-col md>
                            <template v-if="isEnabled">
                                <b-card-title>{{ $t('Disable Two-Factor Authentication') }}</b-card-title>
                                <p>{{ $t('Enter the code from your authenticator app into the field below.') }}</p>
                            </template>
                            <template v-else>
                                <b-card-title>{{ $t('Enable Two-Factor Authentication') }}</b-card-title>
                                <p>{{ $t('Scan the QR code with your authenticator app and enter the numeric code into the field below.') }}</p>
                                <p class="text-center">
                                    <img
                                        v-if="qrCodeImage"
                                        :src="qrCodeImage"
                                        class="img-fluid img-thumbnail"
                                        alt="QR Code">
                                    <b-spinner v-else></b-spinner>
                                </p>
                            </template>
                            <validation-provider
                                :name="$t('Code')"
                                vid="code"
                                :rules="{
                                    required: true,
                                    decimal: true
                                }"
                                v-slot="validationContext"
                            >
                                <b-form-group
                                    :state="getValidationState(validationContext)"
                                    :invalid-feedback="validationContext.errors[0]"
                                >
                                    <b-form-input
                                        v-model="code"
                                        ref="codeInput"
                                        autocomplete="off"
                                        required
                                        autofocus
                                        :disabled="isBusy"
                                        :placeholder="$t('Code')"
                                        :state="getValidationState(validationContext)"
                                    />
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                    </b-row>

                    <template #footer>
                        <b-button
                            type="button"
                            variant="secondary"
                            :disabled="isBusy"
                            @click="$router.push({ name: 'userprofile' })"
                        >
                            {{ $t('Cancel') }}
                        </b-button>
                        <b-button
                            v-if="isEnabled"
                            type="submit"
                            variant="primary"
                            :disabled="isBusy"
                        >
                            <font-awesome-icon icon="times"/>
                            {{ $t('Disable') }}
                        </b-button>
                        <span v-else>
                            <b-button
                                type="button"
                                variant="outline-primary"
                                :disabled="isBusy"
                                @click="fetchData"
                            >
                                <font-awesome-icon icon="sync"/>
                                {{ $t('Refresh') }}
                            </b-button>
                            <b-button
                                type="submit"
                                variant="primary"
                                :disabled="isBusy"
                            >
                                <font-awesome-icon icon="check"/>
                                {{ $t('Enable') }}
                            </b-button>
                        </span>
                    </template>

                </b-card>
            </b-form>
        </validation-observer>
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
import userprofileApi from "@/api/userprofile";
import { showSnackbar } from '@/utils'
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    components: {
        AlertWithRetry,
    },
    title() {
        return this.$t('Two-Factor Authentication')
    },
    data() {
        return {
            isLoaded: false,
            errorText: null,
            isEnabled: false,
            isBusy: false,
            otpApps: [
                { href: "https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2", text: "Google Authenticator" },
                { href: "https://play.google.com/store/apps/details?id=com.authy.authy", text: "Authy" },
                { href: "https://play.google.com/store/apps/details?id=org.fedorahosted.freeotp", text: "FreeOTP Authenticator" },
            ],
            code: '',
            qrCodeImage: null,
        }
    },
    created() {
        this.fetchData()
    },
    methods: {
        async fetchData() {
            this.errorText = null
            this.isBusy = true
            this.qrCodeImage = null
            try {
                let data = await userprofileApi.view2FA()
                this.qrCodeImage = data.image
                this.isEnabled = data.enabled
                this.isLoaded = true
            } catch (err) {
                this.errorText = err
            }
            this.isBusy = false
        },
        async onSubmit() {
            this.isBusy = true
            try {
                let data = await userprofileApi.store2FA(this.code)
                showSnackbar(data.message)
                this.$router.push({ name: 'userprofile' })
            } catch (err) {
                alert(err)
                this.$nextTick(() => {
                    this.$refs.codeInput.focus()
                })
            }
            this.isBusy = false
        },
    }
}
</script>
