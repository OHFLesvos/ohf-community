<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
        slim
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">

            <b-form-row>

                <!-- Name -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.name')"
                        vid="name"
                        :rules="{ required: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.name')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.name"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <p>
                <b-form-checkbox
                    v-model="form.is_default"
                >
                    {{ $t('app.default') }}
                </b-form-checkbox>
            </p>

            <b-card
                :header="$t('app.roles_with_access')"
                class="mb-4"
                body-class="pb-0"
            >
                <p><em>{{ $t('app.specifying_no_role_will_allow_access_by_any') }}</em></p>
                <b-form-group
                    v-if="roles.length > 0"
                    :label="$t('app.roles')"
                >
                    <b-form-checkbox-group
                        v-model="form.roles"
                        :options="roles"
                        stacked
                    />
                </b-form-group>
                <template v-else>
                    <p><em>{{ $t('app.no_roles_defined') }}</em></p>
                </template>
            </b-card>

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled"
                    >
                        <font-awesome-icon icon="check" />
                        {{ wallet ? $t('app.update') : $t('app.add') }}
                    </b-button>

                    <!-- Cancel -->
                    <b-button
                        variant="link"
                        :disabled="disabled"
                        @click="$emit('cancel')"
                    >
                        {{ $t('app.cancel') }}
                    </b-button>
                </span>

                <!-- Delete -->
                <b-button
                    v-if="wallet && wallet.can_delete"
                    variant="link"
                    :disabled="disabled"
                    class="text-danger"
                    @click="onDelete"
                >
                    {{ $t('app.delete') }}
                </b-button>

            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import rolesApi from '@/api/user_management/roles'
export default {
    props: {
        wallet: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data () {
        return {
            form: this.wallet ? {
                name: this.wallet.name,
                is_default: this.wallet.is_default,
                roles: this.wallet.roles.map(r => r.id)
            } : {
                name: null,
                is_default: false,
                roles: []
            },
            roles: []
        }
    },
    created () {
        this.fetchRoles()
    },
    methods: {
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('app.confirm_delete_wallet'))) {
                this.$emit('delete')
            }
        },
        async fetchRoles () {
            let data = await rolesApi.list()
            this.roles = data.data.map(r => {
                return {
                    text: r.name,
                    value: r.id
                }
            })
        }
    }
}
</script>
