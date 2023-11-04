<template>
    <b-container>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <template v-else-if="role">
            <RoleForm
                :role="role"
                :rolePermissions="permissions"
                :roleUsers="users"
                :roleAdministrators="administrators"
                :title="$t('Edit Role')"
                :disabled="isBusy"
                @submit="handleUpdate"
                @cancel="handleCancel"
                @delete="handleDelete"
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
import RoleForm from '@/components/user_management/RoleForm.vue'
export default {
    title() {
        return this.$t("Edit Role");
    },
    components: {
        AlertWithRetry,
        RoleForm
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
            permissions: {},
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
                // this.users = data.data.relationships.users?.data ?? []
                // this.administrators = data.data.relationships.administrators?.data ?? []
                this.permissions = Object.values(data.permissions).flat().map(e => Object.keys(e)).flat()
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
                let data = await rolesApi.update(this.id, formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'roles.show', params: { id: this.id }})
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async handleDelete () {
            this.isBusy = true
            try {
                let data = await rolesApi.delete(this.id)
                showSnackbar(data.message)
                this.$router.push({ name: 'roles.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>
