<template>
    <validation-observer ref="observer" v-slot="{ handleSubmit }" slim>
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card :title="title" body-class="pb-1" footer-class="d-flex justify-content-between align-items-start" class="mb-3">
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
                                    autocomplete="off"
                                    :autofocus="!wallet"
                                    :state="getValidationState(validationContext)"
                                />
                            </b-form-group>
                        </validation-provider>
                    </b-col>
                </b-form-row>

                <b-form-group
                    v-if="roles.length > 0"
                    :label="$t('User roles with access (Whitelist)')"
                >
                    <b-form-checkbox-group
                        v-model="form.roles"
                        :options="roles"
                        stacked
                    />
                </b-form-group>
                <template v-else>
                    <p><em>{{ $t('No roles defined.') }}</em></p>
                </template>
                <b-alert variant="info" :show="!form.roles.length">{{ $t('Specifying no role will allow access by all roles.') }}</b-alert>

                <template #footer>
                    <span>
                        <!-- Submit -->
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="disabled"
                        >
                            <font-awesome-icon icon="check" />
                            {{ wallet ? $t('Update') : $t('Add') }}
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
                        v-if="wallet && wallet.can_delete"
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
import rolesApi from '@/api/user_management/roles'
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    props: {
        wallet: {
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
            form: this.wallet ? {
                name: this.wallet.name,
                roles: this.wallet.roles.map(r => r.id)
            } : {
                name: null,
                roles: []
            },
            roles: []
        }
    },
    created () {
        this.fetchRoles()
    },
    methods: {
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('Really delete this wallet?'))) {
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
