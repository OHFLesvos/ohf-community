<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card :title="$t('Manage members')" :sub-title="role.name" body-class="pb-0"  footer-class="d-flex justify-content-between align-items-start" class="mb-3">
                <hr>
                <BaseTable
                    :id="`roleUsers${role?.id}`"
                    :fields="userTableFields"
                    :api-method="fetchUsers"
                    default-sort-by="name"
                    :empty-text="$t('No users found.')"
                    :items-per-page="10"
                >
                    <template v-slot:cell(member)="data">
                        <b-form-checkbox
                            :checked="form.users.includes(data.item.id)"
                            @change="userAssignmentChanged(data.item.id, $event)"
                        />
                    </template>
                    <template v-slot:cell(administrator)="data">
                        <b-form-checkbox
                            v-if="allowEditAdministrators"
                            :disabled="!form.users.includes(data.item.id) && !form.administrators.includes(data.item.id)"
                            :checked="form.administrators.includes(data.item.id)"
                            @change="administratorAssignmentChanged(data.item.id, $event)"
                        />
                        <font-awesome-icon v-else :icon="form.administrators.includes(data.item.id) ? 'check' : 'times'" :class="form.administrators.includes(data.item.id) ? 'text-success' : 'text-muted'"/>
                    </template>
                </BaseTable>
                <p><small>{{ $t('Current selection: {users} users and {administrators} role administrators', { users:form.users.length, administrators: form.administrators.length }) }}</small></p>

                <template #footer>
                    <span>
                        <!-- Submit -->
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="disabled"
                        >
                            <font-awesome-icon icon="check" />
                            {{ role ? $t('Update') : $t('Add') }}
                        </b-button>

                        <!-- Cancel -->
                        <b-button
                            variant="link"
                            :disabled="disabled"
                            @click="$emit('cancel')"
                        >
                            {{ $t('Cancel') }}
                        </b-button>
                    </span>
                </template>
            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import formValidationMixin from "@/mixins/formValidationMixin";
import usersApi from '@/api/user_management/users'
import BaseTable from "@/components/table/BaseTable.vue";
export default {
    components: {
        BaseTable
    },
    mixins: [formValidationMixin],
    props: {
        role: {
            type: Object,
            required: false
        },
        roleUsers: {
            type: Array,
            default: () => [],
        },
        roleAdministrators: {
            type: Array,
            default: () => [],
        },
        title: {
            required: false,
            default: undefined
        },
        allowEditAdministrators: Boolean,
        disabled: Boolean
    },
    data () {
        return {
            form: this.role ? {
                users: this.roleUsers.map(r => r.id),
                administrators: this.roleAdministrators.map(r => r.id),
            } : {
                users: [],
                administrators: [],
            },
            userTableFields: [
                {
                    key: "name",
                    label: this.$t("Name"),
                    sortable: true,
                    class: "align-middle"
                },
                {
                    key: "email",
                    label: this.$t("Email address"),
                    class: "align-middle d-none d-sm-table-cell"
                },
                {
                    key: "member",
                    label: this.$t("Member"),
                    class: 'text-center',
                },
                {
                    key: "administrator",
                    label: this.$t("Administrator"),
                    class: 'text-center',
                },
            ],
        }
    },
    methods: {
        fetchUsers: usersApi.list,
        onSubmit () {
            this.$emit('submit', this.form)
        },
        userAssignmentChanged(userId, checked) {
            if (checked && !this.form.users.includes(userId)) {
                this.form.users.push(userId)
            } else if (!checked && this.form.users.includes(userId)) {
                this.form.users.splice(this.form.users.indexOf(userId), 1)
                if (this.form.administrators.includes(userId)) {
                    this.form.administrators.splice(this.form.administrators.indexOf(userId), 1)
                }
            }
        },
        administratorAssignmentChanged(userId, checked) {
            if (checked && !this.form.administrators.includes(userId)) {
                this.form.administrators.push(userId)
            } else if (!checked && this.form.administrators.includes(userId)) {
                this.form.administrators.splice(this.form.administrators.indexOf(userId), 1)
            }
        },
    }
}
</script>
