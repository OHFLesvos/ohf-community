<template>
    <alert-with-retry
        v-if="error"
        :value="error"
        @retry="fetchData"
    />
    <b-container v-else-if="user">
        <!-- {{ user }} -->
        <UserForm
            :user="user"
            :title="$t('Edit User')"
            :disabled="isBusy"
            @submit="updateUser"
            @cancel="$router.push({ name: 'users.show', params: { id: id }})"
            @delete="deleteUser"
        />

    </b-container>
    <b-container v-else>
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import usersApi from "@/api/user_management/users";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
import { showSnackbar } from '@/utils'
import UserForm from '@/components/user_management/UserForm.vue'
export default {
    title() {
        return this.$t("Edit User");
    },
    components: {
        AlertWithRetry,
        UserForm
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            user: null,
            error: null,
            isBusy: false,
            permissions: {},
            roles: [],
            administeredRoles: [],
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
            this.user = null
            this.error = null
            try {
                let data = await usersApi.find(this.id)
                this.user = data.data
                this.roles = data.data.relationships.roles?.data ?? []
                this.administeredRoles = data.data.relationships.administeredRoles?.data ?? []
                this.permissions = data.permissions
            } catch (err) {
                this.error = err
            }
        },
        async updateUser (formData) {
            this.isBusy = true
            try {
                let data = await usersApi.update(this.id, formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'users.show', params: { id: this.id }})
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async deleteUser () {
            this.isBusy = true
            try {
                let data = await usersApi.delete(this.id)
                showSnackbar(data.message)
                this.$router.push({ name: 'users.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>
