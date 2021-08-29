<template>
    <b-container
        fluid
        class="px-0"
    >
        <donor-form
            :disabled="isBusy"
            @submit="registerDonor"
            @cancel="handleCancel"
        />
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
                this.$router.push({ name: 'fundraising.donors.show', params: { id: data.id }})
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'fundraising.donors.index' })
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>
