<template>
    <validation-observer
        ref="form"
        v-slot="{ handleSubmit }"
        slim
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-card class="shadow-sm mb-4" :header="$t('Profile')" body-class="pb-2" footer-class="text-right">

                <validation-provider
                    :name="$t('Name')"
                    vid="name"
                    :rules="{ required: true }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Name')"
                        :label-class="!isOauthActive ? 'required' : null"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                        :description="profileUpdateDescription"
                    >
                        <b-form-input
                            v-model="name"
                            autocomplete="off"
                            :disabled="isOauthActive || isBusy"
                            :required="!isOauthActive"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>

                <validation-provider
                    :name="$t('E-Mail Address')"
                    vid="email"
                    :rules="{ email: true }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('E-Mail Address')"
                        :label-class="!isOauthActive ? 'required' : null"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                        :description="profileUpdateDescription"
                    >
                        <b-form-input
                            v-model="email"
                            type="email"
                            autocomplete="off"
                            :disabled="isOauthActive || isBusy"
                            :required="!isOauthActive"
                            :state="getValidationState(validationContext)"
                        />
                    </b-form-group>
                </validation-provider>
                <validation-provider
                    :name="$t('Language')"
                    vid="locale"
                    :rules="{ required: true }"
                    v-slot="validationContext"
                >
                    <b-form-group
                        :label="$t('Language')"
                        label-class="required"
                        :state="getValidationState(validationContext)"
                        :invalid-feedback="validationContext.errors[0]"
                    >
                        <b-select
                            v-model="locale"
                            required
                            :disabled="isBusy"
                            :options="languageOptions"
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
                        {{ $t('Save') }}
                    </b-button>
                </template>
            </b-card>
        </b-form>
    </validation-observer>
</template>

<script>
import userprofileApi from "@/api/userprofile"
import { showSnackbar } from '@/utils'
import moment from 'moment'
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    props: {
        user: {
            required: true,
            type: Object
        },
        languages: {
            required: true,
            type: Object
        },
    },
    data() {
        return {
            isBusy: false,
            name: this.user.name,
            email: this.user.email,
            locale: this.user.locale,
        }
    },
    computed: {
        isOauthActive() {
            return !!this.user.provider_name
        },
        languageOptions() {
            return Object.entries(this.languages).map(e => ({ value: e[0], text: e[1]}))
        },
        profileUpdateDescription() {
            return this.isOauthActive ? this.$t('Update your profile information directly at {provider}', {provider: this.user.provider_name.capitalize() }) : null
        }
    },
    methods: {
        async onSubmit() {
            this.isBusy = true
            try {
                let data = await userprofileApi.updateProfile({
                    name: this.name,
                    email: this.email,
                    locale: this.locale,
                })
                this.$i18n.locale = this.locale
                moment.locale(this.locale);
                showSnackbar(data.message)
                this.$nextTick(() => {
                    this.$refs.form.reset();
                });
                this.$emit('update')
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
    }
}
</script>
