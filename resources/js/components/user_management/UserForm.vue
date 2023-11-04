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
                                :description="managedByProvider"
                            >
                                <b-form-input
                                    v-model="form.name"
                                    autocomplete="off"
                                    :disabled="!!user.provider_name"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>
                <b-form-row>

                    <!-- E-Mail -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Email address')"
                            vid="email"
                            :rules="{ email: true }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Email address')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                                :description="managedByProvider"
                            >
                                <b-form-input
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="off"
                                    :disabled="!!user.provider_name"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                    <!-- Password -->
                    <b-col md>
                        <validation-provider
                            :name="$t('Password')"
                            vid="password"
                            :rules="{ }"
                            v-slot="validationContext"
                        >
                            <b-form-group
                                :label="$t('Password')"
                                :state="getValidationState(validationContext)"
                                :invalid-feedback="validationContext.errors[0]"
                                :description="managedByProvider"
                            >
                                <b-form-input
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="new-password"
                                    :disabled="!!user.provider_name"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>

                </b-form-row>

                <!-- Roles -->
                <b-form-group
                    v-if="roles.length > 0"
                    :label="$t('Roles')"
                >
                    <div class="columns-2">
                    <b-form-checkbox-group
                        v-model="form.roles"
                        :options="roles"
                        stacked
                    />
                </div>
                </b-form-group>
                <template v-else>
                    <p><em>{{ $t('No roles defined.') }}</em></p>
                </template>
                <hr>
                <b-form-row class="mb-2">
                    <b-col sm>
                        <b-form-checkbox v-model="form.is_super_admin">
                            {{ $t("This user is an administrator") }}
                        </b-form-checkbox>
                    </b-col>
                </b-form-row>

                <template #footer>
                    <span>
                        <!-- Submit -->
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="disabled"
                        >
                            <font-awesome-icon icon="check" />
                            {{ user ? $t('Update') : $t('Add') }}
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
                        v-if="user && user.can_delete"
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
import rolesApi from '@/api/user_management/roles'
export default {
    mixins: [formValidationMixin],
    props: {
        user: {
            type: Object,
            required: false
        },
        title: {
            required: false,
            default: undefined
        },
        disabled: Boolean
    },
    data () {
        return {
            form: this.user ? {
                name: this.user.name,
                email: this.user.email,
                password: null,
                roles: this.user.relationships.roles.data.map(r => r.id),
                is_super_admin: this.user.is_super_admin,
            } : {
                name: null,
                email: null,
                password: null,
                roles: [],
                is_super_admin: false,
            },
            roles: []
        }
    },
    computed: {
        managedByProvider() {
            return this.user.provider_name != null
                ? this.$t('Managed by {provider}', { provider: this.user.provider_name.charAt(0).toUpperCase() + this.user.provider_name.slice(1) } )
                : null
        }
    },
    created () {
        this.fetchRoles()
    },
    methods: {
        async fetchRoles () {
            let data = await rolesApi.list()
            this.roles = data.data.map(r => {
                return {
                    text: r.name,
                    value: r.id
                }
            })
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Really delete this user?'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
