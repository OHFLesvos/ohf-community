<template>
    <b-list-group v-if="supplier" class="mb-3" flush>
        <two-col-list-group-item :title="$t('Name')">
            {{ supplier.name }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="supplier.category"
            :title="$t('Category')"
        >
            {{ supplier.category }}
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.address" :title="$t('Address')">
            <maps-link
                :label="supplier.address"
                :query="supplier.address"
                :place-id="supplier.place_id"
            />
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.phone" :title="$t('Phone')">
            <phone-link :value="supplier.phone" />
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.mobile" :title="$t('Mobile')">
            <phone-link :value="supplier.mobile" mobile />
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="supplier.email"
            :title="$t('Email address')"
        >
            <email-link :value="supplier.email" />
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.website" :title="$t('Website')">
            <a :href="supplier.website" target="_blank">{{
                supplier.website
            }}</a>
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="supplier.tax_number"
            :title="$t('Tax number')"
        >
            {{ supplier.tax_number }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="supplier.tax_office"
            :title="$t('Tax office')"
        >
            {{ supplier.tax_office }}
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.bank" :title="$t('Bank')">
            {{ supplier.bank }}
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.iban" :title="$t('IBAN')">
            {{ supplier.iban }}
        </two-col-list-group-item>

        <two-col-list-group-item v-if="supplier.remarks" :title="$t('Remarks')">
            {{ supplier.remarks }}
        </two-col-list-group-item>

        <two-col-list-group-item
            v-if="supplier.spending"
            :title="$t('Spending')"
        >
            {{ supplier.spending | decimalNumberFormat }}
        </two-col-list-group-item>
    </b-list-group>
    <p v-else>
        {{ $t("Loading...") }}
    </p>
</template>

<script>
import suppliersApi from "@/api/accounting/suppliers";
import TwoColListGroupItem from "@/components/ui/TwoColListGroupItem";
import PhoneLink from "@/components/common/PhoneLink";
import EmailLink from "@/components/common/EmailLink";
import MapsLink from "@/components/common/MapsLink";
export default {
    components: {
        TwoColListGroupItem,
        EmailLink,
        PhoneLink,
        MapsLink
    },
    props: {
        id: {
            required: true
        }
    },
    data() {
        return {
            supplier: null
        };
    },
    async created() {
        this.fetchSupplier();
    },
    methods: {
        async fetchSupplier() {
            try {
                let data = await suppliersApi.find(this.id, {
                    with_spending: true
                });
                this.supplier = data.data;
            } catch (err) {
                alert(err);
            }
        }
    }
};
</script>
