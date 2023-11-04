<template>
    <b-container>
        <UserForm
            :title="$t('Create User')"
            :disabled="isBusy"
            @submit="registerUser"
            @cancel="handleCancel"
        />

    </b-container>
</template>

<script>
import usersApi from "@/api/user_management/users";
import { showSnackbar } from '@/utils'
import UserForm from '@/components/user_management/UserForm.vue'
export default {
    title() {
        return this.$t("Create User");
    },
    components: {
        UserForm
    },
    data() {
        return {
            isBusy: false,
        }
    },
    methods: {
        async registerUser (formData) {
            this.isBusy = true
            try {
                let data = await usersApi.store(formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'users.show', params: { id: data.id } })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'users.index' })
        }
    }
}
</script>
