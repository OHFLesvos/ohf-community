<template>
    <b-container>
        <template>
            <RoleForm
                :title="$t('Create Role')"
                :disabled="isBusy"
                @submit="handleStore"
                @cancel="handleCancel"
            />
        </template>
    </b-container>
</template>

<script>
import rolesApi from "@/api/user_management/roles";
import { showSnackbar } from '@/utils'
import RoleForm from '@/components/user_management/RoleForm.vue'
export default {
    title() {
        return this.$t("Create Role");
    },
    components: {
        RoleForm
    },
    data() {
        return {
            isBusy: false,
        }
    },
    methods: {
        handleCancel() {
            this.$router.push({ name: 'roles.index' })
        },
        async handleStore (formData) {
            this.isBusy = true
            try {
                let data = await rolesApi.store(formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'roles.show', params: { id: data.id }})
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
    }
}
</script>
