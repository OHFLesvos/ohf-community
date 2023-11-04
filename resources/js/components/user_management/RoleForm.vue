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

                <!-- Users -->
                <b-form-group
                    v-if="users.length > 0"
                    :label="$t('Users')"
                >
                    <div class="columns-2">
                        <b-form-checkbox-group
                            v-model="form.users"
                            :options="users"
                            stacked
                        />
                    </div>
                </b-form-group>
                <template v-else>
                    <p><em>{{ $t('No users defined.') }}</em></p>
                </template>

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

                </template>
            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import formValidationMixin from "@/mixins/formValidationMixin";
import usersApi from '@/api/user_management/users'
export default {
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
                // users: [], //this.role.relationships.users.data.map(r => r.id),
                users: this.roleUsers.map(r => r.id),
            } : {
                name: null,
                users: [],
            },
            users: []
        }
    },
    created () {
        this.fetchUsers()
    },
    methods: {
        async fetchUsers () {
            let data = await usersApi.names()
            this.users = data.map(e => {
                return {
                    text: e.name,
                    value: e.id
                }
            })
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Really delete this role?'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
