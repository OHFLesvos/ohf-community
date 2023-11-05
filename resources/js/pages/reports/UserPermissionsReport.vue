<template>
    <b-container>
        <alert-with-retry
            v-if="error"
            :value="error"
            @retry="fetchData"
        />
        <template v-else-if="permissions">
            <div class="columns-3 mb-3">
                <div v-for="(elements, title) in permissions" :key="title" class="mb-4 column-break-avoid">
                    <h4>{{ title == null || title == '' ? $t('General') : title }}</h4>
                    <div v-for="(label, key) in elements" :key="key" class="mb-4 column-break-avoid">
                        <h6>{{ label }} <small>({{ Object.values(users[key]).length }})</small></h6>
                        <template v-for="user in users[key]">
                            <div :key="user.id">
                                <router-link  :to="{ name: 'users.show', params: { id: user.id } }" :title="user.email">{{ user.name }}</router-link>
                                <font-awesome-icon v-if="user.is_super_admin" icon="user-shield" class="ml-2"/>
                            </div>
                        </template>
                        <div v-if="Object.values(users[key]).length == 0">
                            <em>{{ $t('No users assigned.') }}</em>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <p v-else>
            {{ $t('Loading...') }}
        </p>
    </b-container>
</template>

<script>
import usersApi from "@/api/user_management/users";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
export default {
    title() {
        return this.$t("Report") + ": " + this.$t("User Permissions");
    },
    components: {
        AlertWithRetry
    },
    data() {
        return {
            error: null,
            permissions: null,
            users: null
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.permissions = null
            this.users = null
            this.error = null
            try {
                let data = await usersApi.permissionsReport()
                this.permissions = data.permissions
                this.users = data.users
            } catch (err) {
                this.error = err
            }
        },
    }
};
</script>
