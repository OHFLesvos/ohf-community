<template>
    <alert-with-retry
        v-if="error"
        :value="error"
        @retry="fetchData"
    />
    <b-container v-else-if="role">

        <h2 class="display-4">{{ role.name }}</h2>

        <!-- <b-alert
            v-if="user.is_current_user"
            variant="info"
            show
        >
            <font-awesome-icon icon="info-circle"/>
            {{ $t('This is your own user account.') }}
        </b-alert> -->
        <p>
            <b-button
                v-if="role.can_update"
                type="button"
                variant="primary"
                :to="{ name: 'roles.edit', params: { id: role.id }}"
            >
                <font-awesome-icon icon="edit"/>
                {{  $t('Edit') }}
            </b-button>
        </p>

    </b-container>
    <b-container v-else>
        {{ $t('Loading...') }}
    </b-container>
</template>

<script>
import rolesApi from "@/api/user_management/roles";
import AlertWithRetry from '@/components/alerts/AlertWithRetry.vue'
// import { showSnackbar } from '@/utils'
export default {
    title() {
        return this.$t("Role");
    },
    components: {
        AlertWithRetry,
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
            // permissions: {},
            // roles: [],
            // administeredRoles: [],
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
                // this.roles = data.data.relationships.roles?.data ?? []
                // this.administeredRoles = data.data.relationships.administeredRoles?.data ?? []
                // this.permissions = data.permissions
            } catch (err) {
                this.error = err
            }
        },
    }
}
</script>
