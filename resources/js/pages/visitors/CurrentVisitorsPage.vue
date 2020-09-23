<template>
    <b-container>
        <b-card
          v-if="showRegisterForm"
          :header="$t('app.check_in')"
          body-class="pb-0"
          class="mb-4"
        >
            <register-visitor-form
                :disabled="isBusy"
                @submit="registerVisitor"
                @cancel="showRegisterForm = false"
            />
        </b-card>
        <b-row
          v-if="!showRegisterForm"
          class="mb-4"
        >
            <b-col>
                <b-button
                    variant="primary"
                    @click="showRegisterForm = true"
                >
                    <font-awesome-icon icon="sign-in-alt"/>
                    {{ $t('app.check_in') }}
                </b-button>
            </b-col>
            <b-col class="text-right">
                <b-button
                    variant="secondary"
                    :to="{ name: 'visitors.report' }"
                >
                    <font-awesome-icon icon="chart-bar"/>
                    {{ $t('app.report') }}
                </b-button>
                <b-button
                    v-if="count !== 0"
                    variant="secondary"
                    :disabled="isBusy"
                    @click="checkoutAll"
                >
                    <font-awesome-icon icon="door-closed"/>
                    {{ $t('app.checkout_everyone') }}
                </b-button>
            </b-col>
        </b-row>
        <h3>{{ $t('visitors.current_visitors') }} ({{ count }})</h3>
        <base-table
            ref="table"
            id="visitors-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="entered_at"
            default-sort-desc
            :empty-text="$t('app.no_data_registered')"
            :items-per-page="100"
            @metadata="count = $event.total_visiting"
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
                showSnackbar(this.$t('app.checked_in'))
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
                showSnackbar(this.$t('app.checked_out'))
                this.$refs.table.refresh()
            } catch (err) {
                alert(err)
                this.isBusy = false
            }
        },
        async checkoutAll() {
            if (confirm(this.$t('app.really_checkout_everyone'))) {
                this.isBusy = true
                try {
                    await visitorsApi.checkoutAll()
                    this.isBusy = false
                    showSnackbar(this.$t('app.everyone_checked_out'))
                    this.$refs.table.refresh()
                } catch (err) {
                    alert(err)
                    this.isBusy = false
                }
            }
        }
    }
}
</script>
