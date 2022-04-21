<template>
    <b-card
        class="shadow-sm mb-4"
        :header="$t('Two-Factor Authentication')"
        body-class="pb-1"
        footer-class="d-flex justify-content-between"
    >
        <template v-if="!isTfaConfigured">
            <template v-if="configureTfa">
                <p>{{ $t('Two-Factor Authentication improves the security of your account by requiring an additional code when logging in. This random code is being regenerated every minute on a second device (e.g. your Android or iOS-based smartphone). Therefore, even if your password falls into the wrong hands, a second factor is still required to login successfully into this application.') }}</p>
                <p>{{ $t('A mobile app is required to generate the Two-Factor code. Such apps can be found in the app store of your mobile device. We recommend:') }}</p>
                <ul>
                    <li v-for="link in otpApps" :key="link.href">
                        <a target="_blank" :href="link.href">{{ link.text }}</a>
                    </li>
                </ul>
                <p>{{ $t('Scan the QR code with your authenticator app (e.g. "Google-Authenticator") and enter the numeric code into the field below.') }}</p>
                <p class="text-center">
                    <img
                        :src="`data:image/png;base64,${qrCodeImage}`"
                        class="img-fluid"
                        alt="QR Code">
                    </p>
                <b-form-group>
                    <b-form-input
                        v-model="otpCode"
                        autocomplete="off"
                        required
                        autofocus
                        :disabled="isBusy"
                    />
                </b-form-group>
            </template>
            <template v-else>
                <b-alert variant="info" show>
                    <font-awesome-icon icon="info-circle"/>
                    {{ $t('Improve the security of your account by enabling Two-Factor Authentication.') }}
                </b-alert>
                <b-alert variant="warning" show>
                    <font-awesome-icon icon="exclamation-triangle"/>
                    {{ $t('Two-Factor Authentication is not enabled.') }}
                </b-alert>
            </template>
        </template>
        <template v-else>
            <template v-if="configureTfa">

            </template>
            <template v-else>
                <p>{{ $t('Two-Factor Authentication is enabled') }}</p>
            </template>
        </template>
        <template #footer>
            <b-button
                v-if="configureTfa"
                type="button"
                variant="secondary"
                @click="configureTfa = false"
            >
                {{ $t('Cancel') }}
            </b-button>
            <span v-else></span>
            <b-button
                v-if="!isTfaConfigured"
                type="button"
                variant="primary"
                :disabled="isBusy"
                @click="view2FA"
            >
                <font-awesome-icon icon="check"/>
                {{ $t('Enable') }}
            </b-button>
            <b-button
                v-else
                type="button"
                variant="primary"
                :disabled="isBusy"
            >
                <font-awesome-icon icon="times"/>
                {{ $t('Disable') }}
            </b-button>
        </template>
    </b-card>
</template>

<script>
import userprofileApi from "@/api/userprofile";
// import { showSnackbar } from '@/utils'
export default {
    props: {
        user: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            isBusy: false,
            configureTfa: false,
            otpApps: [
                { href: "https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2", text: "Google Authenticator" },
                { href: "https://play.google.com/store/apps/details?id=com.authy.authy", text: "Authy" },
                { href: "https://play.google.com/store/apps/details?id=org.fedorahosted.freeotp", text: "FreeOTP Authenticator" },
            ],
            otpCode: '',
            qrCodeImage: '',
        }
    },
    computed: {
        isTfaConfigured() {
            return !!this.user.tfa_secret
        },
    },
    methods: {
        async view2FA() {
            this.isBusy = true
            try {
                let data = await userprofileApi.view2FA()
                this.qrCodeImage = data.image
                this.configureTfa = true
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>
