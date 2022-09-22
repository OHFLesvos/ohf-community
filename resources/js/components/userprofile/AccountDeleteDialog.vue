<template>
    <b-card
        class="shadow-sm mb-4"
        :header="$t('Account Removal')"
        footer-class="text-right"
        :no-body="!canDelete"
    >
        <b-card-text v-if="canDelete">
            {{ $t('If you no longer plan to use this service, you can remove your account and delete all associated data.') }}
        </b-card-text>
        <b-alert v-else show variant="info" class="m-0">
            {{ $t('Account cannot be deleted as it is the only remaining account with super-admin privileges.') }}
        </b-alert>
        <template v-if="canDelete" #footer>
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
</template>

<script>
import userprofileApi from "@/api/userprofile"
import { showSnackbar } from '@/utils'
export default {
    props: {
        canDelete: Boolean,
    },
    data() {
        return {
            isBusy: false,
        }
    },
    methods: {
        async confirmDelete() {
            if (confirm(this.$t('Do you really want to delete your account and lose access to all data?'))) {
                this.isBusy = true
                try {
                    let data = await userprofileApi.delete()
                    showSnackbar(data.message)
                    this.$emit('delete')
                } catch (err) {
                    alert(err)
                }
                this.isBusy = false
            }
        },
    }
}
</script>
