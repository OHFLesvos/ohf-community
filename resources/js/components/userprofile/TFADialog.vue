<template>
    <b-card
        class="shadow-sm mb-4"
        :header="$t('Two-Factor Authentication')"
        footer-class="text-right"
        body-class="pb-1"
    >
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
            <b-button
                v-if="!isTfaConfigured"
                type="button"
                variant="primary"
                @click="$router.push({ name: 'userprofile.2FA' })"
            >
                <font-awesome-icon icon="check"/>
                {{ $t('Enable') }}
            </b-button>
            <b-button
                v-else
                type="button"
                variant="primary"
                @click="$router.push({ name: 'userprofile.2FA' })"
            >
                <font-awesome-icon icon="times"/>
                {{ $t('Disable') }}
            </b-button>
        </template>

    </b-card>
</template>

<script>
export default {
    props: {
        user: {
            required: true,
            type: Object
        }
    },
    computed: {
        isTfaConfigured() {
            return !!this.user.tfa_secret
        },
    },
}
</script>
