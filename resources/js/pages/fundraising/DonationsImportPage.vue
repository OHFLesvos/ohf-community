<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
        slim
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">

            <!-- Type -->
            <b-form-group
                :label="$t('app.type')"
            >
                <b-form-radio-group
                    v-model="form.type"
                    :options="types"
                />
            </b-form-group>

            <!-- File -->
            <validation-provider
                :name="$t('app.file')"
                vid="file"
                :rules="{ required: true, ext: ['xlsx', 'xls', 'csv'] }"
                v-slot="validationContext"
            >
                <b-form-group
                    :label="$t('app.file')"
                    :state="getValidationState(validationContext)"
                    :invalid-feedback="validationContext.errors[0]"
                >
                    <b-form-file
                        v-model="form.file"
                        :placeholder="$t('app.choose_file')"
                        accept=".xlsx, .xls, .csv"
                        required
                        :state="getValidationState(validationContext)"
                    />
                </b-form-group>
            </validation-provider>

            <p>
                <b-button
                    type="submit"
                    variant="primary"
                    :disabled="isBusy"
                >
                    <font-awesome-icon icon="upload" />
                    {{ $t('app.import') }}
                </b-button>
            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import donationsApi from '@/api/fundraising/donations'
import { showSnackbar } from '@/utils'
export default {
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
        getValidationState ({ dirty, validated, valid = null }) {
            return dirty || validated ? valid : null;
        },
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