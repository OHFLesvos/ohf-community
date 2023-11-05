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
                        <h6>{{ label }} <small>({{ Object.values(roles[key]).length }})</small></h6>
                        <template v-for="role in roles[key]">
                            <div :key="role.id">
                                <router-link  :to="{ name: 'roles.show', params: { id: role.id } }">{{ role.name }}</router-link>
                            </div>
                        </template>
                        <div v-if="Object.values(roles[key]).length == 0">
                            <em>{{ $t('No roles assigned.') }}</em>
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
import rolesApi from "@/api/user_management/roles";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
export default {
    title() {
        return this.$t("Report") + ": " + this.$t("Role Permissions");
    },
    components: {
        AlertWithRetry
    },
    data() {
        return {
            error: null,
            permissions: null,
            roles: null
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.permissions = null
            this.roles = null
            this.error = null
            try {
                let data = await rolesApi.permissionsReport()
                this.permissions = data.permissions
                this.roles = data.roles
            } catch (err) {
                this.error = err
            }
        },
    }
};
</script>
