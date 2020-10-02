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

                <!-- Category -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.category')"
                        vid="category"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.category')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.category"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <b-form-row>

                <!-- Address -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.address')"
                        vid="address"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.address')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.address"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <b-form-row>

                <!-- Phone -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.phone')"
                        vid="phone"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.phone')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.phone"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Mobile -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.mobile')"
                        vid="mobile"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.mobile')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.mobile"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- E-Mail -->
                <b-col md>
                    <validation-provider
                        :name="$t('app.email')"
                        vid="email"
                        :rules="{ email: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('app.email')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.email"
                                autocomplete="off"
                                type="email"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <b-form-row>

                <!-- Tax number -->
                <b-col md>
                    <validation-provider
                        :name="$t('accounting.tax_number')"
                        vid="tax_number"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('accounting.tax_number')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.tax_number"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Bank -->
                <b-col md>
                    <validation-provider
                        :name="$t('accounting.bank')"
                        vid="bank"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('accounting.bank')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.bank"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- IBAN -->
                <b-col md>
                    <validation-provider
                        :name="$t('accounting.iban')"
                        vid="iban"
                        :rules="{ iban: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('accounting.iban')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.iban"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <p class="d-flex justify-content-between align-items-start">
                <span>
                    <!-- Submit -->
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="disabled"
                    >
                        <font-awesome-icon icon="check" />
                        {{ supplier ? $t('app.update') : $t('app.add') }}
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
                    v-if="supplier && supplier.can_delete"
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
export default {
    props: {
        supplier: {
            type: Object,
            required: false
        },
        disabled: Boolean
    },
    data () {
        return {
            form: this.supplier ? {
                name: this.supplier.name,
                category: this.supplier.category,
                address: this.supplier.address,
                phone: this.supplier.phone,
                mobile: this.supplier.mobile,
                email: this.supplier.email,
                tax_number: this.supplier.tax_number,
                bank: this.supplier.bank,
                iban: this.supplier.iban,
            } : {
                name: null,
                category: null,
                address: null,
                phone: null,
                mobile: null,
                email: null,
                tax_number: null,
                bank: null,
                iban: null,
            }
        }
    },
    methods: {
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
        onSubmit () {
            this.$emit('submit', this.form)
        },
        onDelete () {
            if (confirm(this.$t('accounting.confirm_delete_supplier'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>