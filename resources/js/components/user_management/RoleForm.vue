<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card :title="title" body-class="pb-2" footer-class="d-flex justify-content-between align-items-start" class="mb-3">
                <b-form-row>

                    <!-- Name -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Name')"
                            vid="name"
                            :rules="{ required: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Name')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                            >
                                <b-form-input
                                    v-model="form.name"
                                    required
                                    autocomplete="off"
                                    :autofocus="!role"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>
            </b-card>

            <!-- Permissions -->
            <b-card class="mb-3" :header="$t('Permissions')" body-class="columns-2">
                <div v-for="(elements, title, idx) in permissions" :key="title" class="column-break-avoid">
                    <h5 :class="{'mt-3':idx != 0}">{{ title == '' ? $t('General') : title }}</h5>
                    <b-form-checkbox
                        v-for="(label, value) in elements" :key="value"
                        :checked="form.permissions.includes(value)"
                        @change="permissionChanged(value, $event)"
                    >{{ label }}</b-form-checkbox>
                </div>
                <em v-if="Object.values(permissions).length == 0">{{ $t('No permissions defined.') }}</em>
            </b-card>

            <!-- Users -->
            <b-card :header="`${$t('Users')} (${form.users.length})`" body-class="pb-0" class="mb-2">
                <BaseTable
                    :id="`roleUsers${role.id}`"
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
                            :disabled="!form.users.includes(data.item.id) && !form.administrators.includes(data.item.id)"
                            :checked="form.administrators.includes(data.item.id)"
                            @change="administratorAssignmentChanged(data.item.id, $event)"
                        />
                    </template>
                </BaseTable>
            </b-card>

            <div class="d-flex justify-content-between align-items-start my-3">
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

                <!-- Delete -->
                <b-button
                    v-if="role && role.can_delete"
                    variant="link"
                    :disabled="disabled"
                    class="text-danger"
                    @click="onDelete"
                >
                    {{ $t('Delete') }}
                </b-button>
            </div>
        </b-form>
    </validation-observer>
</template>

<script>
import formValidationMixin from "@/mixins/formValidationMixin";
import usersApi from '@/api/user_management/users'
import rolesApi from '@/api/user_management/roles'
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
        rolePermissions: {
            type: Array,
            default: () => [],
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
        disabled: Boolean
    },
    data () {
        return {
            form: this.role ? {
                name: this.role.name,
                users: this.roleUsers.map(r => r.id),
                administrators: this.roleAdministrators.map(r => r.id),
                permissions: [...this.rolePermissions],
            } : {
                name: null,
                users: [],
                administrators: [],
                permissions: [],
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
            permissions: {}
        }
    },
    async created () {
        this.fetchData()
    },
    methods: {
        async fetchData () {
            this.permissions = await rolesApi.permissions()
        },
        fetchUsers: usersApi.list,
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Really delete this role?'))) {
                this.$emit('delete')
            }
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
        permissionChanged(permission, checked) {
            if (checked && !this.form.permissions.includes(permission)) {
                this.form.permissions.push(permission)
            } else if (!checked && this.form.permissions.includes(permission)) {
                this.form.permissions.splice(this.form.permissions.indexOf(permission), 1)
            }
        },
    }
}
</script>
