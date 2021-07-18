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
                                :autofocus="!supplier"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

                <!-- Category -->
                <b-col md>
                    <validation-provider
                        :name="$t('Category')"
                        vid="category"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Category')"
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
                <b-col md="10">
                    <validation-provider
                        :name="$t('Address')"
                        vid="address"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Address')"
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

                <!-- Place ID -->
                <b-col md="2">
                    <validation-provider
                        :name="$t('Place ID')"
                        vid="place_id"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Place ID')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.place_id"
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
                        :name="$t('Phone')"
                        vid="phone"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Phone')"
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
                        :name="$t('Mobile')"
                        vid="mobile"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Mobile')"
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

            </b-form-row>

            <b-form-row>

                <!-- E-Mail -->
                <b-col md>
                    <validation-provider
                        :name="$t('E-Mail Address')"
                        vid="email"
                        :rules="{ email: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('E-Mail Address')"
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

                <!-- Website -->
                <b-col md>
                    <validation-provider
                        :name="$t('Website')"
                        vid="website"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Website')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.website"
                                autocomplete="off"
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
                        :name="$t('Tax number')"
                        vid="tax_number"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Tax number')"
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

                <!-- Tax office -->
                <b-col md>
                    <validation-provider
                        :name="$t('Tax office')"
                        vid="tax_office"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Tax office')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.tax_office"
                                autocomplete="off"
                                :state="getValidationState(validationContext)"
                            />
                        </b-form-group>
                    </validation-provider>
                </b-col>

            </b-form-row>

            <b-form-row>

                <!-- Bank -->
                <b-col md>
                    <validation-provider
                        :name="$t('Bank')"
                        vid="bank"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Bank')"
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
                        :name="$t('IBAN')"
                        vid="iban"
                        :rules="{ iban: true }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('IBAN')"
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

            <b-form-row>

                <!-- Remarks -->
                <b-col md>
                    <validation-provider
                        :name="$t('Remarks')"
                        vid="remarks"
                        :rules="{ }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('Remarks')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-input
                                v-model="form.remarks"
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
                        {{ supplier ? $t('Update') : $t('Add') }}
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
                    v-if="supplier && supplier.can_delete"
                    variant="link"
                    :disabled="disabled"
                    class="text-danger"
                    @click="onDelete"
                >
                    {{ $t('Delete') }}
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
                place_id: this.supplier.place_id,
                phone: this.supplier.phone,
                mobile: this.supplier.mobile,
                email: this.supplier.email,
                website: this.supplier.website,
                tax_number: this.supplier.tax_number,
                tax_office: this.supplier.tax_office,
                bank: this.supplier.bank,
                iban: this.supplier.iban,
                remarks: this.supplier.remarks
            } : {
                name: null,
                category: null,
                address: null,
                place_id: null,
                phone: null,
                mobile: null,
                email: null,
                website: null,
                tax_number: null,
                tax_office: null,
                bank: null,
                iban: null,
                remarks: null
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
            if (confirm(this.$t('Should this supplier really be deleted?'))) {
                this.$emit('delete')
            }
        }
    }
}
</script>
