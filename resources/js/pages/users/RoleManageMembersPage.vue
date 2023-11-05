<template>
    <b-container>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <template v-else-if="role">
            <RoleMembersForm
                :role="role"
                :roleUsers="users"
                :roleAdministrators="administrators"
                :title="$t('Edit members')"
                :disabled="isBusy"
                :allowEditAdministrators="false"
                @submit="handleUpdate"
                @cancel="handleCancel"
            />
        </template>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </b-container>
</template>

<script>
import rolesApi from "@/api/user_management/roles";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import { showSnackbar } from '@/utils'
import RoleMembersForm from '@/components/user_management/RoleMembersForm.vue'
export default {
    title() {
        return this.$t("Manage members");
    },
    components: {
        AlertWithRetry,
        RoleMembersForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            role: null,
            error: null,
            isBusy: false,
            users: [],
            administrators: [],
        }
    },
    watch: {
        $route() {
            this.fetchData()
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.role = null
            this.error = null
            try {
                let data = await rolesApi.find(this.id)
                this.role = data.data
                this.users = data.users
                this.administrators = data.administrators
            } catch (err) {
                this.error = err
            }
        },
        handleCancel() {
            this.$router.push({ name: 'roles.show', params: { id: this.id }})
        },
        async handleUpdate (formData) {
            this.isBusy = true
            try {
                let data = await rolesApi.updateMembers(this.id, formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'roles.show', params: { id: this.id }})
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
    }
}
</script>
