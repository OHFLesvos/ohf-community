<template>
    <b-table-simple
        small
        class="mt-5"
    >
        <b-thead>
            <b-tr>
                <b-th>{{ $t('app.year') }}</b-th>
                <b-th class="text-right">{{ $t('app.amount') }}</b-th>
            </b-tr>
        </b-thead>
        <b-tbody>
            <b-tr
                v-for="donation in donations"
                :key="donation.year"
            >
                <b-td>
                    {{ donation.year }}
                </b-td>
                <b-td class="text-right" style="text-decoration: underline;">
                        {{ baseCurrency }}
                        {{ donation.total }}
                </b-td>
            </b-tr>
        </b-tbody>
    </b-table-simple>
</template>

<script>
export default {
    props: {
        donations: {
            type: Array,
            required: true
        },
        baseCurrency: {
            required: true,
            type: String
        }
    },
}
</script>
