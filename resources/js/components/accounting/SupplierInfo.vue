<template>
    <dl class="row">
        <dt class="col-sm-3">{{ $t("Name") }}</dt>
        <dd class="col-sm-9">{{ supplier.name }}</dd>
        <template v-if="supplier.category">
            <dt class="col-sm-3">
                {{ $t("Category") }}
            </dt>
            <dd class="col-sm-9">
                {{ supplier.category }}
            </dd>
        </template>
        <template v-if="supplier.address">
            <dt class="col-sm-3">{{ $t("Address") }}</dt>
            <dd class="col-sm-9">
                <maps-link
                    :label="supplier.address"
                    :query="supplier.address"
                    :place-id="supplier.place_id"
                />
            </dd>
        </template>
        <template v-if="supplier.phone">
            <dt class="col-sm-3">{{ $t("Phone") }}</dt>
            <dd class="col-sm-9">
                <phone-link :value="supplier.phone" />
            </dd>
        </template>
        <template v-if="supplier.mobile">
            <dt class="col-sm-3">{{ $t("Mobile") }}</dt>
            <dd class="col-sm-9">
                <phone-link :value="supplier.mobile" mobile />
            </dd>
        </template>
        <template v-if="supplier.email">
            <dt class="col-sm-3">{{ $t("Email address") }}</dt>
            <dd class="col-sm-9">
                <email-link :value="supplier.email" />
            </dd>
        </template>
        <template v-if="supplier.tax_number && supplier.tax_office">
            <dt class="col-sm-3">{{ $t("Tax number") }}</dt>
            <dd class="col-sm-9">
                {{ supplier.tax_number }}, {{ supplier.tax_office }}
            </dd>
        </template>
        <template v-if="supplier.bank && supplier.iban">
            <dt class="col-sm-3">{{ $t("Bank") }}</dt>
            <dd class="col-sm-9">
                {{ supplier.bank }}<br />{{ supplier.iban }}
            </dd>
        </template>
        <template v-if="supplier.remarks">
            <dt class="col-sm-3">{{ $t("Remarks") }}</dt>
            <dd class="col-sm-9">
                {{ supplier.remarks }}
            </dd>
        </template>
    </dl>
</template>

<script>
import PhoneLink from "@/components/common/PhoneLink.vue";
import EmailLink from "@/components/common/EmailLink.vue";
import MapsLink from "@/components/common/MapsLink.vue";
export default {
    components: {
        EmailLink,
        PhoneLink,
        MapsLink
    },
    props: {
        supplier: {
            required: true
        }
    }
};
</script>
