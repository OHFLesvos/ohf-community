<template>
    <validation-observer
        ref="form"
        v-slot="{ handleSubmit }"
        slim
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card class="shadow-sm mb-4" :header="$t('Change Password')" body-class="pb-2" footer-class="text-right">

                <validation-provider
                    :name="$t('Old Password')"
                    vid="old_password"
                    :rules="{ required: true }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Old Password')"
                        label-class="required"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-form-input
                            v-model="old_password"
                            type="password"
                            autocomplete="off"
                            required
                            :disabled="isBusy"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <validation-provider
                    :name="$t('New password')"
                    vid="password"
                    :rules="{ required: true }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('New password')"
                        label-class="required"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-form-input
                            v-model="password"
                            type="password"
                            autocomplete="off"
                            required
                            :disabled="isBusy"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <validation-provider
                    :name="$t('Confirm password')"
                    vid="password_confirmation"
                    :rules="{ required: true }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Confirm password')"
                        label-class="required"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-form-input
                            v-model="password_confirmation"
                            type="password"
                            autocomplete="off"
                            required
                            :disabled="isBusy"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <template #footer>
                    <b-button
                        type="submit"
                        variant="primary"
                        :disabled="isBusy"
                    >
                        <font-awesome-icon icon="check"/>
                        {{ $t('Update password') }}
                    </b-button>
                </template>

            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import userprofileApi from "@/api/userprofile"
import { showSnackbar } from '@/utils'
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    data() {
        return {
            old_password: '',
            password: '',
            password_confirmation: '',
            isBusy: false,
        }
    },
    methods: {
        async onSubmit() {
            this.isBusy = true
            try {
                let data = await userprofileApi.updatePassword({
                    old_password: this.old_password,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                this.old_password = this.password = this.password_confirmation = ''
                showSnackbar(data.message)
                this.$nextTick(() => {
                    this.$refs.form.reset();
                });
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
    }
}
</script>
