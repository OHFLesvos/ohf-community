<template>
    <b-container
      fluid="md"
      class="px-0"
    >
        <b-card
          v-if="showRegisterForm"
          body-class="pb-0"
          class="mb-4"
          header-class="d-flex justify-content-between"
        >
            <template v-slot:header>
                <span>{{ $t('Check-in') }}</span>
                <b-button
                    variant="link"
                    size="sm"
                    @click="showRegisterForm = false"
                >
                    <font-awesome-icon icon="times" />
                </b-button>
            </template>
            <register-visitor-form
                :disabled="isBusy"
                @submit="registerVisitor"
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
                    {{ $t('Check-in') }}
                </b-button>
            </b-col>
            <b-col class="text-right">
                <b-dropdown
                    class="d-block d-md-none"
                    right
                    :text="$t('Actions')"
                >
                    <b-dropdown-item
                        v-if="count !== 0"
                        @click="checkoutAll"
                    >
                        <font-awesome-icon icon="door-closed"/>
                        {{ $t('Checkout everyone') }}
                    </b-dropdown-item>
                </b-dropdown>
                <span class="d-none d-md-inline">
                    <b-button
                        v-if="count !== 0"
                        variant="secondary"
                        :disabled="isBusy"
                        @click="checkoutAll"
                    >
                        <font-awesome-icon icon="door-closed"/>
                        {{ $t('Checkout everyone') }}
                    </b-button>
                </span>
            </b-col>
        </b-row>
        <h3>{{ $t('Current Visitors') }}</h3>
        <p class="text-muted">
            <span v-for="(v, k) in currentlyVisiting" :key="k">
                {{ getTypeLabel(k) }}: <strong>{{ v }}</strong>,
            </span>
            {{ $t('Total') }}: <strong>{{ count }}</strong>
        </p>
        <base-table
            ref="table"
            id="visitors-table"
            :fields="fields"
            :api-method="fetchData"
            default-sort-by="entered_at"
            default-sort-desc
            :empty-text="$t('No data registered.')"
            :items-per-page="100"
            @metadata="currentlyVisiting = $event.currently_visiting"
        >
            <template v-slot:cell(checkout)="data">
                <b-button
                  size="sm"
                  variant="primary"
                  :disabled="isBusy"
                  @click="checkoutVisitor(data.item.id)"
                >
                    <font-awesome-icon icon="sign-out-alt"/>
                    {{ $t('Checkout') }}
                </b-button>
            </template>
        </base-table>
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import visitorsApi from '@/api/visitors'
import RegisterVisitorForm from '@/components/visitors/RegisterVisitorForm'
import BaseTable from '@/components/table/BaseTable'
export default {
    title() {
        return this.$t("Visitors");
    },
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
                    label: this.$t('Last Name'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'first_name',
                    label: this.$t('First Name'),
                    sortable: true,
                    tdClass: 'align-middle'
                },
                {
                    key: 'type',
                    label: this.$t('Type'),
                    tdClass: 'align-middle',
                    formatter: this.getTypeLabel,
                },
                {
                    key: 'additional_info',
                    label: this.$t('Additional Information'),
                    tdClass: 'align-middle',
                    formatter: (value, key, item) => {
                        const items = Array()
                        if (item.id_number) {
                            items.push(`${this.$t('ID Number')}: ${item.id_number}`)
                        }
                        if (item.place_of_residence) {
                            items.push(`${this.$t('Place of residence')}: ${item.place_of_residence}`)
                        }
                        if (item.activity) {
                            items.push(`${this.$t('Activity / Program')}: ${item.activity}`)
                        }
                        if (item.organization) {
                            items.push(`${this.$t('Organization')}: ${item.organization}`)
                        }
                        return items.join(', ')
                    }
                },
                {
                    key: 'entered_at',
                    label: this.$t('Time'),
                    sortable: true,
                    sortDirection: 'desc',
                    tdClass: 'align-middle',
                    formatter: this.timeFormat,
                    class: 'fit'
                },
                {
                    key: 'checkout',
                    label: this.$t('Checkout'),
                    class: 'fit'
                }
            ],
            currentlyVisiting: {}
        }
    },
    computed: {
        count () {
            return Object.values(this.currentlyVisiting).reduce((a, b) => a + b, 0)
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
                showSnackbar(this.$t('Checked-in.'))
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
                showSnackbar(this.$t('Checked out.'))
                this.$refs.table.refresh()
            } catch (err) {
                alert(err)
                this.isBusy = false
            }
        },
        async checkoutAll() {
            if (confirm(this.$t('Really checkout everyone?'))) {
                this.isBusy = true
                try {
                    await visitorsApi.checkoutAll()
                    this.isBusy = false
                    showSnackbar(this.$t('Everyone has been checked out.'))
                    this.$refs.table.refresh()
                } catch (err) {
                    alert(err)
                    this.isBusy = false
                }
            }
        },
        getTypeLabel (value) {
            if (value == 'visitor') {
                return this.$t('Visitor')
            }
            if (value == 'visitors') {
                return this.$t('Visitors')
            }
            if (value == 'participant') {
                return this.$t('Participant')
            }
            if (value == 'participants') {
                return this.$t('Participants')
            }
            if (value == 'staff') {
                return this.$t('Volunteer / Staff')
            }
            if (value == 'external') {
                return this.$t('External visitor')
            }
            return value
        }
    }
}
</script>
