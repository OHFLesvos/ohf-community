<template>
    <b-container
        v-if="donor"
        fluid
        class="px-0"
    >
        <donor-form
            :donor="donor"
            :disabled="isBusy"
            @submit="updateDonor"
            @cancel="$router.push({ name: 'fundraising.donors.show', params: { id: id }})"
            @delete="deleteDonor"
        />
        <hr>
        <p class="text-right">
            <small>
                {{ $t('Last updated') }}:
                {{ donor.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import donorsApi from '@/api/fundraising/donors'
import { showSnackbar } from '@/utils'
import DonorForm from '@/components/fundraising/DonorForm'
export default {
    components: {
        DonorForm
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            donor: null,
            isBusy: false
        }
    },
    watch: {
        $route() {
            this.fetchDonor()
        }
    },
    async created () {
        this.fetchDonor()
    },
    methods: {
        async fetchDonor () {
            try {
                let data = await donorsApi.find(this.id)
                this.donor = data.data
            } catch (err) {
                alert(err)
            }
        },
        async updateDonor (formData) {
            this.isBusy = true
            try {
                let data = await donorsApi.update(this.id, formData)
                showSnackbar(data.message)
                this.$router.push({ name: 'fundraising.donors.show', params: { id: this.id }})
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
                this.$router.push({ name: 'fundraising.donors.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        }
    }
}
</script>
