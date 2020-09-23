<template>
    <b-container>
        <div v-if="showRegisterForm">
            <h3>{{ $t('app.check_in') }}</h3>
            <register-visitor-form
                :disabled="isBusy"
                @submit="registerVisitor"
                @cancel="showRegisterForm = false"
            />
        </div>
        <p>
            <b-button
                v-if="!showRegisterForm"
                variant="primary"
                @click="showRegisterForm = true"
            >
                <font-awesome-icon icon="sign-in-alt"/>
                {{ $t('app.check_in') }}
            </b-button>
        </p>
        <hr>
        <h3>{{ $t('visitors.current_visitors') }} ({{ count }})</h3>
        <base-table
            ref="table"
            id="visitors-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="entered_at"
            :empty-text="$t('app.no_data_registered')"
            :items-per-page="100"
            @count="count = $event"
        >
            <template v-slot:cell(checkout)="data">
                <b-button
                  size="sm"
                  variant="primary"
                  :disabled="isBusy"
                  @click="checkoutVisitor(data.item.id)"
                >
                    <font-awesome-icon icon="sign-out-alt"/>
                    {{ $t('app.checkout') }}
                </b-button>
            </template>
        </base-table>
    </b-container>
</template>

<script>
import moment from 'moment'
import showSnackbar from '@/snackbar'
import visitorsApi from '@/api/visitors'
import RegisterVisitorForm from '@/components/visitors/RegisterVisitorForm'
import BaseTable from '@/components/table/BaseTable'
export default {
    components: {
        RegisterVisitorForm,
        BaseTable
    },
    data () {
        return {
            isBusy: false,
            showRegisterForm: false,
            fields: [
                {
                    key: 'last_name',
                    label: this.$t('app.last_name'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'first_name',
                    label: this.$t('app.first_name'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'id_number',
                    label: this.$t('app.id_number'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'place_of_residence',
                    label: this.$t('app.place_of_residence'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'entered_at',
                    label: this.$t('app.time'),
                    sortable: true,
                    sortDirection: 'desc',
                    tdClass: 'align-middle',
                    formatter: value => {
                        return moment(value).format('HH:mm')
                    },
                    class: 'fit'
                },
                {
                    key: 'checkout',
                    label: this.$t('app.checkout'),
                    class: 'fit'
                }
            ],
            count: 0
        }
    },
    methods: {
        async fetchData (ctx) {
            return visitorsApi.listCurrent(ctx)
        },
        async registerVisitor (formData) {
            this.isBusy = true
            try {
                await visitorsApi.checkin(formData)
                showSnackbar('Checked-in')
                this.isBusy = false
                this.$refs.table.refresh()
            } catch (err) {
                alert(err)
                this.isBusy = false
            }
        },
        async checkoutVisitor(id) {
            this.isBusy = true
            try {
                await visitorsApi.checkout(id)
                this.isBusy = false
                showSnackbar('Checked out')
                this.$refs.table.refresh()
            } catch (err) {
                alert(err)
                this.isBusy = false
            }
        }
    }
}
</script>
