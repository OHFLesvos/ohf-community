<template>
    <b-container>
        <validation-observer
            ref="observer"
            v-slot="{ handleSubmit }"
            slim
        >
            <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
                <b-card :title="$t('Import')">

                    <!-- Type -->
                    <b-form-group
                        :label="$t('Type')"
                    >
                        <b-form-radio-group
                            v-model="form.type"
                            :options="types"
                        />
                    </b-form-group>

                    <!-- File -->
                    <validation-provider
                        :name="$t('File')"
                        vid="file"
                        :rules="{ required: true, ext: ['xlsx', 'xls', 'csv'] }"
                        v-slot="validationContext"
                    >
                        <b-form-group
                            :label="$t('File')"
                            :state="getValidationState(validationContext)"
                            :invalid-feedback="validationContext.errors[0]"
                        >
                            <b-form-file
                                v-model="form.file"
                                :placeholder="$t('Choose file...')"
                                accept=".xlsx, .xls, .csv"
                                required
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
                            <font-awesome-icon icon="upload" />
                            {{ $t('Import') }}
                        </b-button>
                    </template>
                </b-card>
            </b-form>
        </validation-observer>
    </b-container>
</template>

<script>
import donationsApi from '@/api/fundraising/donations'
import { showSnackbar } from '@/utils'
import formValidationMixin from "@/mixins/formValidationMixin";
export default {
    mixins: [formValidationMixin],
    title() {
        return this.$t("Import");
    },
    data () {
        return {
            types: [
                {
                    value: 'stripe',
                    text: 'Stripe'
                }
            ],
            form: {
                file: null,
                type: 'stripe'
            },
            isBusy: false
        }
    },
    methods: {
        async onSubmit () {
            this.isBusy = true
            try {
                let data = await donationsApi.import(this.form.type, this.form.file)
                showSnackbar(data.message)
                this.$router.push({ name: 'fundraising.donations.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
    }
}
</script>
