<template>
    <b-container fluid class="px-0">
        <b-card
            class="mb-4"
            body-class="pb-0"
            :header="$t('fundraising.create_donor')"
        >
            <donor-form
                :disabled="isBusy"
                @submit="registerDonor"
                @cancel="handleCnacel"
            />
        </b-card>
    </b-container>
</template>

<script>
import moment from 'moment'
import donorsApi from '@/api/fundraising/donors'
import { showSnackbar } from '@/utils'
import DonorForm from '@/components/fundraising/DonorForm'
export default {
    components: {
        DonorForm
    },
    data () {
        return {
            isBusy: false
        }
    },
    methods: {
        async registerDonor (formData) {
            this.isBusy = true
            try {
                let data = await donorsApi.store(formData)
                showSnackbar(data.message)
                window.location.href = this.route('fundraising.donors.show', data.id)
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCnacel () {
            window.location.href = this.route('fundraising.donors.index')
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>
