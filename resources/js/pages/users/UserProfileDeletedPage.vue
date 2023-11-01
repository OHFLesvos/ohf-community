<template>
    <b-container class="mt-3">
        <b-alert variant="info" show>
            <font-awesome-icon icon="info-circle"/>
            {{ $t('Your account has been deleted.') }}
        </b-alert>

        <div class="text-center mt-4">
            <a :href="route('login')">{{ $t('Return to login') }}</a>
        </div>
    </b-container>
</template>

<script>
export default {
    title() {
        return this.$t('Account Deletion')
    },
}
</script>

