<template>
    <b-container
        v-if="donor"
        fluid
        class="px-0"
    >
        <b-card
            class="mb-4"
            body-class="pb-0"
            header-class="d-flex justify-content-between align-items-center"
        >
            <template v-slot:header>
                {{ $t('fundraising.edit_donor') }}
                <small class="d-none d-sm-inline">
                    {{ $t('app.last_updated') }}:
                    {{ dateFormat(donor.updated_at) }}
                </small>
            </template>

            <donor-form
                :donor="donor"
                :disabled="isBusy"
                @submit="updateDonor"
                @cancel="handleCnacel"
                @delete="deleteDonor"
            />
        </b-card>
    </b-container>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
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
    props: {
        id: {
            required: true,
            type: Number
        }
    },
    data () {
        return {
            donor: null,
            isBusy: false
        }
    },
    async created () {
        try {
            let data = await donorsApi.find(this.id)
            this.donor = data.data
        } catch (err) {
            alert(err)
        }
    },
    methods: {
        async updateDonor (formData) {
            this.isBusy = true
            try {
                let data = await donorsApi.update(this.id, formData)
                showSnackbar(data.message)
                window.location.href = this.route('fundraising.donors.show', this.id)
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async deleteDonor () {
            this.isBusy = true
            try {
                let data = await donorsApi.delete(this.id)
                showSnackbar(data.message)
                window.location.href = this.route('fundraising.donors.index')
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCnacel () {
            window.location.href = this.route('fundraising.donors.show', this.id)
        },
        dateFormat (value) {
            return moment(value).format('LLL')
        }
    }
}
</script>
