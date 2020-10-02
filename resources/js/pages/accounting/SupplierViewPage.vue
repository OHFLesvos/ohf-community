<template>
    <b-container
        v-if="supplier"
        class="px-0"
    >
        <h2>{{ supplier.name }}</h2>
        <b-list-group class="mb-3">

            <two-col-list-group-item
                v-if="supplier.category"
                :title="$t('app.category')"
            >
                {{ supplier.category }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.address"
                :title="$t('app.address')"
            >
                {{ supplier.address }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.phone"
                :title="$t('app.phone')"
            >
                <phone-link
                  :value="supplier.phone"
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.mobile"
                :title="$t('app.mobile')"
            >
                <phone-link
                  :value="supplier.mobile"
                  mobile
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.email"
                :title="$t('app.email')"
            >
                <email-link
                  :value="supplier.email"
                />
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.tax_number"
                :title="$t('accounting.tax_number')"
            >
                {{ supplier.tax_number }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.bank"
                :title="$t('accounting.bank')"
            >
                {{ supplier.bank }}
            </two-col-list-group-item>

            <two-col-list-group-item
                v-if="supplier.iban"
                :title="$t('accounting.iban')"
            >
                {{ supplier.iban }}
            </two-col-list-group-item>

        </b-list-group>

        <p class="d-flex justify-content-between">
            <b-button
                variant="secondary"
                :to="{ name: 'accounting.suppliers.index' }"
            >
                <font-awesome-icon icon="arrow-left" />
                {{ $t('app.overview') }}
            </b-button>
            <b-button
                v-if="supplier.can_update"
                variant="primary"
                :to="{ name: 'accounting.suppliers.edit',  }"
            >
                <font-awesome-icon icon="edit" />
                {{ $t('app.edit') }}
            </b-button>
        </p>
    </b-container>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import suppliersApi from '@/api/accounting/suppliers'
import TwoColListGroupItem from '@/components/ui/TwoColListGroupItem'
import PhoneLink from '@/components/common/PhoneLink'
import EmailLink from '@/components/common/EmailLink'
export default {
    components: {
        TwoColListGroupItem,
        EmailLink,
        PhoneLink
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            supplier: null
        }
    },
    watch: {
        $route() {
            this.fetchSupplier()
        }
    },
    async created () {
        this.fetchSupplier()
    },
    methods: {
        async fetchSupplier () {
            try {
                let data = await suppliersApi.find(this.id)
                this.supplier = data.data
            } catch (err) {
                alert(err)
            }
        }
    }
}
</script>